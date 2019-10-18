<?php

namespace App\Http\Controllers;

use App\Setting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function home(){
        $last_id = Setting::where('key', 'last_id')->first();

        return view('home', compact('last_id'));
    }
    public function index(){
        $user = Auth::user();
        return view('user', compact('user'));
    }
    public function store(Request $request){
        $request->validate($this->validator());
        $request->request->set('password', 'password');
        $request->request->set('api_key', md5(Str::random(20)));
        $user = User::create($request->all());
        return back()->with(['message' => 'Zakończono pomyslnie']);
    }

    public function update(Request $request, $id){
        $request->validate($this->validator($id));
        $user = Auth::user();
        $user->update($request->all());
        return back()->with(['message' => 'Zaktualizowano pomyslnie']);
    }

    protected function validator($id = null){
        return [
            'email' => (!$id)? 'required|email|unique:users' : 'required|email|unique:users,email,'.$id,
            'name' => 'required',
            'street' => 'required',
            'city' => 'required',
            'postal' => 'required',
            'phone' => 'required',
            'opened_from' => (!$id)? 'required|date_format:H:i' : 'required|date_format:H:i',
            'opened_to' => (!$id)? 'required|date_format:H:i|after:opened_from' : 'required|date_format:H:i|after:opened_from',
            'inpost' => 'required'
        ];
    }
}
