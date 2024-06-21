<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type\Admin;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\CheckboxType;
use Palmtree\Form\Type\TextType;
use Selmak\Proaxive2\Http\Type\Type;

class DocumentType extends Type
{
    public function createFormBuilder(mixed $data = null, ?array $args = []): Form
    {
        $builder = (new FormBuilder([
            'key'             => 'edocument',
            'method'          => 'POST',
            'html_validation' => false,
        ], $data))
            ->add('name', TextType::class, [
                'label' => "Nom du document",
                'placeholder' => "Entrez un nom pour ce document"
            ])
            ->add('description', TextType::class, [
                'label' => "Description",
                'required' => false,
                'placeholder' => "Petite description"
            ])
            ->add('is_online', CheckboxType::class, [
                'required' => false,
                'label' => 'Mode public'
            ])
        ;

        return $builder->getForm();
    }
}