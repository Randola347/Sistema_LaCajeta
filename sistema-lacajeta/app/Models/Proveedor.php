<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores'; // ← nombre correcto de la tabla
    protected $fillable = ['nombre', 'descripcion'];
    public $timestamps = false; // si no estás usando created_at y updated_at
}
