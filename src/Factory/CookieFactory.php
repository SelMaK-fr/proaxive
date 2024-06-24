<?php
declare (strict_types = 1);
namespace Selmak\Proaxive2\Factory;

class CookieFactory
{

    public function setCookie($data, $db): void
    {
        $expected = $data['id'] . '==' . sha1($data['id'] . 'proaxive');
        $db->update('users' ,['auth_token' => $expected], (int)$data['id'])->execute();
        setcookie("proaxive2-auth", $expected, strtotime("+7 days"), '/', '', true, true);
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