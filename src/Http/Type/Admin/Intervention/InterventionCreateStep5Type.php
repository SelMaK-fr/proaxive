<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type\Admin\Intervention;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\ChoiceType;
use Selmak\Proaxive2\Http\Type\Type;

class InterventionCreateStep5Type extends Type
{
    public function createFormBuilder(mixed $data = null, ?array $args = []): Form
    {
        $builder = (new FormBuilder([
            'key'             => 'intervention_step5',
            'method'          => 'POST',
            'html_validation' => false,
        ], $data))
            ->add('status_id', ChoiceType::class, [
                'label' => "Statut",
                'placeholder' => "Sélectionnez un statut",
                'choices' => self::getStatus()
            ])
            ->add('a_priority', ChoiceType::class, [
                'label' => 'Priorité',
                'placeholder' => 'Sélectionnez un niveau',
                'choices' => [
                    'LOW' => 'Basse',
                    'AVERAGE' => 'Moyenne',
                    'URGENT' => 'Urgent',
                    'HIGH' => 'Élevée',
                    'ABSOLUTE' => 'Absolue',
                ]
            ])
        ;
        return $builder->getForm();
    }

    private function getStatus(): array
    {
        $status = [];
        $req = $this->query->from('status')->select(null)->select('status.id, status.name')->fetchAll();
        if($req){
            foreach ($req as $s) {
                $status[$s['id']] = $s['name'];
            }
            return $status;
        }
        return $status;
    }
}