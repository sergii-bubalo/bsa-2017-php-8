<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Session;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddCarTest extends DuskTestCase
{
    public function testAllFieldsIsEmpty()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars/create');
            $this->assertEmpty($browser->value('input[name=model]'));
            $this->assertEmpty($browser->value('input[name=registration_number]'));
            $this->assertEmpty($browser->value('input[name=year]'));
            $this->assertEmpty($browser->value('input[name=color]'));
            $this->assertEmpty($browser->value('input[name=price]'));
        });
    }

    public function testCSRFTokenIsPresent()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars/create');
            $token = $browser->value('input[name=_token]');
            $this->assertNotEmpty($token);
        });
    }

    public function testValidates()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/cars/create');

            $browser->press('Save')
                ->assertPathIs('/cars/create')
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

            $browser->visit('/cars/create')
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
            $browser->visit('/cars/create')
                ->value('input[name=model]', 'model_value')
                ->value('input[name=registration_number]', '123456')
                ->value('input[name=year]', '1500')
                ->value('input[name=color]', 'colorValue')
                ->value('input[name=price]', '9999')
                ->press('Save')
                ->assertPathIs('/cars')
                ->assertSeeLink('model_value')
                ->assertSee('colorValue')
                ->assertSee('9999');
        });
    }
}
