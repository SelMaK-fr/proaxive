<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type;

use Palmtree\Form\Constraint\Length;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;

class AccountPasswordType extends Type
{

    public function createFormBuilder(mixed $data = null): Form
    {
        $builder = (new FormBuilder('account_password', $data))
            ->add('password', 'repeated', [
                'repeatable_type' => 'password',
                'constraints'     => [
                    new Length(['min' => 8]),
                ]
            ])
            ;
        return $builder->getForm();
    }
}