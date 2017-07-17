<?php

namespace Tests\Browser;

use App\Repositories\CarRepository;
use App\Repositories\Contracts\CarRepositoryInterface;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CarsListTest extends DuskTestCase
{
    public function testSeeCarsData()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars')
                ->assertSee('Mercedes C-Classe')
                ->assertSee('White')
                ->assertSee('50')

                ->assertSee('Hyundai Elantra')
                ->assertSee('Silver')
                ->assertSee('30')

                ->assertSee('Skoda Octavia')
                ->assertSee('Blue')
                ->assertSee('35')

                ->assertSee('BMW Series 7')
                ->assertSee('Black')
                ->assertSee('60');
        });
    }

    public function testNotSeeAdditionalData()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars')
                ->assertSee('Mercedes C-Classe')

                ->assertDontSee('MB1234')
                ->assertDontSee('2012')

                ->assertDontSee('HE3214')
                ->assertDontSee('2015')

                ->assertDontSee('SO1342')
                ->assertDontSee('2013')

                ->assertDontSee('BMW789')
                ->assertDontSee('2010');
        });
    }

    public function testLinkToCarPage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars')
                ->assertSeeLink('Mercedes C-Classe')
                ->assertSeeLink('Hyundai Elantra')
                ->assertSeeLink('Skoda Octavia')
                ->assertSeeLink('BMW Series 7');

            $browser->clickLink('Mercedes C-Classe')
                ->assertPathIs('/cars/1')
                ->assertSee('MB1234')
                ->back();

            $browser->clickLink('Hyundai Elantra')
                ->assertPathIs('/cars/2')
                ->assertSee('HE3214')
                ->back();

            $browser->clickLink('Skoda Octavia')
                ->assertPathIs('/cars/3')
                ->assertSee('SO1342')
                ->back();

            $browser->clickLink('BMW Series 7')
                ->assertPathIs('/cars/4')
                ->assertSee('BMW789')
                ->back();
        });
    }

    public function notSeePlaceholder()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars')
                ->assertSee('Mercedes C-Classe')
                ->assertDontSee('No cars');
        });
    }
}
