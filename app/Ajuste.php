<?php

namespace sisMR;

use Illuminate\Database\Eloquent\Model;

class Ajuste extends Model
{
    protected $table='ajuste';
    protected $primaryKey='idajuste';
    public $timestamps=false;
    protected $dates=[
            'fecha_hora'
    ];
    protected $fillable = ['idarticulo', 'cantidad', 'estado', 'detalle'];
    protected $guarded =[

    ];
}
