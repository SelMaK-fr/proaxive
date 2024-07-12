<?php
declare(strict_types=1);
namespace Selmak\Proaxive2\Validator\Type;

use Awurth\Validator\Validator;
use Palmtree\Form\Constraint\AbstractConstraint;
use Palmtree\Form\Constraint\ConstraintInterface;
use Respect\Validation\Validator as V;

class MimeType extends AbstractConstraint implements ConstraintInterface
{
    protected $errorMessage = "L'extension de mime_type n'est pas valide (fichier invalide)";

    public function validate($input): bool
    {
        // /!\ ERROR
        // Unable to retrieve request here. Looking for a way to retrieve the value
        // of "file" which does not exist in the UploadFile object
        // DO NOT USE
        $validator = Validator::create();
        $failures = $validator->validate($input, [
            'picture' => [
                'rules' => v::objectType()->attribute('file', v::oneOf(
                    v::mimetype('image/jpeg'),
                    v::mimetype('image/png')
                ))
            ]
        ]);
        if (0 !== $failures->count()) {
            foreach ($failures as $failure) {
                echo $failure->getMessage();
            }
            return false;
        }
        return true;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}