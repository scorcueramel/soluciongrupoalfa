<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ValidarAccesoFormatosController extends Controller
{
    public function index(){
        return Inertia::render('Formularios/Access');
    }
}
