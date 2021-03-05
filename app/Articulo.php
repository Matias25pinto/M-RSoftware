<?php
namespace sisMR;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table='articulo';

    protected $primaryKey='idarticulo';

    public $timestamps=false;


    protected $fillable =[
    	'idcategoria',
    	'codigo',
    	'nombre',
    	'stock',
    	'descripcion',
    	'imagen',
    	'estado',
        'precio_compra',
        'precio_venta',
        'impuesto'
    ];

    protected $guarded =[

    ];
}
