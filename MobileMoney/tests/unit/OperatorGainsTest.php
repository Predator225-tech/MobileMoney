<?php

use App\Controllers\Operator\OperatorController;
use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class OperatorGainsTest extends CIUnitTestCase
{
    public function testGainBreakdownReturnsRetraitAndTransfertTotals(): void
    {
        $db = \Config\Database::connect();
        $db->query('DROP TABLE IF EXISTS db_transactions');
        $db->query('DROP TABLE IF EXISTS db_types_operation');
        $db->query('CREATE TABLE db_types_operation (id INTEGER PRIMARY KEY AUTOINCREMENT, nom TEXT NOT NULL)');
        $db->query('CREATE TABLE db_transactions (id INTEGER PRIMARY KEY AUTOINCREMENT, id_type_operation INTEGER, frais REAL DEFAULT 0, date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP)');
        $db->query("INSERT INTO db_types_operation (id, nom) VALUES (1, 'Retrait')");
        $db->query("INSERT INTO db_types_operation (id, nom) VALUES (2, 'Transfert')");
        $db->query("INSERT INTO db_transactions (id_type_operation, frais) VALUES (1, 50.0)");
        $db->query("INSERT INTO db_transactions (id_type_operation, frais) VALUES (2, 20.0)");
        $db->query("INSERT INTO db_transactions (id_type_operation, frais) VALUES (1, 10.0)");

        $controller = new class extends OperatorController {
            public function exposeGainBreakdown(): array
            {
                return $this->getGainBreakdown();
            }
        };

        $breakdown = $controller->exposeGainBreakdown();

        $this->assertSame(60.0, (float) ($breakdown['Retrait'] ?? 0));
        $this->assertSame(20.0, (float) ($breakdown['Transfert'] ?? 0));
    }
}
