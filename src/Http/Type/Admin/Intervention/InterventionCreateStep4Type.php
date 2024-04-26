<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type\Admin\Intervention;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\TextareaType;
use Selmak\Proaxive2\Http\Type\Type;

class InterventionCreateStep4Type extends Type
{
    public function createFormBuilder(mixed $data = null, ?array $args = []): Form
    {
        $builder = (new FormBuilder([
            'key'             => 'intervention_step4',
            'method'          => 'POST',
            'html_validation' => false,
        ], $data))
            ->add('observation', TextareaType::class, [
                'label' => 'Observation',
                'placeholder' => "Rédigez une observation",
                'required' => false
            ])
            ->add('note_technician', TextareaType::class, [
                'label' => 'Note technicien',
                'placeholder' => "Note pour le technicien",
                'required' => false
            ])
            ->add('note_customer', TextareaType::class, [
                'label' => 'Note au client',
                'placeholder' => "Note dédiée au client",
                'required' => false
            ])
        ;
        return $builder->getForm();
    }
}