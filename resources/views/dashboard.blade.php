@extends('adminlte::page')

@section('content')
    Hola {{ Auth::user()->name }}!
@endsection
