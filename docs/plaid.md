# Plaid Integration

FlowFi uses [Plaid](https://plaid.com/) to connect user bank accounts.

## Configuration

Set the following environment variables in your `.env` file:

```
PLAID_CLIENT_ID=your-client-id
PLAID_SECRET=your-secret
PLAID_ENV=sandbox   # or development, production
PLAID_WEBHOOK_SECRET=your-webhook-secret
```

These values are consumed by `config/plaid.php` and used by the `PlaidService`.

## Endpoints

- `GET /plaid/link-token` – Create a Link token for the authenticated user. Requires `auth:sanctum`.
- `POST /plaid/exchange` – Exchange a public token for an access token. Requires `auth:sanctum` and persists the token server-side.
- `POST /plaid/webhook` – Receive webhook events from Plaid. Events are validated against the configured webhook secret and supported webhook types.

## Services

`app/Services/PlaidService.php` wraps the Plaid PHP client and provides helpers to:

- create Link tokens
- exchange public tokens for access tokens
- fetch account data
- retrieve transactions
