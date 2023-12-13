<?php
declare(strict_types=1);
namespace App\Security;

use App\Repository\UserRepository;
use Envms\FluentPDO\Query;
use Odan\Session\SessionInterface;

class UserAuthenticator
{
    public function __construct(private readonly SessionInterface $session, private readonly Query $query){}

    public function user()
    {
        if(!$this->session->has('user-auth')) return false;

        $q = (new UserRepository($this->query));
        $q->where('id = ?', );

        return $q->fetch();
    }

    public function check(): bool
    {
        return (bool) self::user() === false;
    }
}