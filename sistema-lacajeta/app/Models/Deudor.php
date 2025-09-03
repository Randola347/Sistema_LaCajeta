<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deudor extends Model
{

    protected $table = 'deudores'; // ← nombre correcto de la tabla
    protected $fillable = ['nombre', 'descripcion'];
    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }
}
