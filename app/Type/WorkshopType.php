<?php
declare(strict_types=1);
namespace App\Type;

use App\Type;
use Palmtree\Form\Constraint\File as FileConstraint;
use Palmtree\Form\Constraint\Number;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;

class WorkshopType extends Type
{

    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('workshop', $data))
            ->add('name', 'text', [
                'label' => "Nom de l'atelier",
                'placeholder' => 'Dénomination commerciale'
            ])
            ->add('type', 'choice', [
                'label' => "Statut juridique",
                'placeholder' => 'Choisissez un type',
                'choices' => [
                    "EI" => 'EI',
                    "Entreprise Individuelle" => 'Entreprise Individuelle',
                    "EIRL" => 'EIRL',
                    "EURL" => 'EURL',
                    "SAS" => 'SAS',
                    "SARL" => 'SARL',
                ]
            ])
            ->add('phone', 'tel', [
                'label' => "Tél Fixe",
                'placeholder' => 'N° de téléphone',
                'constraints' => [
                    new Number()
                ],
                'error_message' => 'Numéro invalid'
            ])
            ->add('about', 'textarea', [
                'label' => "Description",
                'placeholder' => 'Courte description',
                'required' => false
            ])
            ->add('mail', 'email', [
                'label' => "Courriel",
                'placeholder' => 'Courriel'
            ])
            ->add('mobile', 'tel', [
                'label' => "Tél Mobile",
                'placeholder' => 'N° de téléphone portable',
                'required' => false
            ])
            ->add('director', 'text', [
                'label' => "Gérant",
                'placeholder' => 'Gérant du magasin ou de l\'atelier',
                'required' => false
            ])
            ->add('siret', 'text', [
                'label' => "N° siret ou siren",
                'placeholder' => 'Entrez votre numéro de siret/siren'
            ])
            ->add('rcs', 'text', [
                'label' => "Code RCS",
                'placeholder' => 'Entrez votre code RCS',
                'required' => false
            ])
            ->add('tva', 'text', [
                'label' => "Numéro de TVA",
                'placeholder' => 'Entrez votre numéro de TVA',
                'required' => false
            ])
            ->add('ape', 'text', [
                'label' => "Code NAF/APE/APRM",
                'placeholder' => 'Entrez votre code APE'
            ])
            ->add('rm', 'text', [
                'label' => "Code RM",
                'placeholder' => 'Entrez votre code RM',
                'required' => false
            ])
            ->add('rc_pro', 'text', [
                'label' => "RC Pro",
                'placeholder' => 'Entrez votre numéro RC PRO',
                'required' => false
            ])
            ->add('address', 'text', [
                'label' => "Adresse Postale",
                'placeholder' => 'Entrez votre adresse postale',
                'required' => false
            ])
            ->add('city', 'text', [
                'label' => "Ville",
                'placeholder' => 'Entrez votre ville',
                'required' => false
            ])
            ->add('zipcode', 'text', [
                'label' => "Code postal",
                'placeholder' => 'Entrez votre code postal',
                'required' => false
            ])
            ->add('department', 'text', [
                'label' => "Département",
                'placeholder' => 'Entrez votre département',
                'required' => false
            ])
            ->add('website', 'text', [
                'label' => "Site internet",
                'placeholder' => 'Entrez le lien de votre site internet (avec HTTPS://)',
                'required' => false
            ])
            ->add('facebook', 'text', [
                'label' => "Lien Facebook",
                'placeholder' => 'Entrez le lien de votre profil Facebook',
                'required' => false
            ])
            ->add('twitter', 'text', [
                'label' => "Lien Twitter/X",
                'placeholder' => 'Entrez le lien de votre compte Twitter/X',
                'required' => false
            ])
            ->add('linkedin', 'text', [
                'label' => "Lien Linkedin",
                'placeholder' => 'Entrez le lien de votre compte Linkedin',
                'required' => false
            ])
            ->add('instagram', 'text', [
                'label' => "Lien Instagram",
                'placeholder' => 'Entrez lelien de votre profil Instagram',
                'required' => false
            ])
            ;

        return $builder->getForm();
    }
}