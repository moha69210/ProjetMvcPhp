<?php

namespace App\Middleware;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Authenticated
{
    private array $roles;

    public function __construct(string ...$roles)
    {
        $this->roles = $roles;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }
}
