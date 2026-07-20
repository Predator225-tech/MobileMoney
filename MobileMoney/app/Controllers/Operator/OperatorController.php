<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;

class OperatorController extends BaseController
{
    public function index()
    {
        return view('admin/dashboard');
    }

    public function showGains()
    {
        $breakdown = $this->getGainBreakdown();
        $totalGains = 0.0;

        foreach ($breakdown as $value) {
            $totalGains += (float) $value;
        }

        return view('admin/gains', [
            'total_gains' => $totalGains,
            'gains_by_operation' => $breakdown,
        ]);
    }

    protected function getGainBreakdown(): array
    {
        $db = \Config\Database::connect();

        $transactionRows = $db->table('transactions t')
            ->select('types_operation.nom AS operation_nom, SUM(COALESCE(t.frais, 0)) AS total_frais')
            ->join('types_operation', 'types_operation.id = t.id_type_operation', 'left')
            ->whereIn('types_operation.nom', ['Retrait', 'Transfert'])
            ->groupBy('types_operation.id')
            ->get()
            ->getResultArray();

        $barmeRows = $db->table('tranches_frais tf')
            ->select('types_operation.nom AS operation_nom, SUM(COALESCE(tf.frais_fixe, 0)) AS total_frais')
            ->join('types_operation', 'types_operation.id = tf.id_type_operation', 'left')
            ->whereIn('types_operation.nom', ['Retrait', 'Transfert'])
            ->groupBy('types_operation.id')
            ->get()
            ->getResultArray();

        $breakdown = [
            'Retrait' => 0.0,
            'Transfert' => 0.0,
        ];

        foreach ($transactionRows as $row) {
            $operationName = trim((string) ($row['operation_nom'] ?? ''));
            if ($operationName === '' || ! array_key_exists($operationName, $breakdown)) {
                continue;
            }

            $breakdown[$operationName] = (float) ($row['total_frais'] ?? 0);
        }

        foreach ($barmeRows as $row) {
            $operationName = trim((string) ($row['operation_nom'] ?? ''));
            if ($operationName === '' || ! array_key_exists($operationName, $breakdown)) {
                continue;
            }

            $barmeValue = (float) ($row['total_frais'] ?? 0);
            if ($barmeValue > $breakdown[$operationName]) {
                $breakdown[$operationName] = $barmeValue;
            }
        }

        return $breakdown;
    }
}