<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type;

use Palmtree\Form\Constraint\Length;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;

class SocietyType extends Type
{
    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('customer', $data))
            ->add('login_id', 'text', [
                'required' => false,
                'placeholder' => 'Laissez vide pour désactiver'
            ])
            ->add('mail', 'email', [
                'required' => false,
                'label' => 'Adresse courriel'
            ])
            ->add('fullname', 'text', [
                'required' => true,
                'error_message' => 'Please enter valid fullname',
                'label' => "Nom de l'entreprise",
                'constraints' => [
                    new Length(['min' => 3])
                ]
            ])
            ->add('type_status', 'choice', [
                'placeholder' => 'Selectionnez un status',
                'label' => 'Status',
                'choices'     => [
                    'EI' => 'EI',
                    'Entreprise Individuelle' => 'Entreprise Individuelle',
                    'EIRL' => 'EIRL',
                    'EURL' => 'EURL',
                    'SAS' => 'SAS',
                    'SA' => 'SA',
                    'SARL' => 'SARL',
                ],
                'required' => false
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
            ->add('siret_number', 'text', [
                'placeholder' => 'Numéro de siret',
                'label' => 'N°Siret',
                'required' => false
            ])
            ->add('tva_number', 'text', [
                'placeholder' => 'Numéro de TVA',
                'label' => 'N°TVA',
                'required' => false
            ])
            ->add('naf_number', 'text', [
                'placeholder' => 'Code NAF',
                'label' => 'Code NAF',
                'required' => false
            ])
            ->add('website', 'text', [
                'placeholder' => 'Site internet avec HTTPS://',
                'label' => 'Site internet',
                'required' => false
            ])
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
                'required' => true
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