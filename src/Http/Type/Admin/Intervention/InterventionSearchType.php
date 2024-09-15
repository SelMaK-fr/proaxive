<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type\Admin\Intervention;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\ChoiceType;
use Selmak\Proaxive2\Http\Type\Type;

class InterventionSearchType extends Type
{
    public function createFormBuilder(mixed $data = null): Form
    {
        $builder = (new FormBuilder([
            'key'             => 'intervention_search',
            'method'          => 'GET',
            'html_validation' => false,
        ], $data))
            ->add('sort', ChoiceType::class, [
                'label' => '',
                'placeholder' => 'Choisissez un type',
                'choices' => self::getTypeIntervention(),
                'required' => false

            ])
            ->add('a_priority', ChoiceType::class, [
                'label' => '',
                'placeholder' => 'Sélectionnez un niveau',
                'choices' => [
                    'LOW' => 'Basse',
                    'AVERAGE' => 'Moyenne',
                    'URGENT' => 'Urgent',
                    'HIGH' => 'Élevée',
                    'ABSOLUTE' => 'Absolue',
                ],
                'required' => false
            ])
            ->add('company_id', ChoiceType::class, [
                'label' => '',
                'placeholder' => 'Sélectionnez un magasin/atelier',
                'choices' => self::getCompany(),
                'required' => false
            ])
            ->add('status_id', ChoiceType::class, [
                'label' => "",
                'placeholder' => "Sélectionnez un statut",
                'choices' => self::getStatus(),
                'required' => false
            ])
            ->add('way_steps', ChoiceType::class, [
                'label' => "",
                'placeholder' => "Sélectionnez un état",
                'choices' => [
                    1 => 'Matériel récupéré',
                    2 => 'En atelier',
                    3 => 'Tests finaux',
                    4 => 'En attente de récupération / livraison',
                    5 => 'Matériel récupéré / livré',
                ],
                'required' => false
            ])
            ->add('users_id', ChoiceType::class, [
                'label' => "",
                'placeholder' => "Sélectionnez un technicien",
                'choices' => self::getUsers(),
                'required' => false
            ])
            ;
        return $builder->getForm();
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

    private function getUsers(): array
    {
        $users = [];
        $req = $this->query->from('users')->select(null)->select('users.id, users.fullname')->fetchAll();
        foreach ($req as $u) {
            $users[$u['id']] = $u['fullname'];
        }
        return $users;
    }

    private function getStatus(): array
    {
        $status = [];
        $req = $this->query->from('status')->select(null)->select('status.id, status.name')->fetchAll();
        if($req){
            foreach ($req as $s) {
                $status[$s['id']] = $s['name'];
            }
            return $status;
        }
        return $status;
    }

    private function getTypeIntervention(): array
    {
        $type = [];
        $req = $this->query->from('types_interventions as ti')->select(null)->select('ti.id, ti.name')->orderBy('name ASC')->fetchAll();
        if($req){
            foreach ($req as $e) {
                $label = $e['name'];
                $type[$e['name']] = $label;
            }
            return $type;
        }
        return $type;
    }
}