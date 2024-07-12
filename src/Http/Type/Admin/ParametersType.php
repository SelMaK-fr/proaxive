<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type\Admin;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\CheckboxType;
use Palmtree\Form\Type\ChoiceType;
use Palmtree\Form\Type\NumberType;
use Selmak\Proaxive2\Http\Type\Type;

class ParametersType extends Type
{

    public function createFormBuilder(mixed $data = null): Form
    {
        $builder = (new FormBuilder('parameters', $data))
            ->add('expiration_link', NumberType::class, [
                'label' => "Nombre de jour",
                'required' => false
            ])
            ->add('api_address', CheckboxType::class, [
                'label' => '',
                'required' => false
            ])
            ->add('mail_auto', CheckboxType::class, [
                'label' => '',
                'required' => false
            ])
            ->add('api_nominatim', CheckboxType::class, [
                'label' => '',
                'required' => false
            ])
            ->add('date_for_number', ChoiceType::class, [
                'label' => "Format de date",
                'choices' => [
                    'Y' => date('Y').'-XXX (Année + num)',
                    'Ym' => date('Ym').'-XXX (Année/mois + num)',
                    'y' => date('y').'-XXX (Année réduite + num)',
                    'ym' => date('ym').'-XXX (Année réduite + mois + num)',
                    'ymd' => date('ymd').'-XXX (Année/mois/jour + num)',
                    'yd' => date('yd').'-XXX (Année/jour + num)',
                ]
            ])
            ->add('generate_number_alpha', NumberType::class, [
                'label' => "Nombre de caractères alphanumérique",
                'required' => false
            ])
            ->add('php_error', CheckboxType::class, [
                'label' => '',
                'required' => false
            ])
            ;
        return $builder->getForm();
    }
}