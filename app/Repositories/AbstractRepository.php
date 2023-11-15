<?php

namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;

class AbstractRepository {

    public function __construct(Model $model){
        $this->model = $model;
    }

    public function selectAtributosRegistrosRelacionados($atributos){
        $this->model = $this->model->with($atributos);
        //contruindo query
    }


    public function filtro($filtros){
        $filtros = explode(';', $filtros);

        foreach ($filtros as $key => $condicao) {    # code...
            $c = explode(':',$condicao);
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
        }
        //construindo query
    }

    public function selectAtributos($atributos){
        $this->model = $this->model->selectRaw($atributos);
        //Contruindo query
    }

    public function getResultado(){
        return $this->model->get();
        //Query Pronta
    }

    public function getResultadoPaginado($numeroRegistrosPaginados){
        return $this->model->paginate($numeroRegistrosPaginados);
    }


}

?>