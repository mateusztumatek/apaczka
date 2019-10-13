@extends('layout')
@section('content')
<div class="title m-b-md">
    APACZKA INTEGRATE
</div>

<div class="links">
    <a  href="{{url('/orders')}}">Wyślij zamówienie</a>
    <a  href="{{url('/set_shippment')}}">Ustaw kody wysyłek</a>
</div>

    @endsection