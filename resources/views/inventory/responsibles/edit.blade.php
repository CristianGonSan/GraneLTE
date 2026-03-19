@extends('adminlte::page')

@section('title_prefix', 'Editar Responsable |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('responsibles.index') }}">Responsables</a></li>
            <li class="breadcrumb-item">
                <a href="{{ route('responsibles.show', $responsibleId) }}">#{{ $responsibleId }}</a>
            </li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Editar responsable</h1>
    <livewire:Inventory.Responsibles.ResponsibleEdit :responsibleId="$responsibleId" />
@endsection
