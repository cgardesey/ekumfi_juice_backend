<?php

namespace Tests\Unit;

use App\Agent;
use App\User;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserFactoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /** @test */
    public function it_can_create_a_user()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'phone_number' => $user->phone_number,
            'username' => $user->username,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'confirmation_token' => $user->confirmation_token,
            'password' => $user->password,
            'api_token' => $user->api_token,
            'role' => $user->role,
            'email_verified' => $user->email_verified,
            'active' => $user->active,
            'connected' => $user->connected,
            'otp' => $user->otp,
            'app_hash' => $user->app_hash,
            'os_version' => $user->os_version,
            'sdk_version' => $user->sdk_version,
            'device' => $user->device,
            'device_model' => $user->device_model,
            'device_product' => $user->device_product,
            'manufacturer' => $user->manufacturer,
            'android_id' => $user->android_id,
            'version_release' => $user->version_release,
            'device_height' => $user->device_height,
            'device_width' => $user->device_width,
            'guid' => $user->guid,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }
}
