<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNumeroDestinationToTransactions extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $fields = $db->query("PRAGMA table_info('transactions')")->getResultArray();
        $hasColumn = false;

        foreach ($fields as $field) {
            if (($field['name'] ?? '') === 'numero_destination') {
                $hasColumn = true;
                break;
            }
        }

        if (! $hasColumn) {
            $db->query("ALTER TABLE transactions ADD COLUMN numero_destination TEXT");
        }
    }

    public function down()
    {
        // SQLite does not support dropping columns in a portable way.
        // The migration is intentionally a no-op for rollback safety.
    }
}
