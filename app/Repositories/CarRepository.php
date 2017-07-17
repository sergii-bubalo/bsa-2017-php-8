<?php

namespace App\Repositories;

use App\Entities\Car;
use App\Repositories\Contracts\AbstractRepository;
use App\Repositories\Contracts\CarRepositoryInterface;

/**
 * Class CarRepository
 * @package App\Repositories
 */
class CarRepository extends AbstractRepository implements CarRepositoryInterface
{
    /**
     * @var array
     */
    protected static $itemsData = [
        [
            'id' => 1,
            'model' => 'Mercedes C-Classe',
            'color' => 'White',
            'registration_number' => 'MB1234',
            'year' => '2012',
            'price' => 50,
        ], [
            'id' => 2,
            'model' => 'Hyundai Elantra',
            'color' => 'Silver',
            'registration_number' => 'HE3214',
            'year' => '2015',
            'price' => 30,
        ], [
            'id' => 3,
            'model' => 'Skoda Octavia',
            'color' => 'Blue',
            'registration_number' => 'SO1342',
            'year' => '2013',
            'price' => 35,
        ], [
            'id' => 4,
            'model' => 'BMW Series 7',
            'color' => 'Black',
            'registration_number' => 'BMW789',
            'year' => '2010',
            'price' => 60,
        ]
    ];

    /**
     * Creates entity
     * @param array $data
     * @return Car
     */
    protected function createEntity(array $data): Car
    {
        return new Car($data);
    }

}