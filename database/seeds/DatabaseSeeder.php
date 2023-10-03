<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '-1');//allocate memory

        DB::table('faqs')->insert([
            [
                'faq_id' => '2da8e5c3-3be6-4ebd-810c-824cb547d878',
                'title' => "How to approve payment",
                'description' => "If you do not receive pop-up to approve transaction, please follow these steps:\n1. dial *170# on the MTN mobile number you entered.\n2. Select option 6. My Wallet.\n3. Select option 3. My Approvals.\n4. Enter pin and approve pending transaction.",
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        DB::table('banners')->insert([
            [
                'banner_id' => '7e6ad1bd-28d8-4007-bf8e-0c985821742c',
                'title' => "",
                'url' => "https://i0.wp.com/www.ghlinks.com.gh/wp-content/uploads/2020/09/ekumfi-removebg-preview_720x.png?fit=500%2C500&ssl=1",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'banner_id' => '26dba9c8-dee7-41b7-a631-0b4b3bde7587',
                'title' => "",
                'url' => "http://www.ekumfijuice.com/images/slide1.jpg",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'banner_id' => '738e48dc-877b-4062-8d7a-f8e847d3a7dd',
                'title' => "",
                'url' => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS2q3awILvBFbpqWu2A7b7kb3lfF4Vj376_gA&usqp=CAU",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'banner_id' => '8fbf211d-2770-480e-a9bf-8ccb5b38550b',
                'title' => "",
                'url' => "https://gh.jumia.is/unsafe/fit-in/680x680/filters:fill(white)/product/64/128413/1.jpg?3043",
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        DB::table('products')->insert([
            [
                'product_id' => '855615f5-c73a-42d9-b085-7ff596230680',
                'name' => "Pineapple",
                'image_url' => "http://41.189.178.102/ekumfi_juice/storage/app/uploads/product-images/pineapple.png",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => '3a2bf8a9-3847-433a-89ff-93364862a911',
                'name' => "Pine Ginger",
                'image_url' => "http://41.189.178.102/ekumfi_juice/storage/app/uploads/product-images/pine_ginger.png",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => '5ad1907e-02fa-40d2-9e58-0d35956d06ed',
                'name' => "Pine Melon",
                'image_url' => "http://41.189.178.102/ekumfi_juice/storage/app/uploads/product-images/pine_melon.png",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => 'ba9b3b7f-c4f9-4be5-9a7f-47228aaf211b',
                'name' => "Pine Tropic",
                'image_url' => "http://41.189.178.102/ekumfi_juice/storage/app/uploads/product-images/pine_tropic.png",
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'product_id' => '7d74cf01-0269-4cb4-b666-579ce05f9fa5',
                'name' => "Tropic Ginger",
                'image_url' => "http://41.189.178.102/ekumfi_juice/storage/app/uploads/product-images/tropic_ginger.png",
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
