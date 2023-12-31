<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\ClienteRepository;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * 
     */
    public function __construct(Cliente $cliente){
        $this->cliente = $cliente;
    }

    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);


        if($request->has('filtro')){
            $clienteRepository->filtro($request->filtro);
        }

        
        if($request->has('atributos')){
            $clienteRepository->selectAtributos($request->atributos);
        }

        //$marcas = $this->marca->with('modelos')->get();
        return response()->json($clienteRepository->getResultadoPaginado(3), 200);
    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {    
        $request->validate($this->cliente->rules(), $this->cliente->feedback());

        $cliente = $this->cliente->create([
            'nome' => $request->nome,
            'CPF' => $request->CPF,
            'endereço' => $request->endereço,
            'email' => $request->email,
            'telefone' => $request->telefone,
        ]);

        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cliente = $this->cliente->find($id);
        if($cliente === NULL){
            return response()->json(['erro' => 'Recurso não encontrado'], 404)  ;
        };
        return response()->json($cliente, 201);
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cliente = $this->cliente->find($id);

        
        if($cliente === NULL){
            return response()->json(['erro' => 'Recurso não encontrado'], 404)  ;
        }

        if($request->method() === 'PATCH'){

            $regrasDinamicas = array();

            foreach ($cliente->rules() as $input => $regra) {
                if(array_key_exists($input, $request->all())){
                    $regrasDinamicas[$input] = $regra;
                };

            }
            $request->validate($regrasDinamicas, $this->cliente->feedback());
        }else{
            $request->validate($this->cliente->rules(), $this->cliente->feedback());
        }


        $cliente->fill($request->all());

        $cliente->save();
        return response()->json($cliente, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);
        if($cliente === NULL){
            return response()->json(['erro' => 'Recurso não encontrado'], 404)  ;
        }

        $cliente->delete();
        return response()->json(['msg'=> 'elemento deletado'], 200);
    }
}
