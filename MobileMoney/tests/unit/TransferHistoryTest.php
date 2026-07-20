<?php

use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class TransferHistoryTest extends CIUnitTestCase
{
    public function testTransferCreatesAReceiptEntryForRecipient(): void
    {
        $db = \Config\Database::connect();
        $db->query('DROP TABLE IF EXISTS db_transactions');
        $db->query('DROP TABLE IF EXISTS db_clients');
        $db->query('DROP TABLE IF EXISTS db_types_operation');
        $db->query('CREATE TABLE db_clients (id INTEGER PRIMARY KEY AUTOINCREMENT, numero_telephone TEXT NOT NULL UNIQUE, solde REAL DEFAULT 0)');
        $db->query('CREATE TABLE db_types_operation (id INTEGER PRIMARY KEY AUTOINCREMENT, nom TEXT NOT NULL)');
        $db->query('CREATE TABLE db_transactions (id INTEGER PRIMARY KEY AUTOINCREMENT, id_client INTEGER, id_type_operation INTEGER, montant REAL, frais REAL, numero_destination TEXT, date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP)');
        $db->query("INSERT INTO db_clients (id, numero_telephone, solde) VALUES (1, '0330000000', 1000.0)");
        $db->query("INSERT INTO db_clients (id, numero_telephone, solde) VALUES (2, '0370000000', 500.0)");
        $db->query("INSERT INTO db_types_operation (id, nom) VALUES (1, 'Transfert')");

        $controller = new class extends \App\Controllers\Client\DashboardController {
            public function exposePersistTransferTransactions(array $sender, array $recipient, float $amount, float $fee, string $numeroDestination, array $operation): void
            {
                $this->persistTransferTransactions($sender, $recipient, $amount, $fee, $numeroDestination, $operation);
            }
        };

        $controller->exposePersistTransferTransactions(
            ['id' => 1, 'numero_telephone' => '0330000000'],
            ['id' => 2, 'numero_telephone' => '0370000000'],
            100.0,
            2.0,
            '0370000000',
            ['id' => 1]
        );

        $rows = $db->table('db_transactions')->get()->getResultArray();

        $this->assertCount(2, $rows);
        $this->assertSame(1, (int) $rows[0]['id_client']);
        $this->assertSame(2, (int) $rows[1]['id_client']);
    }
}
