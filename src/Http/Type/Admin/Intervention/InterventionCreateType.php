<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type\Admin\Intervention;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\ChoiceType;
use Palmtree\Form\Type\TextareaType;
use Palmtree\Form\Type\TextType;
use Selmak\Proaxive2\Http\Type\Type;

class InterventionCreateType extends Type
{

    public function createFormBuilder(mixed $data = null, mixed $args = []): Form
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
            ->add('before_breakdown', TextareaType::class, [
                'label' => 'Avant panne',
                'placeholder' => "Précisez ce qu'il c'est passé avant la panne",
                'required' => false
            ])
        ;
        if($args->getRoles() === "SUPER_ADMIN"){
            $builder
                ->add('company_id', ChoiceType::class, [
                    'label' => 'Magasin',
                    'placeholder' => false,
                    'choices' => self::getCompany(),
                    'required' => true
                ])
                ;
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