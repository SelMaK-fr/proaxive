<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type\Admin;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Selmak\Proaxive2\Http\Type\Type;

class PeripheralType extends Type
{
    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('equipment', $data))
            ->add('customers_id', 'choice', [
                'placeholder' => 'Sélectionnez un client',
                'label' => 'Clients',
                'choices' => $this->getCustomer(),
                'required' => false
            ])
            ->add('types_equipments_id', 'choice', [
                'placeholder' => 'Sélectionnez un type',
                'label' => 'Types',
                'choices' => $this->getType()
            ])
            ->add('brands_id', 'choice', [
                'placeholder' => 'Sélectionnez une marque',
                'label' => 'Marques',
                'choices' => $this->getBrand()
            ])
            ->add('localization_site', 'text', [
                'placeholder' => 'Annexe de nantes dans le bureau de pascal',
                'label' => 'Localisation/site',
                'required' => false
            ])
            ->add('name', 'text', [
                'required' => true,
                'label' => 'Dénomination',
                'placeholder' => "Nom de l'équipement"
            ])
            ->add('end_guarantee', DateType::class, [
                'label' => 'Fin de garantie',
                'required' => false
            ])
            ->add('type_name', 'hidden', [
                'required' => false
            ])
            ->add('brand_name', 'hidden', [
                'required' => false
            ])
            ->add('e_serial', 'text', [
                'label' => 'Numéro de série',
                'placeholder' => "Numéro de série",
                'error_message' => 'Veuillez renseigner un numéro de série.'
            ])
            ->add('e_model', 'text', [
                'required' => false,
                'label' => 'Numéro de produit',
                'placeholder' => 'ex : HP ProDesk 400 G2 MT'
            ])
            ->add('e_year', 'number', [
                'required' => false,
                'label' => "Année d'achat",
                'placeholder' => 'ex : 2023'
            ])
            ->add('u_username', 'text', [
                'required' => false,
                'label' => "Nom d'utilisateur",
                'placeholder' => "ex : John"
            ])
            ->add('u_password', 'text', [
                'required' => false,
                'label' => "Mot de passe",
                'placeholder' => "MonMotDePasse (sera en clair)"
            ])
            ->add('u_account_mail', 'email', [
                'required' => false,
                'label' => "Compte courriel du système",
                'placeholder' => "ex : john@microsoft.tld"
            ])
            ->add('u_domain', 'text', [
                'required' => false,
                'label' => "Domaine",
                'placeholder' => "ex : domain/tld"
            ])
            ->add('n_ipaddress', 'text', [
                'required' => false,
                'label' => "Adresse IP",
                'placeholder' => "ex : 192.168.0.2"
            ])
            ->add('n_masknetwork', 'text', [
                'required' => false,
                'label' => "Masque sous-réseau",
                'placeholder' => "ex : 255.255.255.0"
            ])
            ->add('n_gateway', 'text', [
                'required' => false,
                'label' => "Passerelle",
                'placeholder' => "ex : 192.168.0.254"
            ])
            ->add('n_dns', 'text', [
                'required' => false,
                'label' => "DNS",
                'placeholder' => "ex : 8.8.8.1"
            ])
            ->add('n_ssid', 'text', [
                'required' => false,
                'label' => "SSID Wifi",
                'placeholder' => "Salon"
            ])
            ->add('n_wifi_key', 'textarea', [
                'required' => false,
                'label' => "Clé wifi",
                'placeholder' => "Clé de connexion Wifi"
            ])
            ->add('is_outofservice', 'checkbox', [
                'required' => false,
                'label' => 'Hors-Service ?'
            ])
            ;
        return $builder->getForm();
    }

    private function getCustomer(): array
    {
        $customers = [];
        $req = $this->query->from('customers')->select(null)->select('customers.id, customers.fullname')->fetchAll();
        foreach ($req as $c) {
            $customers[$c['id']] = $c['fullname'];
        }
        return $customers;
    }

    /**
     * Return types equipments list
     * @return array
     * @throws \Envms\FluentPDO\Exception
     */
    private function getType(): array
    {
        $types = [];
        $req = $this->query->from('types_equipments')->select(null)->select('types_equipments.id, types_equipments.name')->where('is_peripheral = 1')->fetchAll();
        foreach ($req as $t) {
            $types[$t['id']] = $t['name'];
        }
        return $types;
    }

    /**
     * Return brands list
     * @return array
     * @throws \Envms\FluentPDO\Exception
     */
    private function getBrand(): array
    {
        $brands = [];
        $req = $this->query->from('brands')->select(null)->select('brands.id, brands.name')->fetchAll();
        foreach ($req as $b) {
            $brands[$b['id']] = $b['name'];
        }
        return $brands;
    }
}