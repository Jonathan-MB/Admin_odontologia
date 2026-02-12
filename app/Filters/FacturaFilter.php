<?php

namespace App\Filters;

use Illuminate\Http\Request;
use App\Filters\ApiFilter;


class FacturaFilter extends ApiFilter
{
    protected $safeParams = [
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
