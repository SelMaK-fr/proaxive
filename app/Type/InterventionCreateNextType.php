<?php
declare(strict_types=1);
namespace App\Type;

use App\Type;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\CheckboxType;
use Palmtree\Form\Type\ChoiceType;
use Palmtree\Form\Type\HiddenType;
use Palmtree\Form\Type\TextareaType;
use Palmtree\Form\Type\TextType;
use Selmak\Proaxive2\Type\DateType;

class InterventionCreateNextType extends Type
{
    public function createFormBuilder(mixed $data = null, ?array $args = []): Form
    {
        $builder = (new FormBuilder([
            'key'             => 'intervention_2time',
            'method'          => 'POST',
            'html_validation' => false,
        ], $data))
            ->add('equipments_id', ChoiceType::class, [
                'label' => 'Equipement',
                'placeholder' => "Sélectionnez un équipement",
                'choices' => self::getEquipment((int)$args['customers_id']),
            ])
            ->add('users_id', ChoiceType::class, [
                'label' => 'Technicien',
                'placeholder' => "Sélectionnez un technicien",
                'choices' => self::getUsers((int)$args['company_id']),
            ])
            ->add('equipment_name', HiddenType::class, [
                'label' => "equipment_name",
            ])
            ->add('way_type', ChoiceType::class, [
                'label' => "Statut",
                'placeholder' => "Sélectionnez un statut",
                'choices' => self::getStatus()
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
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'placeholder' => "Rédigez une courte description de la panne",
                'required' => false
            ])
            ->add('before_breakdown', TextareaType::class, [
                'label' => 'Avant panne',
                'placeholder' => "Précisez ce qu'il c'est passé avant la panne",
                'required' => false
            ])
            ->add('observation', TextareaType::class, [
                'label' => 'Observation',
                'placeholder' => "Rédigez une observation",
                'required' => false
            ])
            ->add('note_technician', TextareaType::class, [
                'label' => 'Note technicien',
                'placeholder' => "Note pour le technicien",
                'required' => false
            ])
        ;
        return $builder->getForm();
    }

    private function getEquipment(int $data): array
    {
        $equipments = [];
        $req = $this->query->from('equipments')->select(null)->select('equipments.id, equipments.name')->where('customers_id = ?', $data)->fetchAll();
        if($req){
            foreach ($req as $e) {
                $equipments[$e['id']] = $e['name'];
            }
            return $equipments;
        }
        return $equipments;
    }

    public function getUsers(int $data): array
    {
        $users = [];
        $req = $this->query->from('users')->select(null)->select('users.id, users.fullname')->where('company_id = ?', $data)->fetchAll();
        if($req){
            foreach ($req as $u) {
                $users[$u['id']] = $u['fullname'];
            }
            return $users;
        }
        return $users;
    }

    private function findEquipment($data): int
    {
        return $this->query->from('equipments')->where('customers_id = ?', $data)->count();
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