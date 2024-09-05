<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type\Admin;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\ChoiceType;
use Selmak\Proaxive2\Http\Type\Type;

class TaskListType extends Type
{

    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('tasks', $data))
            ->add('tasks', ChoiceType::class, [
                'label' => "Tâches",
                'required' => false,
                'multiple' => true,
                'choices' => self::getTask()
            ])
            ;

        return $builder->getForm();
    }

    private function getTask(): array
    {
        $tasks = [];
        $req = $this->query->from('tasks')->select(null)->select('tasks.id, tasks.name')->fetchAll();
        foreach ($req as $t) {
            $tasks[$t['id']] = $t['name'];
        }
        return $tasks;
    }
}