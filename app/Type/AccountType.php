<?php
declare (strict_types = 1);
namespace App\Type;

use App\Type;
use Envms\FluentPDO\Query;
use Palmtree\Form\Constraint\Length;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Slim\App;

class AccountType extends Type
{

    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('account', $data))
            ->add('fullname', 'text', [
                'error_message' => 'Please enter your fullname',
                'required' => false,
                'constraints' => [
                    new Length(['min' => 3])
                ]
            ])
            ->add('pseudo', 'text', [
                'required' => true
            ]);
        $builder->add('roles', 'choice', [
                'placeholder' => 'Select role',
                'choices'     => $this->getRoles(),
                'mapped' => false
            ])
            ;
        return $builder->getForm();
    }

    private function getRoles(): array
    {
        $roles = [];
        $req = $this->query->from('users')->select(null)->select('users.id, users.roles')->fetchAll();
        foreach ($req as $role) {
            $roles[$role['roles']] = $role['roles'];
        }
        return $roles;
    }
}