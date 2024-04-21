<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type;

use Palmtree\Form\Constraint\Length;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;

class CustomerPortalType extends Type
{

    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('customer', $data))
            ->add('mail', 'email', [
                'required' => false,
                'label' => 'Adresse courriel'
            ])
            ->add('fullname', 'text', [
                'required' => true,
                'error_message' => 'Please enter valid fullname',
                'label' => 'Nom complet',
                'constraints' => [
                    new Length(['min' => 3])
                ]
            ])
            ->add('phone', 'tel', [
                'placeholder' => 'Numéro de téléphone',
                'label' => 'Téléphone fixe',
                'required' => false
            ])
            ->add('mobile', 'tel', [
                'placeholder' => 'Numéro de portable',
                'label' => 'Téléphone portable',
                'required' => false
            ])
            ->add('favorite_contact', 'choice', [
                'placeholder' => 'Selectionnez une méthode',
                'label' => 'Contact préféré',
                'choices'     => [
                    'SMS' => 'SMS',
                    'Appel' => 'Appel',
                    'Courriel' => 'Courriel'
                ],
                'required' => false
            ])
            ;
        return $builder->getForm();
    }
}