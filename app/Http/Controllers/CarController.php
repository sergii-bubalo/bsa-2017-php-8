<?php

namespace App\Http\Controllers;

use App\Entities\Car;
use App\Repositories\Contracts\CarRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CarController extends Controller
{
    private $carRepository;

    /**
     * CarController constructor.
     * @param CarRepositoryInterface $carRepository
     */
    public function __construct(CarRepositoryInterface $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response|View
     */
    public function index(): View
    {
        $cars = $this->carRepository->getAll();

        return view('cars.list', [
            'cars' => $cars,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response|View
     */
    public function create(): View
    {
        return view('cars.create', []);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Response|View
     */
    public function store(Request $request): View
    {
        $this->validate($request, [
            'model' => 'required|max:255|regex:~[a-zA-Z_ ]~',
            'year' => 'required|integer|between:1000,' . date('Y'),
            'registration_number' => 'required|alpha_num|size:6',
            'color' => 'required|max:255|alpha',
            'price' => 'required|numeric',
        ]);

        $car = new Car($request->all());

        $this->carRepository->store($car);

        return view('cars.list', [
            'cars' => $this->carRepository->getAll(),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response|View
     */
    public function show(int $id): View
    {
        $car = $this->carRepository->getById($id);

        return view('cars.item', [
            'car' => $car,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response|View
     */
    public function edit(int $id): View
    {
        $car = $this->carRepository->getById($id);

        return view('cars.edit', [
            'car' => $car,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return Response|View
     */
    public function update(Request $request, int $id): View
    {
        $this->validate($request, [
            'model' => 'required|max:255|regex:~[a-zA-Z_ ]~',
            'year' => 'required|integer|between:1000,' . date('Y'),
            'registration_number' => 'required|alpha_num|size:6',
            'color' => 'required|max:255|alpha',
            'price' => 'required|numeric',
        ]);

        $car = $this->carRepository->getById($id);

        if ($car) {
            $car->fromArray($request->all());
        }

        $this->carRepository->update($car);

        return view('cars.item', [
            'car' => $car,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->carRepository->delete($id);

        return redirect()->route('cars.index');
    }
}
