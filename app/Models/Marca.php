<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Modelo;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'imagem'];


    public function rules(){
        return [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpeg,jpg'
       ];
    }
    public function feedback(){
        return  [
            'required' => 'O campo :attribute precisa ser obrigatório!,',
            'nome.unique' => 'O nome de marca já existe!',
            'nome.min' => 'O nome deve ter no minimo 3 caracteres',
            'imagem.file' => 'É necessário adicionar um arquivo'
       ];
    }

    public function modelos(){
        return $this->hasMany('App\Models\Modelo');
    }
}
