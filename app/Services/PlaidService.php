<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class PlaidService
{
    public function __construct(
        protected ?string $clientId = null,
        protected ?string $secret = null,
        protected ?string $environment = null,
    ) {
        $this->clientId ??= config('plaid.client_id');
        $this->secret ??= config('plaid.secret');
        $this->environment ??= config('plaid.env', 'sandbox');
    }

    public function createLinkToken(?int $userId = null): string
    {
        $payload = [
            'client_name' => config('app.name', 'FlowFi'),
            'country_codes' => ['CA'],
            'language' => 'en',
            'products' => ['transactions'],
            'user' => [
                'client_user_id' => (string) ($userId ?? auth()->id() ?? 'demo-user'),
            ],
        ];

        $response = $this->plaidRequest('/link/token/create', $payload);

        $linkToken = $response['link_token'] ?? null;

        if (! is_string($linkToken) || $linkToken === '') {
            throw new RuntimeException('Plaid did not return a link token.');
        }

        return $linkToken;
    }

    /**
     * Exchange a public token for an access token and associated metadata.
     *
     * @return array{access_token: string, item_id: string|null}
     */
    public function exchangePublicToken(string $publicToken): array
    {
        $response = $this->plaidRequest('/item/public_token/exchange', [
            'public_token' => $publicToken,
        ]);

        $accessToken = $response['access_token'] ?? null;
        $itemId = $response['item_id'] ?? null;

        if (! is_string($accessToken) || $accessToken === '') {
            throw new RuntimeException('Plaid did not return an access token.');
        }

        return [
            'access_token' => $accessToken,
            'item_id' => is_string($itemId) && $itemId !== '' ? $itemId : null,
        ];
    }

    public function getAccounts(string $accessToken): array
    {
        $response = $this->plaidRequest('/accounts/get', [
            'access_token' => $accessToken,
        ]);

        return $response['accounts'] ?? [];
    }

    public function getTransactions(string $accessToken, string $startDate, string $endDate): array
    {
        $response = $this->plaidRequest('/transactions/get', [
            'access_token' => $accessToken,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'options' => [
                'count' => 100,
                'offset' => 0,
            ],
        ]);

        return $response['transactions'] ?? [];
    }

    protected function plaidRequest(string $endpoint, array $payload): array
    {
        if (! $this->hasCredentials()) {
            throw new RuntimeException('Plaid client credentials are not configured.');
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl() . $endpoint, array_merge([
            'client_id' => $this->clientId,
            'secret' => $this->secret,
        ], $payload));

        if ($response->failed()) {
            throw new RuntimeException('Plaid request failed: ' . $response->body());
        }

        return $response->json();
    }

    protected function hasCredentials(): bool
    {
        return filled($this->clientId) && filled($this->secret);
    }

    protected function baseUrl(): string
    {
        return match ($this->environment) {
            'production' => 'https://production.plaid.com',
            'development' => 'https://development.plaid.com',
            default => 'https://sandbox.plaid.com',
        };
    }
}
