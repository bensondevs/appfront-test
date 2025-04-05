<?php

namespace Tests\Feature;

use App\Jobs\SendPriceChangeNotification;
use App\Models\Product;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AdminController
 */
class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertViewIs('login');
    }

    public function test_login(): void
    {
        $user = UserFactory::new()->create();

        $invalidResponse = $this->post('/login', [
            'email' => 'something@email.com',
            'password' => 'password',
        ]);
        $invalidResponse->assertRedirect();

        $sqlInjectionResponse = $this->post('/login', [
            'email' => "' OR 1=1 --",
            'password' => 'password',
        ]);
        $sqlInjectionResponse->assertRedirect();
        $this->assertGuest();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertRedirectToRoute('admin.products');
        $this->assertAuthenticated();
    }

    public function test_logout(): void
    {
        $user = UserFactory::new()->create();
        $this->actingAs($user);

        $this->assertAuthenticatedAs($user);

        $response = $this->post('/logout');
        $response->assertRedirectToRoute('login');
        $this->assertGuest();
    }

    public function test_products(): void
    {
        Product::factory()->count($count = rand(3, 5))->create();

        $this->actingAs(UserFactory::new()->create());
        $response = $this->get('/admin/products');

        $response->assertStatus(200);
        $response->assertViewIs('admin.products');
        $response->assertViewHas('products', fn ($viewProducts) => $viewProducts->count() === $count);
    }

    public function test_edit_product(): void
    {
        $product = Product::factory()->create();

        $this->actingAs(UserFactory::new()->create());
        $response = $this->get("/admin/products/edit/{$product->getKey()}");

        $response->assertStatus(200);
        $response->assertViewIs('admin.edit_product');
        $response->assertViewHas('product', fn (Product $p) => $p->getKey() === $product->getKey());
    }

    public function test_update_product(): void
    {
        Bus::fake();

        $this->actingAs(UserFactory::new()->create());
        $product = Product::factory()->create(['price' => 100]);

        $response = $this->put("/admin/products/edit/{$product->getKey()}", [
            'name' => 'Updated Name',
            'description' => 'Updated Description',
            'price' => 150,
            'image' => UploadedFile::fake()->image($fileName = 'image.jpg'),
        ]);

        $response->assertRedirectToRoute('admin.products');
        $this->assertDatabaseHas('products', [
            'id' => $product->getKey(),
            'price' => 150,
        ]);

        $product->refresh();
        $this->assertTrue(str($product->getImage())->endsWith('-' . $fileName));

        Bus::assertDispatched(SendPriceChangeNotification::class);
    }

    public function test_delete_product(): void
    {
        $product = Product::factory()->create();

        $this->actingAs(UserFactory::new()->create());
        $response = $this->delete("/admin/products/delete/{$product->getKey()}");

        $response->assertRedirectToRoute('admin.products');
        $this->assertDatabaseMissing('products', ['id' => $product->getKey()]);
    }

    public function test_add_product_form(): void
    {
        $this->actingAs(UserFactory::new()->create());
        $response = $this->get('/admin/products/add');

        $response->assertOk();
        $response->assertViewIs('admin.add_product');
    }

    public function test_add_product(): void
    {
        $this->actingAs(UserFactory::new()->create());
        $response = $this->post('/admin/products/add', [
            'name' => $name = fake()->words(asText: true),
            'description' => $description = fake()->text(),
            'price' => $price = fake()->randomFloat(2, 100, 1000),
        ]);
        $response->assertRedirectToRoute('admin.products');

        $this->assertDatabaseHas('products', [
            'name' => $name,
            'description' => $description,
            'price' => $price,
        ]);
    }
}
