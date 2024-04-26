<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type\Admin\Intervention;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\ChoiceType;
use Selmak\Proaxive2\Http\Type\Type;

class InterventionCreateStep3Type extends Type
{
    public function createFormBuilder(mixed $data = null, ?array $args = []): Form
    {

        $builder = (new FormBuilder([
            'key'             => 'intervention_step3',
            'method'          => 'POST',
            'html_validation' => false,
        ], $data))
            ->add('equipments_id', ChoiceType::class, [
                'label' => 'Equipements',
                'class' => 'test',
                'choices' => self::getEquipment((int)$args['customers_id']),
                'expanded' => true,
                'inline' => false,
            ])
        ;
        return $builder->getForm();
    }

    private function getEquipment(int $data): array
    {
        $equipments = [];
        $req = $this->query->from('equipments')->select(null)->select('equipments.id, equipments.name, equipments.type_name, equipments.brand_name, equipments.os_name')->where('customers_id = ?', $data)->fetchAll();
        if($req){
            foreach ($req as $e) {
                $label = $e['name'] . '<span class="d-block fw-400">' . $e['type_name'] . '</span><span>' . $e['brand_name'] . '<span><span class="d-block fw-400">'. $e['os_name'].'</span>';
                $equipments[$e['id']] = $label;
            }
            return $equipments;
        }
        return $equipments;
    }
}