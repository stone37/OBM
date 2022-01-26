<?php

namespace App\Security;

class UniqueNumberGenerator
{
    /**
     * Génère une chaine de nombre aléatoire d'une taille définie.
     *
     * @param $size
     * @return false|string
     */
    public function generate(int $length = 9): string
    {
        $numbers = "0123456789";

        return str_shuffle(substr($numbers,0, $length));
    }
}