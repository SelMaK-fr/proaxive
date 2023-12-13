<?php
declare(strict_types=1);
namespace App\Type;

use App\Type;
use Palmtree\Form\Constraint\Length;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\PasswordType;

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