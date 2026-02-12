<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;



class GrupoFilter extends ApiFilter
{
    // Parámetros permitidos y operadores para filtrar
    protected $safeParams = [
        'nombre'   => ['eq', 'like'],  // ejemplo: nombre[like]=incisivo          // ejemplo: grupo_id[eq]=1
    ];

    // Mapeo de parámetros a columnas reales de la base de datos
    protected $columnMap = [
        'nombre'   => 'nombre',
    ];

    // Mapeo de operadores a símbolos SQL
    protected $operatoMap = [
        'eq'  => '=',   // igual
        'lt'  => '<',   // menor que
        'lte' => '<=',  // menor o igual
        'gt'  => '>',   // mayor que
        'gte' => '>=',  // mayor o igual
        'neq'=> '!=',   // diferente a
        'like'=> 'like', 
        // búsqueda parcial
    ];
}
