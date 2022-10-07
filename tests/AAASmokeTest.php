<?php

declare(strict_types=1);

namespace Vanilo\Braintree\Tests;

class AAASmokeTest extends TestCase
{
    public const MIN_PHP_VERSION = '7.4.0';

    /** @test */
    public function smoke()
    {
        $this->assertTrue(true);
    }

    /**
     * @depends smoke
     * @test
     */
    public function php_version_satisfies_requirements()
    {
        $this->assertFalse(
            version_compare(PHP_VERSION, self::MIN_PHP_VERSION, '<'),
            'PHP version ' . self::MIN_PHP_VERSION . ' or greater is required but only '
            . PHP_VERSION . ' found.'
        );
    }
}
