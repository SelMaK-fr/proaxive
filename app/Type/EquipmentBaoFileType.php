<?php
declare(strict_types=1);
namespace App\Type;

use App\Type;
use Palmtree\Form\Form;
use Palmtree\Form\FormBuilder;
use Palmtree\Form\Constraint\File as FileConstraint;
use Palmtree\Form\Type\FileType;

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