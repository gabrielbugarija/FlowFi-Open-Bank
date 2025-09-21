<?php

namespace App\Support\Database;

use Illuminate\Support\Facades\DB;

class DateExpressions
{
    public static function monthYear(string $column): string
    {
        $driver = DB::getDriverName();

        return match ($driver) {
            'sqlite' => "strftime('%Y-%m', $column)",
            'pgsql' => "to_char($column, 'YYYY-MM')",
            'sqlsrv' => "FORMAT($column, 'yyyy-MM')",
            'mysql', 'mariadb' => "DATE_FORMAT($column, '%Y-%m')",
            default => "DATE_FORMAT($column, '%Y-%m')",
        };
    }
}
