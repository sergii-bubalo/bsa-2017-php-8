<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HeaderLinksTest extends DuskTestCase
{
    public function testSeeCarsListLink()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars/3')
                ->assertSeeLink('Cars list')
                ->clickLink('Cars list')
                ->assertPathIs('/cars');

            $browser->visit('/cars')
                ->assertSeeLink('Cars list')
                ->clickLink('Cars list')
                ->assertPathIs('/cars');
        });
    }

    public function testSeeAddLink()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars/1')
                ->assertSeeLink('Add')
                ->clickLink('Add')
                ->assertPathIs('/cars/create');

            $browser->visit('/cars')
                ->assertSeeLink('Add')
                ->clickLink('Add')
                ->assertPathIs('/cars/create');
        });
    }
}
