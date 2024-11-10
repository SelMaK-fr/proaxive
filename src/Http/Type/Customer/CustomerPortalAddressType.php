<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type\Customer;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Selmak\Proaxive2\Http\Type\Type;

class CustomerPortalAddressType extends Type
{

    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('customer', $data))
            ->add('address', 'text', [
                'placeholder' => 'Adresse postale du client',
                'label' => 'Adresse Postale',
                'required' => true
            ])
            ->add('addr_longitude', 'hidden', [
                'required' => false
            ])
            ->add('addr_latitude', 'hidden', [
                'required' => false
            ])
            ->add('city', 'text', [
                'placeholder' => 'Ville',
                'label' => 'Ville',
                'required' => true
            ])
            ->add('zipcode', 'text', [
                'placeholder' => 'Code postal',
                'label' => 'Code postal',
                'required' => true
            ])
            ->add('department', 'text', [
                'placeholder' => 'Département',
                'label' => 'Département',
                'required' => false
            ])
            ->add('type_housing', 'choice', [
                'placeholder' => 'Selectionnez un type',
                'label' => 'Type de logement',
                'choices' => [
                    'Maison' => 'Maison',
                    'Appartement' => 'Appartement',
                ],
                'required' => false
            ])
            ->add('h_floor', 'text', [
                'placeholder' => 'Étage/palier',
                'label' => 'Étage/palier',
                'required' => false
            ])
            ->add('h_digicode', 'text', [
                'placeholder' => 'Digicode',
                'label' => 'Digicode',
                'required' => false
            ])
            ->add('h_about', 'textarea', [
                'placeholder' => 'Complément lié au logement ou au client',
                'label' => 'Autre informations',
                'required' => false
            ])
            ;
        return $builder->getForm();
    }
}