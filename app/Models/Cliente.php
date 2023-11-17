<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $fillable = ['nome','CPF', 'endereço', 'telefone', 'email'];

    public function rules(){
        return [
            'nome' => 'required|min:3',
            'CPF' => 'unique:clientes,CPF,'.$this->id.'|min:11|max:11',
            'email' => 'unique:clientes,email',
            'endereço' => 'required',
            'email' => 'required',
            
       ];
    }
    public function feedback(){
        return  [
            'required' => 'O campo :attribute precisa ser obrigatório!,',
            'nome.min' => 'O nome deve ter no minimo 3 caracteres',
            'CPF.unique' => 'O CPF já existe!',
            'CPF.min' => 'O CPF Precisa ter 11 dígitos!',
            'CPF.max' => 'O CPF Precisa ter 11 dígitos!',
            'email.unique' => 'O email já existe!'
       ];
    }

}
