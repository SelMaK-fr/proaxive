<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type\Admin\Booking;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\CheckboxType;
use Palmtree\Form\Type\TextareaType;
use Palmtree\Form\Type\TextType;
use Selmak\Proaxive2\Http\Type\Admin\DateType;
use Selmak\Proaxive2\Http\Type\TimeType;
use Selmak\Proaxive2\Http\Type\Type;

class BookingType extends Type
{
    public function createFormBuilder(mixed $data = null): Form
    {
        $builder = (new FormBuilder('booking', $data))
            ->add('start_date', DateType::class, [
                'label' => ""
            ])
            ->add('start_time', TimeType::class, [
                'label' => ""
            ])
            ->add('end_date', DateType::class, [
                'label' => "",
                'required' => false
            ])
            ->add('end_time', TimeType::class, [
                'label' => "",
                'required' => false
            ])
            ->add('title', TextType::class, [
                'label' => "Titre de l'évènement",
                'required' => true
            ])
            ->add('subtitle', TextType::class, [
                'label' => "Sous-titre de l'évènement",
                'required' => true
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description",
                'required' => true
            ])
            ->add('backgroundColor', TextType::class, [
                'label' => "Couleur de fond",
                'required' => true
            ])
            ->add('textColor', TextType::class, [
                'label' => "Couleur du texte",
                'required' => true
            ])
            ->add('allDay', CheckboxType::class, [
                'label' => "Toute la journée ?",
                'required' => false
            ])
        ;
        return $builder->getForm();
    }
}