<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;



class EspecialistaFilter extends ApiFilter
{
    // Parámetros permitidos y operadores para filtrar
    protected $safeParams = [
        'nombre'   => ['eq', 'like'],       
        'sedeID'   => ['eq', 'neq'],       
        
    ];

    // Mapeo de parámetros a columnas reales de la base de datos
    protected $columnMap = [
        'nombre'   => 'nombre',
        'sedeID'   => 'sede_id',
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
