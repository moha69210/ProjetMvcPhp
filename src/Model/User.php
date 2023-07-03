<?php
namespace App\Model;

class User
{
    const userRole = 'users';
    const adminRole = 'admin';

    public string $username;
    public string $role;
}
?>
