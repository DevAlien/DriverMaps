<?php
namespace Aliegon\Tests\Auth;

use \Aliegon\Auth\UserInterface;

class User implements UserInterface
{
    private $permissions = array();

    public function __construct($permissions = array())
    {
        $this->permissions = $permissions;;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }
}