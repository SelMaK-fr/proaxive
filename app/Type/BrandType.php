<?php
declare(strict_types=1);
namespace App\Type;

use App\Type;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\FileType;
use Palmtree\Form\Type\TextType;

class BrandType extends Type
{

    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('brand', $data))
            ->add('name', TextType::class, [
                'label' => "Nom"
            ])
            ->add('logo_link', TextType::class, [
                'label' => "Lien externe",
                'required' => false
            ])
            ->add('logo_file', FileType::class, [
                'label' => 'Charger un logo',
                'required' => false
            ])
            ;
        return $builder->getForm();
    }
}