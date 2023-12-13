<?php
declare(strict_types=1);
namespace App\Repository;

use App\BaseRepository;

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

    public function allWithCompany()
    {
        return $this->makeQueryDefault()
            ->select(null)
            ->select('users.*, company.name')
            ->leftJoin('company as c ON users.company_id = c.id')
            ;
    }
}