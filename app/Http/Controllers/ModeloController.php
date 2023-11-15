<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Modelo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ModeloRepository;

class ModeloController extends Controller
{

    public function __construct(Modelo $modelo){
        $this->modelo = $modelo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
      
        $modeloRepository = new ModeloRepository($this->modelo);

        if($request->has('atributos_marca')){
            $atributos_marca = 'marca:id,'. $request->atributos_marca;
            $modeloRepository->selectAtributosRegistrosRelacionados($atributos_marca);

        }else{
            $modeloRepository->selectAtributosRegistrosRelacionados('marca');
        };

        if($request->has('filtro')){
            $modeloRepository->filtro($request->filtro);
        }

        
        if($request->has('atributos')){
            $modeloRepository->selectAtributos($request->atributos);
        }

        //$marcas = $this->marca->with('modelos')->get();
        return response()->json($modeloRepository->getResultado(), 200);
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
        $request->validate($this->modelo->rules());
       
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagem/modelos', 'public');

        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,
        ]);

        return response()->json($modelo, 201);
       //$this->marca->create($request->all());
       //Marca::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if($modelo === NULL){
            return response()->json(['erro' => 'Recurso não encontrado'], 404)  ;
        };
        return response()->json($modelo, 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modelo $modelo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $modelo = $this->modelo->find($id);
      
        if($modelo === NULL){
            return response()->json(['erro' => 'Recurso não encontrado'], 404)  ;
        }

        if($request->method() === 'PATCH'){

            $regrasDinamicas = array();

            foreach ($modelo->rules() as $input => $regra) {
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                };

            }

            $request->validate($regrasDinamicas);

        }else{
            $request->validate($modelo->rules());
        }

        if($request->file('imagem')){
            Storage::disk('public')->delete($modelo->imagem);
        }
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagem/modelos', 'public');

        //remove o arquivo antigo caso ele exista

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
        ]);
        */

        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if($modelo === NULL){
            return response()->json(['erro' => 'Recurso não encontrado'], 404)  ;
        }

        Storage::disk('public')->delete($modelo->imagem);


        $modelo->delete();
        return response()->json(['msg'=> 'elemento deletado'], 200);
    }
}
