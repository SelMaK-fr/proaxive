<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type\Admin;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\HiddenType;
use Palmtree\Form\Type\TextareaType;
use Selmak\Proaxive2\Http\Type\ColorType;
use Selmak\Proaxive2\Http\Type\Type;

class StatusType extends Type
{
    public function createFormBuilder(mixed $data = null): Form
    {
        $builder = (new FormBuilder('status', $data))
            ->add('name', 'text', [
                'label' => 'Titre',
                'placeholder' => 'Titre du statut'
            ])
            ->add('color', ColorType::class, [
                'label' => 'Couleur de fond',
                'required' => false,
                'placeholder' => 'Laissez vide pour désactiver'
            ])
            ->add('color_txt', ColorType::class, [
                'label' => 'Couleur du texte',
                'required' => false,
                'placeholder' => 'Laissez vide pour désactiver'
            ])
            ->add('fixed', HiddenType::class, [
                'required' => false
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'placeholder' => 'Informations sur le statut',
                'required' => false
            ])
            ;
        return $builder->getForm();
    }
}