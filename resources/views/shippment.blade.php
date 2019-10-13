@extends('layout')
@section('content')
    <div class="title m-b-md">
        APACZKA INTEGRATE
    </div>
    <div class="links mb-4">
        <a  href="{{url('/')}}">Powrót</a>
    </div>
    <form method="post" action="{{url('/save_shippments')}}">
        @csrf
        @foreach($shippments as $shippment)
            <div class="form-group">
                <label>{{$shippment->name}}</label>
                <select name="shippment[{{$shippment->id}}]" class="form-control">
                    <option value="">Brak możliwości</option>
                    @foreach($apaczka_shippments as $key => $ap)
                    <option value="{{$key}}" @if($shippment->shippment_maps && $shippment->shippment_maps->apaczka_codename == $key) selected @endif>{{$ap}}</option>
                        @endforeach
                </select>
            </div>
        @endforeach
            <div class="col-md-12">
                <button class="btn btn-primary" type="submit">Zapisz</button>
            </div>
    </form>

@endsection