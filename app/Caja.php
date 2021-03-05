<?php

namespace sisMR;

use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    
    protected $table='caja';

    protected $primaryKey='idcaja';

    public $timestamps=false;
    protected $dates=[
            'fecha_hora_apertura',
            'fecha_hora_cierre'
    ];
    protected $fillable =[
    	'usuario',
    	'monto_apertura_caja',
    	'monto_cierre_caja'
    ];

    protected $guarded =[

    ];
}
