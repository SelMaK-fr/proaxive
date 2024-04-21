<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Domain\Task\Repository;

use Selmak\Proaxive2\Domain\BaseRepository;

class TaskAssocRepository extends BaseRepository
{
    protected string $model = 'tasks_assoc';

    /**
     * @param int $id
     * @return array|bool
     * @throws \Envms\FluentPDO\Exception
     */
    public function findByIntervention(int $id)
    {
        return $this->makeQueryDefault()
            ->select('tasks_assoc.*, tasks.*, interventions.id as i_id')
            ->leftJoin('tasks ON tasks_assoc.tasks_id = tasks.id')
            ->leftJoin('interventions ON tasks_assoc.interventions_id = interventions.id')
            ->where('tasks_assoc.interventions_id = ?', [$id])
            ->fetchAll();
    }

    /**
     * @param int $id
     * @return array|bool
     * @throws \Envms\FluentPDO\Exception
     */
    public function findForSelect2(int $id)
    {
        return $this->makeQueryDefault()
            ->select('tasks_assoc.tasks_id, tasks_assoc.interventions_id')
            ->where('tasks_assoc.interventions_id = ?', [$id])
            ->fetchAll();
    }

    /**
     * @param int $intervention
     * @param int $task
     * @return false|mixed
     * @throws \Envms\FluentPDO\Exception
     */
    public function search(int $intervention, int $task){
        return $this->makeQueryDefault()->where('interventions_id = ? AND tasks_id = ?', [$intervention, $task])->fetch();
    }
}