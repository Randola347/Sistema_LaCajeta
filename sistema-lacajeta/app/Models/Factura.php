<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
     protected $table = 'facturas';
    public function deudor()
    {
        return $this->belongsTo(Deudor::class);
    }
}
