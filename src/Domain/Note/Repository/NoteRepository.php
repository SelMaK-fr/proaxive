<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Domain\Note\Repository;

use Selmak\Proaxive2\Domain\BaseRepository;

class NoteRepository extends BaseRepository
{
    protected string $model = 'notes';

    public function allWitUsers()
    {
        return $this->makeQueryObject()
            ->select('notes.*, u.id u_id, u.fullname u_fullname, u.avatar u_avatar, u.roles u_roles')
            ->leftJoin('users as u ON u.id = notes.users_id')
            ->fetchAll()
            ;
    }
}
