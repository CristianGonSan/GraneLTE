@extends('adminlte::page')

@section('plugins.Select2', true)

@section('content_header')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuarios</a></li>
            <li class="breadcrumb-item active">{{ $userId }}</li>
            <li class="breadcrumb-item active">Edición</li>
        </ol>
    </nav>
@endsection

@section('content')
    <h1 class="h4">Edición de usuario</h1>
    <livewire:Admin.Users.UserEdit :userId="$userId" />
@endsection
