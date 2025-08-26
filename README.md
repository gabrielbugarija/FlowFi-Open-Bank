# FlowFi-Open-Bank

FlowFi is a modern open-banking-ready budgeting application built with Laravel. It helps users track income, expenses, budgets, and accounts through a clean and extensible backend.

## Plaid Integration

Plaid support is provided via a dedicated service and controller that expose endpoints for creating Link tokens, exchanging public tokens, and retrieving account and transaction data. See [docs/plaid.md](docs/plaid.md) for configuration and usage details.

## Getting Started

1. Install PHP dependencies:
   ```bash
   composer install
   ```
2. Copy `.env.example` to `.env` and configure database and Plaid credentials.
3. Run migrations:
   ```bash
   php artisan migrate
   ```
4. Start the development server:
   ```bash
   php artisan serve
   ```

## Testing

Run the test suite:
```bash
composer test
```

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).
