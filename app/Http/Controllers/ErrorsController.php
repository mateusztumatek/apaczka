<?php

namespace App\Http\Controllers;

use App\Error;
use Illuminate\Http\Request;

class ErrorsController extends Controller
{
    public function index(Request $request){
        $my_errors = Error::orderBy('created_at', 'desc')->paginate(10);
        return view('errors', compact('my_errors'));
    }
    public function destroy(Request $request, $id){
        Error::where('id', $id)->delete();
        return back()->with(['message' => 'Element usuniety poprawnie']);
    }
    public function massiveDestroy(Request $request){
        if($request->my_errors){
            foreach ($request->my_errors as $error => $data){
                Error::where('id', $error)->delete();
            }
        }else{
            return back()->with(['message' => 'Nie zaznaczono zadnych elementow']);
        }

        return back()->with(['message' => 'Elementy usuniete poprawnie']);
    }
}
