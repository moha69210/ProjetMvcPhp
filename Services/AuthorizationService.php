<?php

class AuthorizationService
{
    function hasAccess(object $object, string $methodName, string $userRole): bool
    {
        $reflection = new ReflectionClass($object);
        $method = $reflection->getMethod($methodName);
        $attributes = $method->getAttributes(RequireRole::class);

        foreach ($attributes as $attribute) {
            if ($attribute->newInstance()->hasRole($userRole)) {
                return true;
            }
        }

        return false;
    }
}
