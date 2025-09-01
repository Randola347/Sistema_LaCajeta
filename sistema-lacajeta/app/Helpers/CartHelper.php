<?php

namespace App\Helpers;

class CartHelper
{
    /**
     * Calcular el total del carrito
     */
    public static function total(array $cart): float
    {
        $t = 0;
        foreach ($cart['items'] ?? [] as $l) {
            $t += $l['precio'] * $l['cantidad'];
        }
        return $t;
    }
}
