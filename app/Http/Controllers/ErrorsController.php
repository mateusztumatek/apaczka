<?php

namespace App\Http\Controllers;

use App\Error;
use Illuminate\Http\Request;

class ErrorsController extends Controller
{
    public function index(Request $request){
        $my_errors = Error::all();
        return view('errors', compact('my_errors'));
    }
    public function destroy(Request $request, $id){
        Error::where('id', $id)->delete();
        return back()->with(['message' => 'Element usuniety poprawnie']);
    }
}
