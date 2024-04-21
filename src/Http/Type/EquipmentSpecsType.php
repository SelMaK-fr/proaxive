<?php

namespace Selmak\Proaxive2\Http\Type;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;

class EquipmentSpecsType extends Type
{

    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder('equipment_specs', $data))
            ->add('c_install_date', 'text', [
                'label' => "Date d'installation",
                'placeholder' => "ex : 2022",
                'error_message' => 'Veuillez renseigner un numéro de série.',
                'required' => false,
            ])
            ->add('c_processor', 'text', [
                'required' => false,
                'label' => 'Processeur',
                'placeholder' => 'ex : Intel(R) Core(TM) i3-7100U CPU @ 2.40GHz'
            ])
            ->add('c_socket', 'text', [
                'required' => false,
                'label' => "Socket",
                'placeholder' => 'ex : LGA 1155'
            ])
            ->add('c_gpu', 'text', [
                'required' => false,
                'label' => "Carte Graphique",
                'placeholder' => "ex : Intel(R) HD Graphics 4400"
            ])
            ->add('e_licence', 'text', [
                'required' => false,
                'label' => "Licence Windows",
                'placeholder' => "NW9GB-3YD8K-WC99X-YCB2K-WTYK7"
            ])
            ->add('c_motherboard', 'text', [
                'required' => false,
                'label' => "Carte mère",
                'placeholder' => "ex : H61M-D2H-USB3"
            ])
            ->add('c_bios', 'text', [
                'required' => false,
                'label' => "Version du BIOS",
                'placeholder' => "ex : F13"
            ])
            ->add('c_memory', 'text', [
                'required' => false,
                'label' => "Mémoire RAM",
                'placeholder' => "ex : 16255 Mo"
            ])
            ->add('c_memory', 'text', [
                'required' => false,
                'label' => "Mémoire RAM",
                'placeholder' => "ex : 16255 Mo"
            ])
        ;

        return $builder->getForm();
    }

}