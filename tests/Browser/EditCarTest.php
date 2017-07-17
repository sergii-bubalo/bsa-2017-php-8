<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EditCarTest extends DuskTestCase
{
    public function testSeeOldModelValues()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars/3/edit');

            $this->assertEquals($browser->value('input[name=model]'), 'Skoda Octavia');
            $this->assertEquals($browser->value('input[name=registration_number]'), 'SO1342');
            $this->assertEquals($browser->value('input[name=year]'), '2013');
            $this->assertEquals($browser->value('input[name=color]'), 'Blue');
            $this->assertEquals($browser->value('input[name=price]'), '35');
        });
    }

    public function testCSRFTokenIsPresent()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars/1/edit');
            $token = $browser->value('input[name=_token]');
            $this->assertNotEmpty($token);
        });
    }

    public function testValidates()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars/1/edit');


            $browser
                ->value('input[name=model]', '')
                ->value('input[name=registration_number]', '')
                ->value('input[name=year]', '')
                ->value('input[name=color]', '')
                ->value('input[name=price]', '')
                ->press('Save')
                ->assertPathIs('/cars/1/edit')
                ->assertSee('The model field is required.')
                ->assertSee('The registration number field is required.')
                ->assertSee('The year field is required.')
                ->assertSee('The color field is required.')
                ->assertSee('The price field is required.');

            $browser
                ->value('input[name=registration_number]', 'a')
                ->value('input[name=year]', '2050')
                ->value('input[name=color]', '1')
                ->value('input[name=price]', 'a')
                ->press('Save')
                ->assertSee('The registration number must be 6 characters.')
                ->assertSee('The year must be between 1000 and 2017.')
                ->assertSee('The color may only contain letters.')
                ->assertSee('The price must be a number.');

            $browser
                ->value('input[name=registration_number]', '!')
                ->value('input[name=year]', 'a')
                ->press('Save')
                ->assertSee('The year must be an integer.')
                ->assertSee('The registration number may only contain letters and numbers.');
        });
    }

    public function testOldValuesOnError()
    {
        $this->browse(function (Browser $browser) {
            $value = 'test';

            $browser->visit('/cars/1/edit')
                ->value('input[name=model]', $value)
                ->value('input[name=registration_number]', $value)
                ->value('input[name=year]', $value)
                ->value('input[name=color]', $value)
                ->value('input[name=price]', $value)
                ->press('Save');

            $this->assertEquals($browser->value('input[name=model]'), $value);
            $this->assertEquals($browser->value('input[name=registration_number]'), $value);
            $this->assertEquals($browser->value('input[name=year]'), $value);
            $this->assertEquals($browser->value('input[name=color]'), $value);
            $this->assertEquals($browser->value('input[name=price]'), $value);
        });
    }

    public function testSavesCar()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars/1/edit/')
                ->value('input[name=model]', 'model_value')
                ->value('input[name=registration_number]', '123456')
                ->value('input[name=year]', '1500')
                ->value('input[name=color]', 'colorValue')
                ->value('input[name=price]', '9999')
                ->press('Save')
                ->assertPathIs('/cars/1')
                ->assertSee('model_value')
                ->assertSee('123456')
                ->assertSee('1500')
                ->assertSee('colorValue')
                ->assertSee('9999');
        });
    }
}
