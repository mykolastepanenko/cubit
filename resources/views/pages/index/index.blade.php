@extends('layouts.main-landing')

@php
    $componentsPath = 'pages.index.components.';
@endphp

@section('content')

    @include("{$componentsPath}.section-1")
    @include("{$componentsPath}.section-2")
    @include("{$componentsPath}.section-3")
    @include("{$componentsPath}.section-4")

    <div class="modal d-none">
        <button class="close-button">
            <span></span>
            <span></span>
        </button>
        <div class="modal-description-wrp">
            <div class="modal-description">Дякуємо, що звернулись до нас! Найближчим часом з вами зв’яжеться менеджер
            </div>
        </div>
    </div>

@endsection
