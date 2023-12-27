<?php
declare(strict_types=1);
namespace App\Type;

use App\Type;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\ChoiceType;
use Palmtree\Form\Type\TextType;

class OperatingSystemType extends Type
{
    public function createFormBuilder(mixed $data = null): Form
    {
        $builder = (new FormBuilder('operating_system', $data))
            ->add('os_name', TextType::class, [
                'label' => 'Nom du système',
                'placeholder' => 'ex : Windows 11'
            ])
            ->add('os_architecture', ChoiceType::class, [
                'label' => 'Architecture',
                'choices' => [
                    '64Bits' => '64Bits',
                    '32Bits' => '32Bits',
                ]
            ])
            ->add('os_release', TextType::class, [
                'label' => 'Version du système',
                'placeholder' => 'ex : 22h2'
            ])
            ;
        return $builder->getForm();
    }
}