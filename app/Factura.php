<?php

namespace sisMR;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $table='factura';
    protected $primaryKey='idfactura';
    public $timestamps=false;
    protected $fillable = [
    	'idventa', 
    	'fecha_hora',
    	'total_venta',
    	'estado'
    ];
    protected $guarded =[

    ];
}
