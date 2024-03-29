<?php

declare(strict_types=1);

namespace Tests\DamianPaginationPhp\Config;

use Tests\BaseTest;
use DamianPaginationPhp\Config\Config;

class ConfigTest extends BaseTest
{
    public function testConfig(): void
    {
        $this->assertSame('en', Config::get()['lang'] ?? ''); // "?? ''" pour phpstan (level 7)

        $this->assertTrue(is_array(Config::get()));

        // Et on change la langue.
        Config::set(['lang' => 'fr']);

        $this->assertSame('fr', Config::get()['lang'] ?? ''); // "?? ''" pour phpstan (level 7)

        $this->assertTrue(is_array(Config::get()));
    }
}
