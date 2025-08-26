<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PlaidService;
use Illuminate\Http\Request;

class PlaidController extends Controller
{
    public function __construct(protected PlaidService $plaid)
    {
    }

    public function linkToken()
    {
        $token = $this->plaid->createLinkToken();

        return response()->json(['link_token' => $token]);
    }

    public function webhook(Request $request)
    {
        $publicToken = $request->input('public_token');
        $accessToken = $this->plaid->exchangePublicToken($publicToken);
        $accounts = $this->plaid->getAccounts($accessToken);

        return response()->json([
            'access_token' => $accessToken,
            'accounts' => $accounts,
        ]);
    }
}

