<?php
declare(strict_types=1);
namespace App\Type;

use App\Type;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\HiddenType;

class EquipmentFastType extends Type
{

    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('equipment', $data))
            ->add('types_equipments_id', 'choice', [
                'placeholder' => 'Sélectionnez un type',
                'label' => 'Types',
                'choices' => $this->getType()
            ])
            ->add('brands_id', 'choice', [
                'placeholder' => 'Sélectionnez une marque',
                'label' => 'Marques',
                'choices' => $this->getBrand()
            ])
            ->add('name', 'text', [
                'required' => true,
                'label' => 'Dénomination',
                'placeholder' => "Nom de l'équipement"
            ])
            ->add('e_serial', 'text', [
                'label' => 'Numéro de série',
                'placeholder' => "Numéro de série",
                'error_message' => 'Veuillez renseigner un numéro de série.',
                'required' => false
            ])
            ;
        return $builder->getForm();
    }

    /**
     * Return types equipments list
     * @return array
     * @throws \Envms\FluentPDO\Exception
     */
    private function getType(): array
    {
        $types = [];
        $req = $this->query->from('types_equipments')->select(null)->select('types_equipments.id, types_equipments.name')->where('is_peripheral IS NULL')->fetchAll();
        foreach ($req as $t) {
            $types[$t['id']] = $t['name'];
        }
        return $types;
    }

    /**
     * Return brands list
     * @return array
     * @throws \Envms\FluentPDO\Exception
     */
    private function getBrand(): array
    {
        $brands = [];
        $req = $this->query->from('brands')->select(null)->select('brands.id, brands.name')->fetchAll();
        foreach ($req as $b) {
            $brands[$b['id']] = $b['name'];
        }
        return $brands;
    }
}