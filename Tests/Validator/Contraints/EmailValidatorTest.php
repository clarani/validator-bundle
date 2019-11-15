<?php

namespace AssoConnect\ValidatorBundle\Tests\Validator\Constraints;

use AssoConnect\ValidatorBundle\Validator\Constraints\Email;
use AssoConnect\ValidatorBundle\Validator\Constraints\EmailValidator;
use LayerShifter\TLDDatabase\Store;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class EmailValidatorTest extends ConstraintValidatorTestCase
{

    public function createValidator()
    {
        $store = new Store();
        return new EmailValidator($store);
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new Email());
        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid()
    {
        $this->validator->validate('', new Email());
        $this->assertNoViolation();
    }

    /**
     * @dataProvider providerInvalidValues
     */
    public function testInvalidValues($value, $messageField, $code)
    {
        $this->validator->validate(
            $value,
            new Email([
                $messageField => 'myMessage',
            ])
        );
        $this
            ->buildViolation('myMessage')
            ->setParameter('{{ value }}', '"' . $value . '"')
            ->setCode($code)
            ->assertRaised();
    }
    public function providerInvalidValues()
    {
        return [
            // Format
            // #0
            [
                'format',
                'message',
                Email::INVALID_FORMAT_ERROR,
            ],
            // #1
            [
                'format;format@mail.com',
                'message',
                Email::INVALID_FORMAT_ERROR,
            ],
            // #2
            [
                'mailto:format@mail.com',
                'message',
                Email::INVALID_FORMAT_ERROR,
            ],
            // #3
            [
                ' format@mail.com',
                'message',
                Email::INVALID_FORMAT_ERROR,
            ],
            // #4
            [
                'format@mail.com ',
                'message',
                Email::INVALID_FORMAT_ERROR,
            ],
            // #5
            [
                'format@mail.com.',
                'message',
                Email::INVALID_FORMAT_ERROR,
            ],
            // #6 TLD
            [
                'tld@mail.error',
                'TLDMessage',
                Email::INVALID_TLD_ERROR,
            ],
        ];
    }

    /**
     * @dataProvider providerValidValues
     */
    public function testValidValues($value)
    {
        $this->validator->validate($value, new Email());
        $this->assertNoViolation();
    }
    public function providerValidValues()
    {
        return [
            // Valid
            // #0
            ['valid@mail.com'],
            // #1
            ['valid.valid@mail.com'],
            // #2
            ['valid+valid@mail.com'],
        ];
    }

}