<?php

namespace App\Http\Controllers;

use App\Models\locacao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorelocacaoRequest;
use App\Http\Requests\UpdatelocacaoRequest;
use App\Repositories\LocacaoRepository;

class LocacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct(Locacao $locacao){
        $this->locacao = $locacao;
    }
    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        if($request->has('filtro')){
            $locacaoRepository->filtro($request->filtro);
        }

        
        if($request->has('atributos')){
            $locacaoRepository->selectAtributos($request->atributos);
        }

        //$marcas = $this->marca->with('modelos')->get();
        return response()->json($locacaoRepository->getResultado(), 200);
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
        
        $request->validate($this->locacao->rules());
     

        $locacao = $this->locacao->create([
            'cliente_id' => $request->cliente_id,
            'carro_id' => $request->carro_id,
            'data_inicio_periodo' => $request->data_inicio_periodo,
            'data_final_previsto_periodo' => $request->data_final_previsto_periodo,
            'data_final_realizado_periodo' => $request->data_final_realizado_periodo,
            'valor_diaria' => $request->valor_diaria,
            'km_inicial' => $request->km_inicial,
            'km_final' => $request->km_final,
        ]);

        return response()->json($locacao, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $locacao = $this->locacao->find($id);
        if($locacao === NULL){
            return response()->json(['erro' => 'Recurso não encontrado'], 404)  ;
        };
        return response()->json($locacao, 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(locacao $locacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $locacao = $this->locacao->find($id);

        
        if($locacao === NULL){
            return response()->json(['erro' => 'Recurso não encontrado'], 404)  ;
        }

        if($request->method() === 'PATCH'){

            $regrasDinamicas = array();

            foreach ($locacao->rules() as $input => $regra) {
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                };

            }

            $request->validate($regrasDinamicas);

        }else{
            $request->validate($locacao->rules());
        }



        $locacao->fill($request->all());
        $locacao->save();
        /*
        $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);

        */
        return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);
        if($locacao === NULL){
            return response()->json(['erro' => 'Recurso não encontrado'], 404)  ;
        }

        $locacao->delete();
        return response()->json(['msg'=> 'elemento deletado'], 200);
    }
}
