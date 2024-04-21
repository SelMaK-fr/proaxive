<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\User\Repository;

use Selmak\Proaxive2\Domain\BaseRepository;

class UserRepository extends BaseRepository
{

    protected string $model = 'users';
    /**
     * Fetch permet de retourner un seul élèment.
     * Il est préférable de stocker le premier résultat dans une variable.
     * Exemple de récupération d'un utilisateur via une adresse mail passée en POST
     * $query = $this->makeQuery()->where('mail = ?', $login);
     * $result = $query->fetch();
     */
    public function findUserByMail($value)
    {
        $query = $this->makeQueryDefault()->where('mail = ?', $value);
        return $query->fetch();
    }

    public function apiLoginUser(string $mail, string $password)
    {
        $query = $this->makeQueryDefault()->where('mail = ?', $mail);
        $result = $query->fetch();
        if(!$result){
            throw new \Exception('Login failed: email or password incorrect.', 400);
        }
        if(!password_verify($password, $result['password'])){
            throw new \Exception('Login failed: email or password incorrect.', 400);
        }
        return $result;
    }

    public function checkUserIfNotActivated($value)
    {
        $query = $this->makeQueryDefault()->where('mail = ? AND confirm_at IS NOT NULL', $value);
        return $query->fetch();
    }

    public function findUserByToken($token, $code)
    {
        $query = $this->makeQueryDefault()->where('token = ? AND confirm_at = ?', [$token, $code]);
        return $query->fetch();
    }

    public function allWithCompany()
    {
        return $this->makeQueryDefault()
            ->select(null)
            ->select('users.*, company.name')
            ->leftJoin('company as c ON users.company_id = c.id')
            ;
    }
}