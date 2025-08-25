# Plaid Integration

FlowFi uses [Plaid](https://plaid.com/) to connect user bank accounts.

## Configuration

Set the following environment variables in your `.env` file:

```
PLAID_CLIENT_ID=your-client-id
PLAID_SECRET=your-secret
PLAID_ENV=sandbox   # or development, production
```

These values are consumed by `config/plaid.php` and used by the `PlaidService`.

## Endpoints

- `GET /plaid/link-token` – Create a Link token for the authenticated user.
- `POST /plaid/webhook` – Receive webhook events from Plaid.

## Services

`app/Services/PlaidService.php` wraps the Plaid PHP client and provides helpers to:

- create Link tokens
- exchange public tokens for access tokens
- fetch account data
- retrieve transactions
