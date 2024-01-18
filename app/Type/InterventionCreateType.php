<?php
declare(strict_types=1);
namespace App\Type;

use App\Type;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\ChoiceType;
use Palmtree\Form\Type\TextType;

class InterventionCreateType extends Type
{

    public function createFormBuilder(mixed $data = null): Form
    {
        $builder = (new FormBuilder([
            'key'             => 'intervention',
            'method'          => 'POST',
            'html_validation' => false,
        ], $data))
            ->add('name', TextType::class, [
                'label' => "Titre de l'intervention",
                'placeholder' => "Entrez un titre pour cette intervention"
            ])
            ->add('customers_id', 'choice', [
                'placeholder' => 'Sélectionnez un client',
                'label' => 'Clients',
                'choices' => self::getCustomer()
            ])
            ->add('sort', ChoiceType::class, [
                'label' => 'Type',
                'placeholder' => 'Choisissez un type',
                'choices' => [
                    'Dépannage' => 'Dépannage',
                    'Réparation' => 'Réparation',
                    'Visite périodique' => 'Visite périodique',
                    'Préparation de poste' => 'Préparation de poste',
                    'Assemblage' => 'Assemblage',
                    'Expertise' => 'Expertise',
                    'Devis' => 'Devis',
                ]
            ])
            ->add('a_priority', ChoiceType::class, [
                'label' => 'Priorité',
                'placeholder' => 'Sélectionnez un niveau',
                'choices' => [
                    'LOW' => 'Basse',
                    'AVERAGE' => 'Moyenne',
                    'URGENT' => 'Urgent',
                    'HIGH' => 'Élevée',
                    'ABSOLUTE' => 'Absolue',
                ]
            ])
            ->add('company_id', ChoiceType::class, [
                'label' => 'Magasin',
                'placeholder' => 'Sélectionnez un magasin/atelier',
                'choices' => self::getCompany(),
                'required' => false
            ])
            ;
        return $builder->getForm();
    }

    private function getCustomer(): array
    {
        $customers = [];
        $req = $this->query->from('customers')->select(null)->select('customers.id, customers.fullname')->fetchAll();
        foreach ($req as $c) {
            $e = $this->query->from('equipments')->where('customers_id = ?', $c['id'])->fetchAll();
            foreach ($e as $t) {
                if($t){
                    $customers[$c['id']] = $c['fullname'];
                }
            }
        }
        return $customers;
    }

    private function getCompany(): array
    {
        $companies = [];
        $req = $this->query->from('company')->select(null)->select('company.id, company.name')->fetchAll();
        foreach ($req as $c) {
            $companies[$c['id']] = $c['name'];
        }
        return $companies;
    }
}