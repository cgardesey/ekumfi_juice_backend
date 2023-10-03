<?php

namespace Tests\Unit;

use App\Ekumfi;
use App\EkumfiInfo;
use App\Info;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EkumfiInfoFactoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /** @test */
    public function it_can_create_an_ekumfi_info()
    {
        $ekumfi_info = factory(EkumfiInfo::class)->create();

        $this->assertInstanceOf(EkumfiInfo::class, $ekumfi_info);
        $this->assertDatabaseHas('ekumfi_infos', [
            'ekumfi_info_id' => $ekumfi_info->ekumfi_info_id,
            'name' => $ekumfi_info->name,
            'profile_image_url' => $ekumfi_info->profile_image_url,
            'primary_contact' => $ekumfi_info->primary_contact,
            'auxiliary_contact' => $ekumfi_info->auxiliary_contact,
            'longitude' => $ekumfi_info->longitude,
            'latitude' => $ekumfi_info->latitude,
            'digital_address' => $ekumfi_info->digital_address,
            'street_address' => $ekumfi_info->street_address,
            'availability' => $ekumfi_info->availability,
            'user_id' => $ekumfi_info->user_id,
        ]);
    }
}
