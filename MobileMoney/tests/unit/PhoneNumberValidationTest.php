<?php

use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class PhoneNumberValidationTest extends CIUnitTestCase
{
    public function testFormattedTenDigitNumbersAreAccepted(): void
    {
        $controller = new class extends \App\Controllers\BaseController {
            public function exposeNormalize(?string $value): string
            {
                return $this->normalizePhoneNumber($value);
            }

            public function exposeIsValid(?string $value): bool
            {
                return $this->isValidPhoneNumber($value);
            }
        };

        $this->assertSame('0331234567', $controller->exposeNormalize(' 033 123-4567 '));
        $this->assertTrue($controller->exposeIsValid('0331234567'));
        $this->assertFalse($controller->exposeIsValid('123456789'));
    }
}
