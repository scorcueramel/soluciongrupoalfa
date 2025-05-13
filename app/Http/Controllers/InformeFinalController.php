<?php

namespace App\Http\Controllers;

use App\Http\Requests\InformeFinalStoreRequest;
use Illuminate\Http\Request;

class InformeFinalController extends Controller
{
    public function store(InformeFinalStoreRequest $request){
      dd($request->data);
    }
}
