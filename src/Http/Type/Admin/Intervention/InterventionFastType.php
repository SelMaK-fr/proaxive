<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type\Admin\Intervention;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\CheckboxType;
use Palmtree\Form\Type\ChoiceType;
use Palmtree\Form\Type\HiddenType;
use Palmtree\Form\Type\TextType;
use Selmak\Proaxive2\Http\Type\Type;

class InterventionFastType extends Type
{

    public function createFormBuilder(mixed $data = null): Form
    {
        $builder = (new FormBuilder([
            'key'             => 'intervention_add_fast',
            'method'          => 'POST',
            'action'          => '/admin/interventions/ajax/add/fast',
            'html_validation' => false,
        ], $data))
            ->add('name', TextType::class, [
                'label' => "Titre de l'intervention",
                'placeholder' => "Entrez un titre pour cette intervention"
            ])
            ->add('customers_id', 'choice', [
                'placeholder' => 'Sélectionnez un client',
                'label' => 'Clients',
                'choices' => $this->getCustomer(),
                'required' => false
            ])
            ->add('customer_name', HiddenType::class)
            ->add('create_customer', CheckboxType::class, [
                'label' => 'Créer un client',
                'required' => false
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
            ->add('company_id', ChoiceType::class, [
                'label' => 'Magasin',
                'placeholder' => 'Sélectionnez un magasin/atelier',
                'choices' => self::getCompany()
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
        $req = $this->query->from('company')
            ->select(null)
            ->select('company.id, company.name, COUNT(u.id) as countUsers')
            ->leftJoin('users as u ON u.company_id = company.id')
            ->groupBy('company.id, company.name')
            ->fetchAll();
        foreach ($req as $c) {
            if($c['countUsers'] > 0) {
                $companies[$c['id']] = $c['name'] . ' (' . $c['countUsers'] . ' user(s))';
            }
        }
        return $companies;
    }
}