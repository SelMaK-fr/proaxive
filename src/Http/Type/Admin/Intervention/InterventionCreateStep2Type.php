<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type\Admin\Intervention;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\ChoiceType;
use Selmak\Proaxive2\Http\Type\TextValueType;
use Selmak\Proaxive2\Http\Type\Type;

class InterventionCreateStep2Type extends Type
{
    public function createFormBuilder(mixed $data = null, ?array $args = []): Form
    {
        $builder = (new FormBuilder([
            'key'             => 'intervention_step2',
            'method'          => 'POST',
            'html_validation' => false,
        ], $data))
            ->add('customers_id', 'choice', [
                'placeholder' => 'Sélectionnez un client',
                'label' => 'Clients',
                'choices' => self::getCustomer()
            ])
        ;
        if($args['roles'] === "SUPER_ADMIN"){
            $builder
                ->add('users_id', 'choice', [
                    'placeholder' => 'Sélectionnez un technicien',
                    'label' => 'Techniciens',
                    'choices' => self::getUsers((int)$args['company_id']),
                    'expanded' => true,
                    'inline' => false,
                ])
            ;
        } else {
            $builder
                ->add('users_id', 'hidden')
                ;
        }
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

    public function getUsers(int $data): array
    {
        $users = [];
        $req = $this->query->from('users')->select(null)->select('users.id, users.fullname, users.roles, users.mail')->where('company_id = ?', $data)->fetchAll();
        if($req){
            foreach ($req as $u) {
                $label = $u['fullname'] . '<span class="d-block fw-400 fs-12px">' . $u['roles'] . '</span>' . $u['mail'];
                $users[$u['id']] = $label;
            }
            return $users;
        }
        return $users;
    }
}