<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleFactura extends Model
{
    use SoftDeletes;

    protected $table = 'detalle_facturas';

    protected $fillable = [
        'factura_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'factura_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
