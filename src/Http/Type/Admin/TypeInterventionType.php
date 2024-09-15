<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type\Admin;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\CheckboxType;
use Palmtree\Form\Type\TextType;
use Selmak\Proaxive2\Http\Type\Type;

class TypeInterventionType extends Type
{
    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('type_intervention', $data))
            ->add('name', TextType::class, [
                'label' => "Nom du type"
            ])
        ;
        return $builder->getForm();
    }
}