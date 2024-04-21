<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\TextType;

class EquipmentUpdateType extends Type
{

    public function createFormBuilder(mixed $data = null): Form
    {
        $builder = (new FormBuilder([
            'key'             => 'equipment_update',
            'method'          => 'POST',
            'html_validation' => false,
        ], $data))
            ->add('equipment_specs', TextType::class, [])
            ;
        return $builder->getForm();
    }
}