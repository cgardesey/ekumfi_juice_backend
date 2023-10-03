<?php

namespace Tests\Unit\Controllers;

use App\Banner;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BannerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index method of BannerController.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = factory(User::class)->create();

        $banners = factory(Banner::class, 3)->create();

        $response = $this->get('/api/banners', [
            'Authorization' => 'Bearer ' . $user->api_token,
        ]);

        $response->assertStatus(200);


        $response->assertJsonFragment([
            'banner_id' => $banners[0]->banner_id,
            'title' => $banners[0]->title,
            'url' => $banners[0]->url
        ]);
    }

    /**
     * Test show method of BannerController.
     *
     * @return void
     */
    public function testShow()
    {/**/
        $banner = factory(Banner::class)->create();

        $user = factory(User::class)->create();
        $response = $this->get('api/banners/' . $banner->banner_id , [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'banner_id' => $banner->banner_id,
            'title' => $banner->title,
            'url' => $banner->url
        ]);
    }

    /**
     * Test update method of BannerController.
     *
     * @return void
     */
    public function testUpdate()
    {
        $banner = factory(Banner::class)->create();
        $banner->makeVisible('banner_id');

        $requestData = factory(Banner::class)->make()->toArray();

        $user = factory(User::class)->create();

        $response = $this->patch('/api/banners/' . $banner->banner_id, $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'title' => $requestData['title'],
            'url' => $requestData['url']
        ]);

        $banner->refresh();

        $this->assertDatabaseHas('banners', [
            'title' => $requestData['title'],
            'url' => $requestData['url']
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Banner $Banner
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $Banner)
    {
        $status = $Banner->delete();
        return Response::json(array(
            'status' => $status
        ));
    }
}
