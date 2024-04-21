<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type;

use Palmtree\Form\Constraint\Length;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;

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