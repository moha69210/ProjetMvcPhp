<?php

namespace App\Middleware;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Authenticated
{
    private string $role;

    public function __construct(string $role = 'user')
    {
        $this->role = $role;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}
