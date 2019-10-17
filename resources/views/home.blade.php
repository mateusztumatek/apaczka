@extends('layout')
@section('content')
<div class="title m-b-md">
    APACZKA INTEGRATE
</div>
@if($errors->any())
    <div class="alert alert-primary">{{$errors->first()}}</div>
    @endif
<div class="links">
    <a  href="{{url('/orders')}}">Wyślij zamówienie</a>
    <a  href="{{url('/user')}}">Ustaw swoje dane</a>
    <a  href="{{url('/set_shippment')}}">Ustaw kody wysyłek</a>
    <a  href="{{url('/errors')}}">Błędy</a>

</div>

    @endsection
