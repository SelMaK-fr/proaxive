<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type\Admin;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\AbstractType;
use Palmtree\Form\Type\ChoiceType;
use Palmtree\Form\Type\TextareaType;
use Palmtree\Form\Type\TextType;
use Selmak\Proaxive2\Http\Type\Type;

class OutlayType extends Type
{
    public function createFormBuilder(mixed $data = null, ?array $args = []): Form
    {
        $builder = (new FormBuilder([
            'key'             => 'outlay',
            'method'          => 'POST',
            'html_validation' => false,
        ], $data))
            ->add('customers_id', ChoiceType::class, [
                'label' => "Clients",
                'placeholder' => 'Sélectionnez un client',
                'choices' => self::getCustomer(),
                'required' => true
            ])
            ->add('amount', TextType::class, [
                'label' => "Montant du débours",
                'placeholder' => 'Montant précis ou maximum',
                'required' => true
            ])
            ->add('payment_method', ChoiceType::class, [
                'label' => "Moyen de remboursement",
                'placeholder' => 'Sélectionnez une méthode',
                'choices' => [
                    'Carte bancaire' => 'Carte bancaire',
                    'Espèce' => 'Espèce',
                    'Chèque' => 'Chèque',
                    'Virement' => 'Virement',
                    'Paypal' => 'Paypal',
                    'Stripe' => 'Stripe',
                ],
                'required' => true
            ])
            ->add('reference_propal', TextType::class, [
                'label' => 'Référence devis',
                'placeholder' => 'Numéro de devis de référence',
                'required' => false
            ])
            ->add('reference_intervention', TextType::class, [
                'label' => 'Référence intervention',
                'placeholder' => "Numéro de l'intervention associée",
                'required' => false
            ])
            ->add('seller', TextType::class, [
                'label' => 'Vendeur',
                'placeholder' => 'Site de vente, entreprise',
                'required' => true
            ])
            ->add('products', TextareaType::class, [
                'label' => 'Informations complémentaire',
                'placeholder' => 'Peut contenir la liste des articles achetés',
                'required' => true
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'placeholder' => "Sélectionnez un statut",
                'choices' => $this->getStatus(),
                'required' => true
            ])
        ;
        return $builder->getForm();
    }

    private function getStatus(): array
    {
        $status = [];
        $req = $this->query->from('status')->select(null)->select('status.id, status.name')->fetchAll();
        if($req){
            foreach ($req as $s) {
                $status[$s['name']] = $s['name'];
            }
            return $status;
        }
        return $status;
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
}