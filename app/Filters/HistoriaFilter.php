<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;


class HistoriaFilter extends ApiFilter
{
    protected $safeParams = [
        'nombre'          => ['eq', 'like'],
        'clienteId'       => ['eq'],
        'especialistaId'  => ['eq'],
    ];

    protected $columnMap = [
        'clienteId'      => 'cliente_id',
        'especialistaId' => 'especialista_id',
    ];

    protected $operatoMap = [
        'eq'   => '=',
        'lt'   => '<',
        'lte'  => '<=',
        'gt'   => '>',
        'gte'  => '>=',
        'neq'  => '!=',
        'like' => 'like',
    ];
}
