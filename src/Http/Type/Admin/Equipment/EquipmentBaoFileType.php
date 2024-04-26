<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Http\Type\Admin\Equipment;

use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Type\FileType;
use Selmak\Proaxive2\Http\Type\Type;

class EquipmentBaoFileType extends Type
{

    public function createFormBuilder(string|\stdClass|null|array $data = null): Form
    {
        $builder = (new FormBuilder([
           'key' => 'fileupload_bao',
           'ajax' => false,
            'html_validation' => true,
        ], $data))
            ->add('file', FileType::class);
            return $builder->getForm();
    }
}