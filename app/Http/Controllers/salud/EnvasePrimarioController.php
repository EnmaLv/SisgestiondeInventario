<?php

namespace App\Http\Controllers\salud;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\salud\EnvasePrimario;

class EnvasePrimarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.salud.maestros.envases_primarios.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(EnvasePrimario $envasePrimario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EnvasePrimario $envasePrimario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EnvasePrimario $envasePrimario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EnvasePrimario $envasePrimario)
    {
        //
    }
}
