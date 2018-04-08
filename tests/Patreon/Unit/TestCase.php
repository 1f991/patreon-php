<?php declare(strict_types=1);

namespace Squid\Patreon\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

class TestCase extends PHPUnit_TestCase
{
    /**
     * Returns the contents of a fixture as a string.
     *
     * @param string $path Path to the fixture
     *
     * @return string
     */
    public function fixture(string $path): string
    {
        return file_get_contents(__DIR__ . '/../fixtures/' . $path);
    }
}
