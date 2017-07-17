<?php

namespace App\Repositories\Contracts;

use App\Entities\Car;
use App\Repositories\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AbstractRepository
 * @package App\Repositories\Contracts
 */
abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * @var array Raw mock data.
     */
    protected static $itemsData;

    /**
     * @var Collection Wrapped data to handy work with.
     */
    protected static $itemsCollection = null;

    public function __construct()
    {
        if (!is_null(self::$itemsCollection)) {
            return;
        }

        self::$itemsCollection = new Collection();

        foreach (static::$itemsData as $data) {
            $item = $this->createEntity($data);
            self::$itemsCollection->push($item);
        }
    }

    /**
     * Creates an entity of a repository type
     *
     * @param array $data
     * @return mixed
     */
    abstract protected function createEntity(array $data);

    /**
     * @inheritdoc
     */
    public function getAll() : Collection
    {
        return self::$itemsCollection->sortBy(function ($entity) {
            return $entity->getId();
        });
    }

    /**
     * @inheritdoc
     */
    public function getById(int $id)
    {
        $item = self::$itemsCollection->filter(function ($entity) use ($id) {
            return $entity->getId() === $id;
        })->first();

        if (!$item) {
            return null;
        }

        return $item;
    }

    /**
     * @inheritdoc
     */
    public function addItem($entity) : Car
    {
        $id = $this->getNextIndex();
        $entity->setId($id);
        self::$itemsCollection = self::$itemsCollection->push($entity);

        return $this->getById($id);
    }

    /**
     * @inheritdoc
     */
    public function update($entity) : Car
    {
        $id = $entity->getId();

        $notFound = self::$itemsCollection->filter(function ($entity) use ($id) {
            return $entity->getId() == $id;
        })->isEmpty();

        if ($notFound) {
            throw new NotFoundException("No item is found.");
        }

        $this->delete($id);
        self::$itemsCollection = self::$itemsCollection->push($entity);

        return $this->getById($id);
    }

    /**
     * @inheritdoc
     */
    public function store($entity) : Car
    {
        try {
            return $this->update($entity);
        } catch (NotFoundException $e) {
            return $this->addItem($entity);
        }
    }

    /**
     * @inheritdoc
     */
    public function delete(int $id) : Collection
    {
        self::$itemsCollection = self::$itemsCollection->filter(function ($entity) use ($id) {
            return $entity->getId() !== $id;
        });

        return $this->getAll();
    }

    /**
     * Calculates a new id.
     *
     * @return int
     */
    public static function getNextIndex() : int
    {
        $i = 0;

        self::$itemsCollection->each(function ($entity) use (&$i) {
            if ($entity->getId() > $i) {
                $i = $entity->getId();
            }
        });

        return $i + 1;
    }
}