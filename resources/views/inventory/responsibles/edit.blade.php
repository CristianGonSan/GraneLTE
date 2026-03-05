@extends('adminlte::page')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('responsibles.index') }}">Responsables</a></li>
            <li class="breadcrumb-item active">{{ $responsibleId }}</li>
            <li class="breadcrumb-item active">Edición</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Edición de responsable</h1>
    <livewire:Inventory.Responsibles.ResponsibleEdit :responsibleId="$responsibleId" />
@endsection
