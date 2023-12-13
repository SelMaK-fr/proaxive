<?php

namespace Selmak\Proaxive2\Validation;

use Respect\Validation\Validator as Respect;

class RespectValiator
{

    public function validate($request, array $rules)
    {
        dd('works');
    }
}