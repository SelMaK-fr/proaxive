<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type;

use Palmtree\Form\Constraint\Length;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;

class UserType extends Type
{

    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('user', $data))
            ->add('fullname', 'text', [
                'label' => 'Nom complet',
                'required' => false,
                'constraints' => [
                    new Length(['min' => 3])
                ]
            ])
            ->add('pseudo', 'text', [
                'label' => 'Pseudo',
                'required' => true
            ])
            ->add('mail', 'email', [
                'label' => 'Courriel'
            ])
            ->add('roles', 'choice', [
                'placeholder' => 'Choisissez un rôle',
                'choices'     => [
                    'SUPER_ADMIN' => 'Administrateur/root',
                    'USER_TECH' => 'Technicien',
                    'USER_MANAGER' => 'Manager'
                ],
                'mapped' => false
            ])
            ->add('company_id', 'choice', [
                'label' => 'Magasin/Atelier',
                'placeholder' => 'Magasin/atelier rattaché',
                'choices'     => $this->getCompany(),
                'mapped' => false
            ])
        ;
        return $builder->getForm();
    }

    private function getCompany(): array
    {
        $company = [];
        $req = $this->query->from('company')->select(null)->select('company.id, company.name')->fetchAll();
        foreach ($req as $c) {
            $company[$c['id']] = $c['name'];
        }
        return $company;
    }
}