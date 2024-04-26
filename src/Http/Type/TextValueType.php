<?php

declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type;

use Palmtree\Form\Type\AbstractType;
use Palmtree\Html\Element;

class TextValueType extends AbstractType
{
    protected $type = 'text';

    public function getElement(): Element
    {
        $element = parent::getElement();
        if ($this->value) {
            $element->attributes['value'] = $this->value;
        }
        //$element->classes->add('palmtree-form-control', 'form-control');
        return $element;
    }
}