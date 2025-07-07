<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Esquadra extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'provincia',
        'municipio',
        'bairro',
        'rua',
        'telefone',
        'email',
        'responsavel',
        'observacoes',
        'ativa',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function ocorrencias()
    {
        return $this->hasMany(Ocorrencia::class);
    }
}
