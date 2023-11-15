<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Storage;
use App\Models\Marca;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\MarcaRepository;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     */

    public function __construct(Marca $marca){
        $this->marca = $marca;
    }

    public function index(Request $request)
    {

        $marcaRepository = new MarcaRepository($this->marca);

        $marcas = array();

        if($request->has('atributos_modelos')){
            $atributos_modelos = 'modelos:id,'. $request->atributos_modelos;
            $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);

        }else{
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        };

        if($request->has('filtro')){
            $marcaRepository->filtro($request->filtro);
        }

        
        if($request->has('atributos')){
            $marcaRepository->selectAtributos($request->atributos);
        }

        //$marcas = $this->marca->with('modelos')->get();
        return response()->json($marcaRepository->getResultadoPaginado(3), 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return 'NADA';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->marca->rules(),$this->marca->feedback());
       
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagem', 'public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);
       //$this->marca->create($request->all());
       //Marca::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);
        if($marca === NULL){
            return response()->json(['erro' => 'Recurso não encontrado'], 404)  ;
        };
        return response()->json($marca, 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marca $marca)
    {
        return 'NADA';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        
        if($marca === NULL){
            return response()->json(['erro' => 'Recurso não encontrado'], 404)  ;
        }

        if($request->method() === 'PATCH'){

            $regrasDinamicas = array();

            foreach ($marca->rules() as $input => $regra) {
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                };

            }

            $request->validate($regrasDinamicas, $marca->feedback());

        }else{
            $request->validate($marca->rules(), $marca->feedback());
        }


        $marca->fill($request->all());

        if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);

            $imagem = $request->file('imagem');
            $imagem_urn = $imagem->store('imagens', 'public');
            $marca->imagem = $imagem_urn;
        }

        $marca->save();
        return response()->json($marca, 200);
        /*
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagem', 'public');

        //remove o arquivo antigo caso ele exista
        if($request->file('imagem')){
            Storage::disk('public')->delete($marca->imagem);
        }

        $marca->fill($request->all());
        $marca->imagem = $imagem_urn;
        $marca->save();
        /*
        $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);

        */
      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        if($marca === NULL){
            return response()->json(['erro' => 'Recurso não encontrado'], 404)  ;
        }

        Storage::disk('public')->delete($marca->imagem);


        $marca->delete();
        return response()->json(['msg'=> 'elemento deletado'], 200);
    }
}
