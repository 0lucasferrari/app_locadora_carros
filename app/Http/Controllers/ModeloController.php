<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositories\ModeloRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ModeloController extends Controller
{
    /*
    Técnica de injeção de model
    */
    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*
        Utilização por método estático
        $marcas = Marca::all();
        */
        // Utilização do método via injeção do Model

        $modeloRepository = new ModeloRepository($this->modelo);

        /*
        Utilização por método estático
        $modelos = Modelo::all();
        */
        // Utilização do método via injeção do Model

        $modelos = array();

        if ($request->has('atributos_marca')) {
            $atributos_marca = 'marca:id,' . $request->atributos_marca;

            $modeloRepository->selectAtributosRegistrosSelecionados($atributos_marca);
        } else {
            $modeloRepository->selectAtributosRegistrosSelecionados('marca');
        }

        if ($request->has('filtro')) {
            $modeloRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $atributos = $request->atributos;
            $modeloRepository->selectAtributos($atributos);
        }

        // $marcas = $this->marca->all();
        return $modeloRepository->getResultado();
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

        $request->validate($this->modelo->rules());

        $image = $request->file('imagem');
        $imagem_urn = $image->store('imagens/modelos', 'public');

        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);

        return $modelo;
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if ($modelo === null) {
            return response()->json(['erro' => 'Recurso pesquisado não encontrado'], 404);
        }
        return $modelo;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /*
        $request->all() // Dados atualizados;
        $marca->getAttributes() // Dados antigos;
        */
        $modelo = $this->modelo->find($id);


        if ($modelo === null) {
            return response()->json(['erro' => 'Recurso solicitado não encontrado'], 404);
        }


        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();
            foreach ($modelo->rules() as $input => $regra) {
                if (array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            $request->validate($regrasDinamicas);
        } else {
            $request->validate($modelo->rules());
        }

        // Remove o arquivo antigo caso um novo arquivo tenha sido encaminhado no request.
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);
        }

        $image = $request->file('imagem');
        $imagem_urn = $request->file('imagem') !== null ? $image->store('imagens/modelos', 'public') : $modelo->imagem;

        $modelo->fill($request->all());
        $modelo->imagem = $imagem_urn;
        $modelo->save();

        /*
        $modelo->update([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]); */

        return $modelo;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);

        if ($modelo === null) {
            return response()->json(['erro' => 'Recurso solicitado não encontrado'], 404);
        }

        // Remove o arquivo de imagem associado.
        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();
        return ['message' => 'O modelo foi removido com sucesso.'];
    }
}
