<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type\Admin\Workshop;

use Palmtree\Form\Form;
use Palmtree\Form\Constraint\File as FileConstraint;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\FileType;
use Selmak\Proaxive2\Http\Type\Type;

class WorkshopUploadLogoType extends Type
{
    public function createFormBuilder(mixed $data = null): Form
    {
        $builder = (new FormBuilder([
            'key' => 'workshop_upload',
            'ajax' => false,
            'html_validation' => true
        ], $data))
            ->add('logo', FileType::class, [
                'label' => 'Choisissez un fichier',
                'constraints' => [
                    new FileConstraint\Extension([
                        'extensions' => ['jpg', 'gif', 'png'],
                    ]),
                ],
                'required' => false
            ])
            ->add('updated_at', 'hidden', [
                'mapped' => false,
                'required' => false
            ])
            ;
        return $builder->getForm();
    }
}