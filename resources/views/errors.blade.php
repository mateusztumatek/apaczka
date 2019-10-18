@extends('layout')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="links mb-4">
                    <a  href="{{url('/')}}">Powrót</a>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger">{{$errors->first()}}</div>
                @endif
                @if(\Illuminate\Support\Facades\Session::has('message'))
                    <div class="alert alert-primary">{!! \Illuminate\Support\Facades\Session::get('message') !!}</div>
                @endif
                <form action="{{url('/errors_massive_delete')}}" method="post">
                    @CSRF
                    @METHOD('DELETE')
                <button type="button" onclick="$(this).parent().submit()" class="btn btn-danger mb-2">Usuń zaznaczone</button>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col"></th>
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
                        <th><input type="checkbox" name="my_errors[{{$err->id}}]"></th>
                        <th scope="row">{{$err->order_id}}</th>
                        <td>{{$err->code}}</td>
                        <td>{{$err->description}}</td>
                        <td>{{$err->created_at->format('Y-m-d')}}</td>
                        <td class="d-flex flex-wrap">
                            <a class="btn btn-primary mx-1" href="{{url('/orders?order_id='.$err->order_id)}}">Ponów próbę</a>
                            <form action="{{url('/errors/'.$err->id)}}" method="post" class="mx-1">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="$(this).parent().submit()" class="btn btn-danger">Usuń</button>
                            </form>

                        </td>
                    </tr>
                        @endforeach
                    </tbody>
                    {{$my_errors->links()}}
                </table>
                </form>
            </div>
        </div>
    </div>
@endsection

<style>

</style>
