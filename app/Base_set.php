<?php

namespace sisMR;

use Illuminate\Database\Eloquent\Model;

class Base_set extends Model
{
    protected $table='base_set';

    protected $primaryKey='id';

    public $timestamps=false;


    protected $fillable =[
    	'nombre',
    	'num_documento',
    ];

    protected $guarded =[

    ];
}
