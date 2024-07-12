<?php
declare (strict_types = 1);
namespace Selmak\Proaxive2\Factory;

class CookieFactory
{

    public function setCookie($data, $db): void
    {
        $expected = sha1('proaxive_app');
        $db->update('users' ,['auth_token' => $expected], (int)$data['id'])->execute();
        setcookie("proaxive2-auth", $expected, strtotime("+3 days"), '/', '', true, true);
    }

    public function get(string $key): mixed
    {
        if(isset($_COOKIE[$key])){
            return $_COOKIE[$key];
        }
    }

    public function has(string $key): bool
    {
        if($_COOKIE[$key]){
            return true;
        }
        return false;
    }
}