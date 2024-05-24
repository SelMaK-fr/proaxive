<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type\Admin\Intervention;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\ChoiceType;
use Selmak\Proaxive2\Http\Type\Type;

class InterventionDiagType extends Type
{
    public function createFormBuilder(mixed $data = null): Form
    {
        $builder = (new FormBuilder('intervention_diag', $data))
            ->add('diag_cpu', ChoiceType::class, [
                'label' => 'CPU',
                'expanded' => true,
                'choices' => [
                    'OK' => 'OK',
                    'Chauffe' => 'Chauffe',
                    'HS' => 'HS'
                ]
            ])
            ->add('diag_gpu', ChoiceType::class, [
                'label' => 'GPU',
                'expanded' => true,
                'choices' => [
                    'OK' => 'OK',
                    'Chauffe' => 'Chauffe',
                    'HS' => 'HS'
                ]
            ])
            ->add('diag_memory', ChoiceType::class, [
                'label' => 'Mémoire (RAM)',
                'expanded' => true,
                'choices' => [
                    'OK' => 'OK',
                    'Erreur' => 'Erreur',
                    'HS' => 'HS'
                ]
            ])
            ->add('diag_storage', ChoiceType::class, [
                'label' => 'Stockage',
                'expanded' => true,
                'choices' => [
                    'OK' => 'OK',
                    'Erreur' => 'Erreur',
                    'HS' => 'HS'
                ]
            ])
            ;
        return $builder->getForm();
    }
}