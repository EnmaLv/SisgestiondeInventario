<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexarController extends Controller
{
    public function index()
    {
        return view('admin.configuracion.indexar.index');
    }
}
