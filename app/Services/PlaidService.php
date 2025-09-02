<?php

namespace App\Services;

class PlaidService
{
    public function createLinkToken(): string
    {
        return 'mock-link-token';
    }

    public function exchangePublicToken(string $publicToken): string
    {
        return 'mock-access-token';
    }

    public function getAccounts(string $accessToken): array
    {
        return [];
    }

    public function getTransactions(string $accessToken, string $startDate, string $endDate): array
    {
        return [];
    }
}
