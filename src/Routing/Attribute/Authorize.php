<?php

namespace App\Routing\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Authorize
{
    public function __construct(public string $role)
    {
    }
}
