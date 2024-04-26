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
            ->add('way_type', ChoiceType::class, [
                'label' => "Statut",
                'placeholder' => "Sélectionnez un statut",
                'choices' => self::getStatus()
            ])
            ->add('way_steps', ChoiceType::class, [
                'label' => "Etat",
                'placeholder' => "Sélectionnez un état",
                'choices' => [
                    1 => 'Matériel récupéré',
                    2 => 'En atelier',
                    3 => 'Tests finaux',
                    4 => 'En cours de sortie',
                    5 => 'Terminé',
                ]
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
                $status[$s['name']] = $s['name'];
            }
            return $status;
        }
        return $status;
    }
}