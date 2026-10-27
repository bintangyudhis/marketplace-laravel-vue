<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seed();

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_api_returns_seeded_products_and_supports_search(): void
    {
        $this->seed();

        $this->getJson('/api/products')
            ->assertOk()
            ->assertJsonCount(9, 'data');

        $this->getJson('/api/products?q=kopi')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_buyer_can_add_to_cart_and_checkout(): void
    {
        $this->seed();

        $buyer = User::where('email', 'pembeli@demo.test')->firstOrFail();
        $product = Product::where('name', 'like', '%Kopi%')->firstOrFail();
        $stockBefore = $product->stock;

        $this->actingAs($buyer)
            ->post(route('cart.add', $product), ['quantity' => 2])
            ->assertRedirect(route('cart.index'));

        $this->actingAs($buyer)
            ->post(route('checkout.store'), [
                'recipient_name' => 'Budi Santoso',
                'phone' => '081234567890',
                'address' => 'Jalan Uji No. 1',
            ])
            ->assertRedirectContains('/pesanan/');

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertSame(Order::STATUS_BARU, Order::first()->status);
        $this->assertSame($stockBefore - 2, $product->fresh()->stock);
    }
}
