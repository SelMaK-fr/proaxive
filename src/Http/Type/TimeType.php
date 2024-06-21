<?php
declare(strict_types=1);

namespace Selmak\Proaxive2\Http\Type;

use Palmtree\Form\Type\TextType;

class TimeType extends TextType
{
    protected $type = 'time';
}