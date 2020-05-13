<?php

declare(strict_types=1);

namespace AssoConnect\ValidatorBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class FrenchRnaValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        // Source : https://www.data.gouv.fr/fr/datasets/repertoire-national-des-associations/
        // the "jgmrt" is a specific letter for the DOM-TOM
        if ((preg_match('/(^W\d[\dJGMRT]\d{7}$)|(^\d[\dJGMRT]\d[PS]\d{10}$)/', $value) !== 1)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(FrenchRna::INVALID_FORMAT_ERROR)
                ->addViolation();
        }
    }

    public function getSupportedConstraint(): string
    {
        return FrenchRna::class;
    }
}