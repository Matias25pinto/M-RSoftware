<?php

namespace sisMR;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
     protected $table='pago_salario';

    protected $primaryKey='idpago';

   public $timestamps=false;
    protected $dates=[
            'fecha_inicio',
            'fecha_final',
            'fecha_pago'
    ];
    protected $fillable =[
    	'idusers',
    	'comosion',
    	'monto_pagado'
    ];

    protected $guarded =[

    ];
}
