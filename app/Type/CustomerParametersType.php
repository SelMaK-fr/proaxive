<?php

namespace App\Type;

use App\Type;
use Palmtree\Form\Constraint\NotBlank;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\CheckboxType;
use Palmtree\Form\Type\NumberType;

class CustomerParametersType extends Type
{

    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('customer_parameters', $data))
            ->add('is_blacklisted', 'checkbox', [
                'required' => false,
                'label' => ''
            ])
            ->add('enable_portal', 'checkbox', [
                'required' => false,
                'label' => ''
            ])
            ->add('on_sale', NumberType::class, [
                'required' => false,
                'label' => ''
            ])
            ;
        return $builder->getForm();
    }
}