<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'inventario',
        'proveedor_id'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

        public $timestamps = false; // si no estás usando created_at y updated_at
}
