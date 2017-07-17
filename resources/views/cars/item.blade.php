@extends('cars.layouts.main')

@section('title', $car->getModel())

@section('content')
    <ul>
        <li>
            <a href="{{ route('cars.show', ['id' => $car->getId()]) }}">{{ $car->getModel() }}</a>
        </li>
        <ul>
            <li>
                Year - {{ $car->getYear() }}
            </li>
            <li>
                Registration Numer - {{ $car->getRegistrationNumber() }}
            </li>
            <li>
                Color - {{ $car->getColor() }}
            </li>
            <li>
                Price - {{ $car->getPrice() }}k $
            </li>
        </ul>
    </ul>
    <a href="{{ route('cars.edit', ['id' => $car->getId()]) }}" class="btn btn-primary edit-button">Edit</a>
    <a href="{{ route('cars.index') }}" class="btn btn-danger delete-button">Delete</a>

    {{--{{ Form::open(['route' => ['cars.destroy', $car->getId()], 'method' => 'delete', 'class' => 'form-inline']) }}--}}
        {{--{{ Form::submit('Delete', ['class' => 'btn btn-danger delete-button']) }}--}}
    {{--{{ Form::close() }}--}}

@endsection