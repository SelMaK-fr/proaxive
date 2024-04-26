<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type\Admin;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\CheckboxType;
use Palmtree\Form\Type\TextType;
use Selmak\Proaxive2\Http\Type\Type;

class TypeEquipmentType extends Type
{
    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('type_equipment', $data))
            ->add('name', TextType::class, [
                'label' => "Nom"
            ])
            ->add('slug', TextType::class, [
                'label' => "Slug",
                'required' => false
            ])
            ->add('is_peripheral', CheckboxType::class, [
                'required' => false,
                'label' => 'Catégorie pour périphérique'
            ])
            ;
        return $builder->getForm();
    }
}