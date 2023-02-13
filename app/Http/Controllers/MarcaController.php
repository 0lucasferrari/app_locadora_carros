<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{

    /*
    Técnica de injeção de model
    */
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        Utilização por método estático
        $marcas = Marca::all();
        */
        // Utilização do método via injeção do Model
        $marcas = $this->marca->all();
        return $marcas;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*
        Utilização por método estático
        $marca = Marca::create($request->all());
        */
        $marca = $this->marca->create($request->all());
        return $marca;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    /*
    Utilizando type hinting:
    public function show(Marca $marca)
    */
    //Utilizando injeção de Model
    public function show($id)
    {
        return $this->marca->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    /*
    Utilizando type hinting:
    public function update(Request $request, Marca $marca)
    */
    // Utilizando a injeção de Model
    public function update(Request $request, $id)
    {
        /*
        $request->all() // Dados atualizados;
        $marca->getAttributes() // Dados antigos;
        */
        $marca = $this->marca->find($id);
        $marca->update($request->all());
        return $marca;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Marca  $marca
     * @return \Illuminate\Http\Response
     */
    /*
    Utilizando o type hinting
    public function destroy(Marca $marca)
    */
    // Utilizando a injeção de Model
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        $marca->delete();
        return ['message' => 'A marca foi removida com sucesso.'];
    }
}
