<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserUnsubscribed;
use App\Repositories\UserRepository;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class UnsubscribeController extends Controller
{
    public function __construct(private UserRepositoryInterface $userRepo) {}

    public function unsubscribe(int $userId, int $campaignId, Request $request)
    {
        if (!$request->hasValidSignature()) {
            return response()->json(['message' => 'Invalid link'], 400);
        }

        $user = $this->userRepo->find($userId);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        UserUnsubscribed::firstOrCreate(['user_id' => $userId, 'campaign_id' => $campaignId, 'signature' => $request->signature]);

        $this->userRepo->update($user, [
            'marketing_opt_in' => false
        ]);


        return response()->json(['message' => 'Successfully unsubscribed']);
    }
}
