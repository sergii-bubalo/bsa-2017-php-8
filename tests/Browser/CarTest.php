<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CarTest extends DuskTestCase
{
    public function testSeeCarData()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars/3')
                ->assertSee('Skoda Octavia')
                ->assertSee('Blue')
                ->assertSee('35')
                ->assertSee('SO1342')
                ->assertSee('2013');
        });
    }

    public function testTitleContainsModel()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars/4')
                ->assertTitleContains('BMW Series 7');
        });
    }

    public function testEditButton()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars/3')
                ->assertSee('Edit')
                ->clickLink('Edit')
                ->assertPathIs('/cars/3/edit');
        });
    }

    public function testDeleteButton()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars/4')
                ->assertSee('Delete')
                ->click('.delete-button')
                ->assertPathIs('/cars');
        });
    }
}
