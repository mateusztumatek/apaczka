@extends('layout')
@section('content')
    <div class="title m-b-md">
        APACZKA INTEGRATE
    </div>
    <div class="links mb-4">
        <a  href="{{url('/')}}">Powrót</a>
    </div>
    @if($errors->any())
        <div class="alert alert-danger">{{$errors->first()}}</div>
    @endif
    @if(\Illuminate\Support\Facades\Session::has('message'))
        <div class="alert alert-primary">{!! \Illuminate\Support\Facades\Session::get('message') !!}</div>
    @endif

    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Kod błędu</th>
            <th scope="col">Opis błędu</th>
            <th scope="col">Data</th>
            <th scope="col">Akcje</th>
        </tr>
        </thead>
        <tbody>
        @foreach($my_errors as $err)
        <tr>
            <th scope="row">{{$err->order_id}}</th>
            <td>{{$err->code}}</td>
            <td>{{$err->description}}</td>
            <td>{{$err->created_at->format('Y-m-d')}}</td>
            <td class="d-flex flex-wrap">
                <a class="btn btn-primary mx-1" href="{{url('/orders?order_id='.$err->order_id)}}">Ponów próbę</a>
                <form action="{{url('/errors/'.$err->id)}}" method="post" class="mx-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Usuń</button>
                </form>

            </td>
        </tr>
            @endforeach
        </tbody>
    </table>
@endsection
