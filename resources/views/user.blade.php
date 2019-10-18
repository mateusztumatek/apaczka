@extends('layout')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="links mb-4">
                    <a  href="{{url('/')}}">Powrót</a>
                </div>
                @if(\Illuminate\Support\Facades\Session::has('message'))
                    <div class="alert alert-primary">{!! \Illuminate\Support\Facades\Session::get('message') !!}</div>
                @endif

                <form action="{{($user)? url('/user/'.$user->id) : url('/user')}}" method="post">
                    @csrf
                    @if($user)
                        @method('put')
                        @endif

                    <div class="form-group">
                        <label>Email</label>
                        <input class="form-control" name="email" type="email" value="{{(!$user)? old('email') : $user->email}}" required>
                        @error('email')
                        <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Imię i nazwisko</label>
                        <input class="form-control" name="name" value="{{(!$user) ? old('name') : $user->name}}" required>
                        @error('name')
                        <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Ulica i numer domu</label>
                        <input class="form-control" name="street" value="{{(!$user)?old('street'): $user->street}}" required>
                        @error('street')
                        <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Miasto</label>
                                <input class="form-control" name="city" value="{{(!$user)?old('city'):$user->city}}" required>
                                @error('city')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kod pocztowy</label>
                                <input class="form-control" name="postal" value="{{(!$user)?old('postal'):$user->postal}}" required>
                                @error('postal')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Numer telefonu</label>
                        <input name="phone" class="form-control" value="{{(!$user)?old('phone'):$user->phone}}" required>
                        @error('phone')
                        <div class="alert alert-danger">{{$message}}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-5">
                            <div class="form-group">
                                <label>Otwarte od</label>
                                <input name="opened_from" type="time" class="form-control" value="{{(!$user)?old('opened_from'):\Carbon\Carbon::parse($user->opened_from)->format('H:i')}}" required>
                                @error('opened_from')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-2 d-flex justify-content-center align-items-center">
                            <span style="font-size: 1.5rem; font-weight: bold">-</span>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Otwarte do</label>
                                <input name="opened_to" type="time" class="form-control" value="{{(!$user)?old('opened_to'):\Carbon\Carbon::parse($user->opened_to)->format('H:i')}}" required>
                                @error('opened_to')
                                <div class="alert alert-danger">{{$message}}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nr twojego paczkomatu:</label>
                        <input class="form-control" name="inpost" value="{{(!$user)? old('inpost') : $user->inpost}}" required>
                    </div>
                    <div class="w-100">
                        <button class="btn btn-primary" type="submit">Zapisz</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
