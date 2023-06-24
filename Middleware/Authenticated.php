<?php

namespace App\Middleware;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Authenticated
{
    // Cette classe peut rester vide car elle ne fait qu'agir comme un marqueur.
    // Vous pouvez cependant ajouter du code ici si nécessaire.
}
