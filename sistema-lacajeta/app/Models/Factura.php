<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factura extends Model
{
    use SoftDeletes;

    protected $table = 'facturas';

    protected $fillable = [
        'deudor_id',
        'fecha',
        'estado',
        'forma_pago',
        'total',
    ];


    // Relación con deudor
    public function deudor()
    {
        return $this->belongsTo(Deudor::class, 'deudor_id');
    }

    // Relación con los detalles de factura
    public function detalles()
    {
        return $this->hasMany(DetalleFactura::class, 'factura_id');
    }

    /* ============================
       Helpers
    ============================ */

    // Calcula el total en tiempo real (aunque ya esté guardado en BD)
    public function getTotalCalculadoAttribute()
    {
        return $this->detalles->sum(function ($d) {
            return $d->subtotal;
        });
    }

    // Para que siempre puedas usar $factura->total con valor consistente
    public function getTotalAttribute($value)
    {
        // Si la BD tiene guardado el total, lo devolvemos
        // Si está en null (por algún error), calculamos
        return $value ?? $this->getTotalCalculadoAttribute();
    }
}
