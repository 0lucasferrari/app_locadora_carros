<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $request->validate($this->marca->rules(), $this->marca->feedback());

        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens', 'public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);

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
        $marca = $this->marca->find($id);
        if ($marca === null) {
            return response()->json(['erro' => 'Recurso pesquisado não encontrado'], 404);
        }
        return $marca;
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

        if ($marca === null) {
            return response()->json(['erro' => 'Recurso solicitado não encontrado'], 404);
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();
            foreach ($marca->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas, $marca->feedback());
        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }

        // Remove o arquivo antigo caso um novo arquivo tenha sido encaminhado no request.
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
        }


        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens', 'public');

        $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);

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

        if ($marca === null) {
            return response()->json(['erro' => 'Recurso solicitado não encontrado'], 404);
        }

        // Remove o arquivo de imagem associado.
        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return ['message' => 'A marca foi removida com sucesso.'];
    }
}
