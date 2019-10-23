@extends('layout')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            @if($errors->any())
                <div class="alert alert-primary">{{$errors->first()}}</div>
            @endif
            @if($last_id)
                <div class="alert alert-primary">
                    <p class="font-weight-bold">Ostatnie przesłane id zamówienia: #{{$last_id->value}}</p>
                </div>
            @endif
            <form action="{{url('/orders')}}" method="post" id="order_form">
                @CSRF
                <div class="form-group">
                    <label>Wysyłaj zamówienia od ID: <span style="font-size: 0.8rem">(Uwaga zamówienie o tym numerze ID nie zostanie wysłane. Wysyłane będą zamówienia od tego numeru)</span></label>
                    <input type="number" name="start" class="form-control" value="{{($last_id)? $last_id->value : old('start')}}">
                </div>
                <div class="links">
                    <a  style="cursor: pointer" onclick="sendForm()">Wyślij zamówienia (Od id wpisanego w polu powyżej)</a>
                    <a  href="{{url('/user')}}">Ustaw swoje dane</a>
                    <a  href="{{url('/set_shippment')}}">Ustaw kody wysyłek</a>
                    <a  href="{{url('/errors')}}">Błędy</a>

                </div>
            </form>

                <last-orders></last-orders>

        </div>
    </div>
</div>
    @endsection
<script>
    function sendForm() {
        $('#order_form').submit();
    }
</script>
