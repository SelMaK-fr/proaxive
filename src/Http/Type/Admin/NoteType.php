<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type\Admin;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\CheckboxType;
use Palmtree\Form\Type\TextareaType;
use Palmtree\Form\Type\TextType;
use Selmak\Proaxive2\Http\Type\Type;

class NoteType extends Type
{
    public function createFormBuilder(mixed $data = null, ?array $args = []): Form
    {
        $builder = (new FormBuilder([
            'key'             => 'note',
            'method'          => 'POST',
            'html_validation' => false,
        ], $data))
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'required' => true,
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu',
                'required' => true,
            ])
            ->add('stick', CheckboxType::class, [
                'label' => 'Epingler',
                'required' => false,
            ])
            ->add('bgcolor', TextType::class, [
                'label' => 'Couleur de fond',
                'required' => false,
            ])
            ->add('txtcolor', TextType::class, [
                'label' => 'Couleur du texte',
                'required' => false,
            ])
        ;

        return $builder->getForm();
    }
}