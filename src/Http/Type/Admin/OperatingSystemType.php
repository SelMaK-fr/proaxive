<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type\Admin;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\ChoiceType;
use Palmtree\Form\Type\TextType;
use Selmak\Proaxive2\Http\Type\Type;

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
                    'x64' => '64Bits',
                    'x86' => '32Bits',
                ],
                'placeholder' => false,
                'required' => true
            ])
            ->add('os_release', TextType::class, [
                'label' => 'Version du système',
                'placeholder' => 'ex : 22h2'
            ])
            ;
        return $builder->getForm();
    }
}