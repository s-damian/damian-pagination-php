<?php

declare(strict_types=1);

namespace Tests\DamianPaginationPhp\Support\String;

use Tests\BaseTest;
use DamianPaginationPhp\Support\String\Str;

class StrTest extends BaseTest
{
    /**
     * Est appellée après chaque testMethod() de cette classe et de classes enfants.
     */
    public function tearDown(): void
    {
        parent::tearDown();

        $_GET = [];
    }

    public function testContains(): void
    {
        $test = 'testaaa';

        $this->assertTrue(Str::contains($test, 'aaa'));

        $this->assertFalse(Str::contains($test, 'aaabbb'));
    }

    public function testInputHiddenIfHasQueryString(): void
    {
        $this->assertSame(
            '',
            Str::inputHiddenIfHasQueryString(['except' => ['except_test_1', 'except_test_2']])
        );

        $_GET['orderby'] = 'title';
        $_GET['order'] = 'asc';

        $this->assertSame(
            '<input type="hidden" name="orderby" value="title"><input type="hidden" name="order" value="asc">',
            Str::inputHiddenIfHasQueryString()
        );

        $this->assertSame(
            '<input type="hidden" name="orderby" value="title">',
            Str::inputHiddenIfHasQueryString(['except' => ['order']])
        );
    }
}
