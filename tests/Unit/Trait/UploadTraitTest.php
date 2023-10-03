<?php

namespace Tests\Unit\Traits;

use App\Traits\UploadTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadTraitTest extends TestCase
{
    use RefreshDatabase;

    protected $testDisk = 'public';

    /** @test */
    public function it_can_upload_a_file()
    {
        Storage::fake($this->testDisk);

        $traitInstance = $this->getMockForTrait(UploadTrait::class);

        $uploadedFile = UploadedFile::fake()->create('test_image.jpg', 500); // Create a fake uploaded file.

        $folder = 'test_folder';
        $filename = 'test_filename';

        $result = $traitInstance->uploadOne($uploadedFile, $folder, $this->testDisk, $filename);

        Storage::disk($this->testDisk)->assertExists($result); // Check if the file exists on the disk.

        Storage::disk($this->testDisk)->assertExists($folder.'/'.$filename.'.'.$uploadedFile->getClientOriginalExtension());
    }
}

