<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type;

use Palmtree\Form\Constraint\Length;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\TextType;

class AccountTotpType extends Type
{
    public function createFormBuilder(mixed $data = null): Form
    {
        $builder = (new FormBuilder('account_totp', $data))
            ->add('code', TextType::class, [
                'label' => '',
                'placeholder' => 'Code de vérification'
            ])
        ;
        return $builder->getForm();
    }
}