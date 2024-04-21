<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\TextareaType;
use Palmtree\Form\Type\TextType;

class TaskType extends Type
{
    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('setting_task', $data))
            ->add('name', TextType::class, [
                'label' => "Dénomination",
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description",
                'required' => false
            ])
            ;
        return $builder->getForm();
    }
}