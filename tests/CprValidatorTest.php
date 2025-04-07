<?php

namespace Tykfyr\Cpr\Tests;

use PHPUnit\Framework\TestCase;
use Tykfyr\Cpr\CprValidator;
use Carbon\Carbon;

class CprValidatorTest extends TestCase
{
    public function testValidCpr()
    {
        $this->assertTrue(CprValidator::isValid('0101011234'));
    }

    public function testInvalidCprWrongLength()
    {
        $this->assertFalse(CprValidator::isValid('123456789'));
    }

    public function testInvalidCprNonDate()
    {
        $this->assertFalse(CprValidator::isValid('3102991234')); // 31. februar findes ikke
    }

    public function testGenderMale()
    {
        $this->assertEquals('male', CprValidator::getGender('0101011235'));
    }

    public function testGenderFemale()
    {
        $this->assertEquals('female', CprValidator::getGender('0101011234'));
    }

    public function testGetBirthdate()
    {
        $date = CprValidator::getBirthdate('0101011234');
        $this->assertInstanceOf(Carbon::class, $date);
        $this->assertEquals('1901-01-01', $date->format('Y-m-d'));
    }
}