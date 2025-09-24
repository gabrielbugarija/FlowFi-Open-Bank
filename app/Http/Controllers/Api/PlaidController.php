<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PlaidService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PlaidController extends Controller
{
    public function __construct(protected PlaidService $plaid)
    {
    }

    public function linkToken(Request $request)
    {
        $userId = (int) $request->user()->getKey();

        $token = $this->plaid->createLinkToken($userId);

        return response()->json(['link_token' => $token]);
    }

    public function exchange(Request $request)
    {
        $validated = $request->validate([
            'public_token' => ['required', 'string'],
        ]);

        $exchange = $this->plaid->exchangePublicToken($validated['public_token']);

        $request->user()
            ->forceFill([
                'plaid_access_token' => Crypt::encryptString($exchange['access_token']),
            ])
            ->save();

        return response()->noContent();
    }

    public function webhook(Request $request)
    {
        $secret = config('plaid.webhook_secret');

        if (! is_string($secret) || $secret === '') {
            return response()->json(['message' => 'Webhook secret not configured.'], 500);
        }

        $signatureHeader = $request->header('Plaid-Webhook-Signature')
            ?? $request->header('PLAID-WEBHOOK-SIGNATURE');

        if ($signatureHeader === null || $signatureHeader === '') {
            return response()->json(['message' => 'Missing Plaid signature header.'], 401);
        }

        $signatures = $this->parseSignatureHeader($signatureHeader);

        if (! isset($signatures['v1']) || $signatures['v1'] === '') {
            return response()->json(['message' => 'Missing Plaid signature version.'], 401);
        }

        $expected = hash_hmac('sha256', $request->getContent(), $secret);

        if (! hash_equals($expected, $signatures['v1'])) {
            return response()->json(['message' => 'Invalid Plaid signature.'], 403);
        }

        $payload = $request->validate([
            'webhook_type' => ['required', 'string'],
            'webhook_code' => ['required', 'string'],
        ]);

        if (! in_array($payload['webhook_type'], $this->allowedWebhookTypes(), true)) {
            return response()->json(['message' => 'Unsupported webhook type.'], 400);
        }

        return response()->noContent();
    }

    protected function parseSignatureHeader(string $header): array
    {
        $parts = array_filter(array_map('trim', explode(',', $header)));

        $signatures = [];

        foreach ($parts as $part) {
            [$key, $value] = array_pad(explode('=', $part, 2), 2, null);

            if ($key !== null && $value !== null) {
                $signatures[$key] = $value;
            }
        }

        return $signatures;
    }

    protected function allowedWebhookTypes(): array
    {
        return [
            'TRANSACTIONS',
            'ITEM',
            'AUTH',
            'LIABILITIES',
            'INVESTMENTS_TRANSACTIONS',
            'HOLDINGS',
            'PAYMENT_INITIATION',
            'TRANSFER',
            'INCOME',
            'STATEMENTS',
        ];
    }
}

