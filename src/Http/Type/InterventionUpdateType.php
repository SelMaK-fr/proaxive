<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\ChoiceType;
use Palmtree\Form\Type\TextareaType;
use Palmtree\Form\Type\TextType;

class InterventionUpdateType extends Type
{

    public function createFormBuilder(mixed $data = null): Form
    {
        $builder = (new FormBuilder([
            'key'             => 'intervention_update',
            'method'          => 'POST',
            'html_validation' => false,
        ], $data))
            ->add('name', TextType::class, [
                'label' => "Nom de l'intervention"
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
            ->add('way_type', ChoiceType::class, [
                'label' => "Statut",
                'placeholder' => "Sélectionnez un statut",
                'choices' => self::getStatus()
            ])
            ->add('users_id', ChoiceType::class, [
                'label' => "Technicien",
                'placeholder' => "Sélectionnez un technicien",
                'choices' => self::getWorkers($data['company_id'])
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
                'label' => "Description",
                'placeholder' => "Décrivez les détails de cette intervention",
                'required' => false
            ])
            ->add('handling_customer', TextareaType::class, [
                'label' => "Manipulations(s) effectuée(s)",
                'placeholder' => "Décrivez les manipulations que le client a déjà effectuées ou quand se produit le problème.",
                'required' => false
            ])
            ->add('observation', TextareaType::class, [
                'label' => "Observation",
                'placeholder' => "Rédiger une observation ici",
                'required' => false
            ])
            ->add('note_technician', TextareaType::class, [
                'label' => "Note Technicien",
                'placeholder' => "Note visible uniquement par les techniciens",
                'required' => false
            ])
            ->add('note_customer', TextareaType::class, [
                'label' => "Note à l'attention du client",
                'placeholder' => "Rédiger une note à l'attention du client",
                'required' => false
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

    private function getWorkers($data): array
    {
        $workers = [];
        $req = $this->query->from('users')->select(null)->select('users.id, users.fullname')->where('users.company_id = ?', $data);
        if($req){
            foreach ($req as $w) {
                $workers[$w['id']] = $w['fullname'];
            }
            return $workers;
        }
        return $workers;
    }
}