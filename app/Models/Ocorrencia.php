<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ocorrencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'esquadra_id',
        'tipo',
        'data_hora',
        'provincia',
        'municipio',
        'bairro',
        'rua',
        'vitimas',
        'descricao',
        'anexos',
        'estado',
    ];

    protected $casts = [
        'anexos' => 'array',
        'data_hora' => 'datetime',
    ];

    public function esquadra()
    {
        return $this->belongsTo(Esquadra::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
