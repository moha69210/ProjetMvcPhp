<?php

namespace App\Middleware;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Authenticated
{
}
