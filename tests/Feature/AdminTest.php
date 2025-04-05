<?php

namespace Tests\Feature;

use App\Jobs\SendPriceChangeNotification;
use App\Models\Product;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertRedirectToRoute('admin.products');
        $this->assertAuthenticated();

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

        $response = $this->get('/admin/products');

        $response->assertStatus(200);
        $response->assertViewIs('admin.products');
        $response->assertViewHas('products', fn ($viewProducts) => $viewProducts->count() === $count);
    }

    public function test_edit_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->get("/admin/products/{$product->id}/edit");

        $response->assertStatus(200);
        $response->assertViewIs('admin.edit_product');
        $response->assertViewHas('product', fn (Product $p) => $p->getKey() === $product->getKey());
    }

    public function test_update_product(): void
    {
        Bus::fake();

        $product = Product::factory()->create(['price' => 100]);

        $response = $this->put("/admin/products/{$product->getKey()}", [
            'name' => 'Updated Name',
            'description' => 'Updated Description',
            'price' => 150,
        ]);

        $response->assertRedirectToRoute('admin.products');
        $this->assertDatabaseHas('products', [
            'id' => $product->getKey(),
            'price' => 150,
        ]);

        Bus::assertDispatched(SendPriceChangeNotification::class);
    }

    public function test_delete_product_removes_it_and_redirects(): void
    {
        $product = Product::factory()->create();

        $response = $this->delete("/admin/products/{$product->getKey()}");

        $response->assertRedirectToRoute('admin.products');
        $this->assertDatabaseMissing('products', ['id' => $product->getKey()]);
    }

    public function test_add_product_form(): void
    {
        $response = $this->get('/admin/products/create');

        $response->assertOk();
        $response->assertViewIs('admin.add_product');
    }

    public function test_add_product(): void
    {
        $response = $this->post('/admin/products', [
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
