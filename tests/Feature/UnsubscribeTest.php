<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Campaign;
use App\Models\UserUnsubscribed;
use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UnsubscribeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_unsubscribe_via_signed_link()
    {
        // 1️⃣ Test üçün real user və campaign yaradılır
        $user = User::factory()->create();
        $campaign = Campaign::factory()->create();

        // 2️⃣ İmzalı URL hazırlanır
        $url = URL::signedRoute('unsubscribe', [
            'user' => $user->id,
            'campaign' => $campaign->id,
        ]);

        // 3️⃣ GET sorğusu göndərilir
        $response = $this->getJson($url);

        // 4️⃣ Cavabı yoxla
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Successfully unsubscribed']);

        // 5️⃣ Verilənlərin bazada qeyd olunduğunu yoxla
        $this->assertDatabaseHas('user_unsubscribed', [
            'user_id' => $user->id,
            'campaign_id' => $campaign->id,
        ]);

        // 6️⃣ User modelində marketing_opt_in false olmalıdır
        $this->assertFalse($user->fresh()->marketing_opt_in);
    }
}
