<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type\Admin\Intervention;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\ChoiceType;
use Palmtree\Form\Type\HiddenType;
use Palmtree\Form\Type\TextareaType;
use Palmtree\Form\Type\TextType;
use Selmak\Proaxive2\Http\Type\Type;

class InterventionArgsType extends Type
{
    public function createFormBuilder(mixed $data = null, ?array $args = []): Form
    {
        $builder = (new FormBuilder([
            'key' => 'intervention_args',
            'method' => 'POST',
            'html_validation' => false,
        ], $data))
            ->add('name', TextType::class, [
                'label' => "Titre de l'intervention",
                'placeholder' => "Entrez un titre pour cette intervention"
            ])
            ->add('sort', ChoiceType::class, [
                'label' => 'Type',
                'placeholder' => 'Choisissez un type',
                'choices' => self::getTypeIntervention()
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'placeholder' => "Rédigez une courte description de la panne",
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
                ]
            ])
            ->add('before_breakdown', TextareaType::class, [
                'label' => 'Avant panne',
                'placeholder' => "Précisez ce qu'il c'est passé avant la panne",
                'required' => false
            ]);
        if($args['e'] == 0) {
            $builder
            ->add('equipments_id', ChoiceType::class, [
                'label' => 'Equipements',
                'placeholder' => false,
                'choices' => self::getEquipments((int)$args['customers_id'])
            ]);
        }
        return $builder->getForm();
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

    private function getEquipments(int $data): array
    {
        $equipments = [];
        $req = $this->query->from('equipments')->select(null)->select('equipments.id, equipments.name, equipments.brand_name')->where('customers_id = ?', $data)->fetchAll();
        if($req){
            foreach ($req as $e) {
                $label = $e['name'] . ' (' . $e['brand_name'] . ')';
                $equipments[$e['id']] = $label;
            }
            return $equipments;
        }
        return $equipments;
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