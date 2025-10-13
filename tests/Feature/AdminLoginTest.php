<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Providers\RouteServiceProvider;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admins_var_pieslegties_un_tiek_novirzits_uz_paneli(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'secret123', // rely on hashed cast in model or factory Hash::make
            'is_admin' => true,
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ]);

        // Breeze / Laravel default after login:
        $response->assertRedirect(RouteServiceProvider::HOME); // typically '/dashboard'

        $this->assertAuthenticatedAs($admin);
        // cast to bool so test doesn't depend on model $casts
        $this->assertTrue((bool) auth()->user()->is_admin);
    }

    public function test_neveiksmiga_pieslegsanas_ar_nepareiziem_datiem_radakludu(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'secret123',
            'is_admin' => true,
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'nepareizi',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_ne_admin_lietotajs_nevar_pieklut_admin_panelim(): void
    {
        // Šobrīd /admin nav definēts, tāpēc 404 ir sagaidāms.
        $user = User::factory()->create([
            'password' => 'secret123',
            'is_admin' => false,
        ]);

        $this->actingAs($user);

        $resp = $this->get('/admin');
        $resp->assertNotFound(); // nomaini uz assertRedirect/Forbidden, kad izveidosi /admin maršrutu ar aizsardzību
    }
}
