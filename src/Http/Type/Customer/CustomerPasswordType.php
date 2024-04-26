<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type\Customer;

use Palmtree\Form\Constraint\Length;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Selmak\Proaxive2\Http\Type\Type;

class CustomerPasswordType extends Type
{
    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('customer_password', $data))
            ->add('passwd', 'repeated', [
                'repeatable_type' => 'password',
                'constraints'     => [
                    new Length(['min' => 8]),
                ],
            ])
            ;
        return $builder->getForm();
    }
}