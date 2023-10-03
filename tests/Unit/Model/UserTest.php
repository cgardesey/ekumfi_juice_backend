<?php

namespace Tests\Unit;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $user = new User();
        $this->assertEquals('users', $user->getTable());
    }

    /** @test */
    public function it_uses_user_id_as_the_primary_key()
    {
        $user = new User();

        $this->assertEquals('user_id', $user->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $user = new User();

        $this->assertFalse($user->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_type()
    {
        $user = new User();
        $this->assertEquals('string', $user->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $user = new User();
        $hidden = [
            'id', 'active', 'otp', 'apphash', 'osversion', 'sdkversion', 'device', 'devicemodel', 'deviceproduct', 'manufacturer', 'androidid', 'versionrelease', 'deviceheight', 'devicewidth', 'email_verified_at', 'password', 'api_token', 'email_verified'
        ];
        $this->assertEquals($hidden, $user->getHidden());
    }

    /** @test */
    public function it_returns_user_id_for_route_key()
    {
        $user = new User();

        $this->assertEquals('user_id', $user->getRouteKeyName());
    }
}
