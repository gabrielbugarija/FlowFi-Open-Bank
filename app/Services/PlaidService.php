<?php

namespace App\Services;

use GuzzleHttp\Client;
use Plaid\Api\PlaidApi;
use Plaid\Configuration;
use Plaid\Model\AccountsGetRequest;
use Plaid\Model\ItemPublicTokenExchangeRequest;
use Plaid\Model\LinkTokenCreateRequest;
use Plaid\Model\TransactionsGetRequest;

class PlaidService
{
    protected PlaidApi $client;

    public function __construct()
    {
        $config = new Configuration();
        $config->setClientId(config('plaid.client_id'));
        $config->setSecret(config('plaid.secret'));
        $config->setEnvironment(config('plaid.env'));

        $this->client = new PlaidApi(new Client(), $config);
    }

    public function createLinkToken(): string
    {
        $request = new LinkTokenCreateRequest([
            'user' => ['client_user_id' => uniqid()],
            'client_name' => config('app.name', 'Laravel'),
            'products' => ['transactions'],
            'country_codes' => ['US'],
            'language' => 'en',
        ]);

        $response = $this->client->linkTokenCreate($request);

        return $response->getLinkToken();
    }

    public function exchangePublicToken(string $publicToken): string
    {
        $request = new ItemPublicTokenExchangeRequest(['public_token' => $publicToken]);
        $response = $this->client->itemPublicTokenExchange($request);

        return $response->getAccessToken();
    }

    public function getAccounts(string $accessToken)
    {
        $request = new AccountsGetRequest(['access_token' => $accessToken]);
        $response = $this->client->accountsGet($request);

        return $response->getAccounts();
    }

    public function getTransactions(string $accessToken, string $startDate, string $endDate)
    {
        $request = new TransactionsGetRequest([
            'access_token' => $accessToken,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        $response = $this->client->transactionsGet($request);

        return $response->getTransactions();
    }
}

