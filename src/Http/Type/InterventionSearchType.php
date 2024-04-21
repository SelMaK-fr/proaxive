<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\ChoiceType;

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
                ],
                'required' => false

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
                ],
                'required' => false
            ])
            ->add('company_id', ChoiceType::class, [
                'label' => 'Magasin',
                'placeholder' => 'Sélectionnez un magasin/atelier',
                'choices' => self::getCompany(),
                'required' => false
            ])
            ->add('way_type', ChoiceType::class, [
                'label' => "Statut",
                'placeholder' => "Sélectionnez un statut",
                'choices' => self::getStatus(),
                'required' => false
            ])
            ->add('way_steps', ChoiceType::class, [
                'label' => "Etat",
                'placeholder' => "Sélectionnez un état",
                'choices' => [
                    1 => 'Matériel récupéré',
                    2 => 'En atelier',
                    3 => 'Tests finaux',
                    4 => 'En cours de sortie',
                    5 => 'Terminé',
                ],
                'required' => false
            ])
            ->add('users_id', ChoiceType::class, [
                'label' => "Technicien",
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
                $status[$s['name']] = $s['name'];
            }
            return $status;
        }
        return $status;
    }
}