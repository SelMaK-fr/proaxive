<?php

namespace Selmak\Proaxive2\Http\Type\Customer;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\NumberType;
use Selmak\Proaxive2\Http\Type\Type;

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