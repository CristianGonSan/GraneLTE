@extends('adminlte::page')

@section('title_prefix', 'Categoria |')

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categorias</a></li>
            <li class="breadcrumb-item active">#{{ $categoryId }}</li>
            <li class="breadcrumb-item active">Detalles</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Detalles de categoría</h1>
    <livewire:Inventory.Categories.CategoryShow :categoryId="$categoryId" />

    <hr class="mt-1">

    <h2 class="h5">Materias primas en esta categoría</h2>
    <livewire:Inventory.Categories.RawMaterialsTable :categoryId="$categoryId" />
@endsection
