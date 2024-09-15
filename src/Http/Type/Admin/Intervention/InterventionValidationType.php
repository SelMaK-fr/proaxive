<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type\Admin\Intervention;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\ChoiceType;
use Palmtree\Form\Type\HiddenType;
use Palmtree\Form\Type\TextareaType;
use Palmtree\Form\Type\TextType;
use Selmak\Proaxive2\Http\Type\Type;

class InterventionValidationType extends Type
{

    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('intervention_validation', $data))
            ->add('name', TextType::class, [
                'label' => "Titre de l'intervention",
                'placeholder' => "Entrez un titre pour cette intervention"
            ])
            ->add('equipment_name', HiddenType::class, [
                'required' => false
            ])
            ->add('sort', ChoiceType::class, [
                'label' => 'Type',
                'placeholder' => 'Choisissez un type',
                'choices' => self::getTypeIntervention()
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
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'placeholder' => "Rédigez une courte description de la panne",
                'required' => false
            ])
            ->add('before_breakdown', TextareaType::class, [
                'label' => 'Avant panne',
                'placeholder' => "Précisez ce qu'il c'est passé avant la panne",
                'required' => false
            ])
            ;
        if(self::getEquipment($data->customers_id)) {
            $builder
                ->add('equipments_id', ChoiceType::class, [
                    'label' => "Equipement",
                    'placeholder' => 'Sélectionnez un équipement',
                    'choices' => self::getEquipment($data->customers_id)
                ]);
        }
        return $builder->getForm();
    }

    private function getEquipment($data): array
    {
        $equipments = [];
        $req = $this->query->from('equipments')->select(null)->select('equipments.id, equipments.name')->where('customers_id = ?', $data)->fetchAll();

        foreach ($req as $e) {
            $equipments[$e['id']] = $e['name'];
        }
        return $equipments;
    }

    private function getTypeIntervention(): array
    {
        $equipments = [];
        $req = $this->query->from('types_interventions as ti')->select(null)->select('ti.id, ti.name')->fetchAll();
        if($req){
            foreach ($req as $e) {
                $label = $e['name'];
                $equipments[$e['name']] = $label;
            }
            return $equipments;
        }
        return $equipments;
    }
}