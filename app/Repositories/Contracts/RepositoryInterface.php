<?php

namespace App\Repositories\Contracts;

use App\Repositories\Exceptions\NotFoundException;
use Illuminate\Database\Eloquent\Collection;


/**
 * Interface RepositoryInterface
 * @package App\Repositories\Contracts
 */
interface RepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAll() : Collection;

    /**
     * Returns an item by id.
     *
     * @param $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * Adds an entity.
     *
     * @param mixed $entity A new item data.
     * @return Collection An updated items array.
     */
    public function addItem($entity);

    /**
     * Updates an entity in collection.
     *
     * @param mixed $entity Edited item.
     * @return Collection An updated collection
     * @throws NotFoundException
     */
    public function update($entity);

    /**
     * Updates an entity in the collection if exists. Adds if no.
     *
     * @param mixed $entity
     * @return Collection
     */
    public function store($entity);

    /**
     * Removes an item by id.
     *
     * @param int $id
     * @return Collection An updated collection
     */
    public function delete(int $id) : Collection;
}