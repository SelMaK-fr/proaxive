<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type\Admin\Equipment;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\FileType;
use Palmtree\Form\Type\HiddenType;
use Respect\Validation\Validator as V;
use Selmak\Proaxive2\Http\Type\Type;
use Selmak\Proaxive2\Validator\Type\MimeType;

class EquipmentUploadType extends Type
{
    public function createFormBuilder(mixed $data = null, ?array $args = []): Form
    {
        $builder = (new FormBuilder([
            'key'             => 'equipment_upload',
            'method'          => 'POST',
            'html_validation' => false,
        ], $data))
            ->add('file', FileType::class, [
                'required' => true
            ])
            ->add('id', HiddenType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ;
        return $builder->getForm();
    }
}