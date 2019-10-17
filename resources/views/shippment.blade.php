@extends('layout')
@section('content')
    <div class="title m-b-md">
        APACZKA INTEGRATE
    </div>
    <div class="links mb-4">
        <a  href="{{url('/')}}">Powrót</a>
    </div>
    @if(\Illuminate\Support\Facades\Session::has('message'))
        <div class="alert alert-primary">{{\Illuminate\Support\Facades\Session::get('message')}}</div>
        @endif

    <form method="post" action="{{url('/save_shippments')}}">
        @csrf
        @foreach($shippments as $shippment)
            <div class="form-group shippment">
                <label style="font-size: 1.2rem; font-weight: bold">{{$shippment->name}}</label>
                <select name="shippment[{{$shippment->id}}][code]" class="form-control">
                    <option value="">Brak możliwości</option>
                    @foreach($apaczka_shippments as $key => $ap)
                    <option value="{{$key}}" @if($shippment->shippment_maps && $shippment->shippment_maps->apaczka_codename == $key) selected @endif>{{$ap}}</option>
                        @endforeach
                </select>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" @if($shippment->shippment_maps && $shippment->shippment_maps->is_domestic) checked @endif name="shippment[{{$shippment->id}}][isDomestic]" type="checkbox">
                    <label class="form-check-label" for="inlineCheckbox1">Czy jest zagraniczna</label>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Zamówienie odbioru</label>
                        <select name="shippment[{{$shippment->id}}][pickup_type]" class="form-control">
                            @foreach($pickup_types as $key => $pickup)
                                <option value="{{$key}}" @if($shippment->shippment_maps && $shippment->shippment_maps->pickup == $key) selected @endif>{{$pickup}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" @if($shippment->shippment_maps && $shippment->shippment_maps->is_paczkomat) checked @endif name="shippment[{{$shippment->id}}][is_paczkomat]" type="checkbox">
                    <label class="form-check-label" for="inlineCheckbox1">Czy ustawić paczkomat</label>
                </div>
            </div>
        @endforeach
            <div class="w-100">
                <button class="btn btn-primary" type="submit">Zapisz</button>
            </div>
    </form>
@endsection
