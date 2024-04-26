<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type\Admin\Equipment;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\CollectionType;
use Selmak\Proaxive2\Http\Type\Type;

class EquipmentUpdateType extends Type
{

    public function createFormBuilder(mixed $data = null): Form
    {
        $builder = (new FormBuilder([
            'key'             => 'equipment_update',
            'method'          => 'POST',
            'html_validation' => false,
        ], $data))
            ->add('equipment', CollectionType::class, [
                'entry_type' => EquipmentType::class
            ])
            ;
        return $builder->getForm();
    }
}