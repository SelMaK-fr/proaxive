<?php
declare (strict_types = 1);
namespace Selmak\Proaxive2\Http\Type;

use Palmtree\Form\Constraint\Length;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\EmailType;

class AccountType extends Type
{

    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('account', $data))
            ->add('fullname', 'text', [
                'error_message' => 'Please enter your fullname',
                'required' => false,
                'constraints' => [
                    new Length(['min' => 3])
                ]
            ])
            ->add('pseudo', 'text', [
                'required' => true
            ])
            ->add('mail', EmailType::class, [
                'label' => "Adresse courriel",
                'placeholder' => "Votre adresse courriel valide"
            ])
            ;
        return $builder->getForm();
    }
}