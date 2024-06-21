<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type\Admin\Booking;

use Cassandra\Date;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\CheckboxType;
use Palmtree\Form\Type\ChoiceType;
use Palmtree\Form\Type\RadioType;
use Palmtree\Form\Type\TextareaType;
use Palmtree\Form\Type\TextType;
use Selmak\Proaxive2\Http\Type\Admin\DateType;
use Selmak\Proaxive2\Http\Type\DateTimeType;
use Selmak\Proaxive2\Http\Type\TimeType;
use Selmak\Proaxive2\Http\Type\Type;

class BookingForInterventionType extends Type
{
    public function createFormBuilder(mixed $data = null): Form
    {
        $builder = (new FormBuilder('booking', $data))
            ->add('start_date', DateType::class, [
                'label' => "Date de retrait"
            ])
            ->add('start_time', TimeType::class, [
                'label' => "Heure de retrait"
            ])
            ->add('end_time', TimeType::class, [
                'label' => "Fin de retrait"
            ])
            ->add('subtitle', ChoiceType::class, [
                'label' => "Lieu du retrait",
                'required' => true,
                'expanded' => true,
                'inline' => false,
                'choices' => [
                    'A domicile' => 'A domicile',
                    'En boutique' => 'En boutique'
                ]
            ])
        ;
        return $builder->getForm();
    }
}