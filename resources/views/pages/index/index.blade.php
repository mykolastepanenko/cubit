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
        <div id="loader-wrp" class="modal-description-wrp">
            <span class="loader"></span>
        </div>
        <div class="real-modal d-none">
            <button class="close-button">
                <span></span>
                <span></span>
            </button>
            <div class="modal-description-wrp">
                <div id="modal-description" class="modal-description">Дякуємо, що звернулись до нас! Найближчим часом з вами зв’яжеться менеджер
                </div>
            </div>
        </div>
    </div>

@endsection
