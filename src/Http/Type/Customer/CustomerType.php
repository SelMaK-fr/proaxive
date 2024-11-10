<?php
declare (strict_types = 1);
namespace Selmak\Proaxive2\Http\Type\Customer;

use Palmtree\Form\Constraint\Length;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Selmak\Proaxive2\Http\Type\Type;

class CustomerType extends Type
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
            ->add('civility', 'choice', [
                'label' => 'Civilité',
                'placeholder' => false,
                'choices'     => [
                    'M.' => 'M.',
                    'Mlle' => 'Mlle',
                    'Mme' => 'Mme'
                ],
                'required' => false
            ])
            ->add('lastname', 'text', [
                'required' => true,
                'error_message' => 'Please enter valid lastname',
                'label' => 'Nom',
                'placeholder' => 'Nom',
                'constraints' => [
                    new Length(['min' => 3])
                ]
            ])
            ->add('firstname', 'text', [
                'required' => true,
                'error_message' => 'Please enter valid firstname',
                'label' => 'Prénom',
                'placeholder' => 'Prénom',
                'constraints' => [
                    new Length(['min' => 3])
                ]
            ])
            ->add('profil_type', 'choice', [
                'placeholder' => 'Selectionnez un profil',
                'label' => 'Profil particulié',
                'choices'     => [
                    'Personne âgée' => 'Personne âgée',
                    'Malentendante' => 'Malentendante',
                    'Malvoyante' => 'Malvoyante',
                    'Sous-tutelle' => 'Sous-tutelle'
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
            ->add('contact_status', 'choice', [
                'placeholder' => 'Selectionnez un statut',
                'label' => 'Statut',
                'choices'     => [
                    'Tuteur(trice)' => 'Tuteur(trice)',
                    'Responsable' => 'Responsable',
                    'Proche' => 'Proche'
                ],
                'required' => false
            ])
            ->add('contact_phone', 'tel', [
                'placeholder' => 'Numéro de téléphone',
                'label' => 'Téléphone',
                'required' => false
            ])
            ->add('contact_fullname', 'text', [
                'placeholder' => 'Nom complet',
                'label' => 'Nom complet',
                'required' => false
            ])
            ->add('contact_mail', 'email', [
                'placeholder' => 'Courriel',
                'label' => 'Courriel',
                'required' => false
            ])
            ->add('contact_address', 'textarea', [
                'placeholder' => 'Adresse complète',
                'label' => 'Adresse complète',
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