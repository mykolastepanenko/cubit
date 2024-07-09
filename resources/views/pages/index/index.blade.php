@extends('layouts.main-landing')

@php
$componentsPath = 'pages.index.components.';
@endphp

@section('content')

@include("{$componentsPath}.section-1")
@include("{$componentsPath}.section-2")
@include("{$componentsPath}.section-3")
@include("{$componentsPath}.section-4")

@endsection
