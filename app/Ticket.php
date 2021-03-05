<?php

namespace sisMR;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table='ticket';
    protected $primaryKey='idticket';
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
