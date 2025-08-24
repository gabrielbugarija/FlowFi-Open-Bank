
{{-- resources/views/components/layout.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Budget App' }}</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Budget App</h1>
            <nav class="nav">
                <a href="{{ route('dashboard') }}">
                    <svg class="icon" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                        <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('accounts.index') }}">
                    <svg class="icon" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                        <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z"/>
                    </svg>
                    Accounts
                </a>
                <a href="{{ route('transactions.index') }}">
                    <svg class="icon" viewBox="0 0 24 24" style="width: 18px; height: 18px;">
                        <path d="M9 11H7v6h2v-6zm4 0h-2v6h2v-6zm4 0h-2v6h2v-6zm2.99-5L12 2 4.01 6v2h15.98V6z"/>
                    </svg>
                    Transactions
                </a>
            </nav>
        </header>

        <main class="main-content fade-in">
            {{ $slot }}
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card, .account-item, .budget-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in');
            });
        });
    </script>
</body>
</html>