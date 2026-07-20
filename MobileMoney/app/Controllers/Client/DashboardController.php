<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Client\ClientModel;
use App\Models\TransactionModel;

class DashboardController extends BaseController
{
    private ClientModel $clientModel;
    private TransactionModel $transactionModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->transactionModel = new TransactionModel();
    }

    public function index()
    {
        $client = $this->requireClient();

        return view('client/dashboard', [
            'client' => $client,
            'balance' => (float) $client['solde'],
            'history' => $this->getHistory((int) $client['id']),
        ]);
    }

    public function deposit()
    {
        $this->processSingleOperation('Dépôt');
    }

    public function withdraw()
    {
        $this->processSingleOperation('Retrait');
    }

    public function transfer()
    {
        $client = $this->requireClient();
        $numeroDestination = preg_replace('/\s+/', '', (string) $this->request->getPost('numero_destination'));
        $montant = $this->request->getPost('montant');

        if ($numeroDestination === '' || ! preg_match('/^\d{9}$/', $numeroDestination)) {
            return redirect()->back()->with('error', 'Le numéro du destinataire est invalide.');
        }

        if (! is_numeric($montant) || (float) $montant <= 0) {
            return redirect()->back()->with('error', 'Le montant du transfert est invalide.');
        }

        $recipient = $this->clientModel->where('numero_telephone', $numeroDestination)->first();
        if (! $recipient) {
            return redirect()->back()->with('error', 'Le destinataire doit d’abord exister dans le système.');
        }

        $operation = $this->getOperationType('Transfert');
        if (! $operation) {
            return redirect()->back()->with('error', 'Type d’opération transfert introuvable.');
        }

        $amount = (float) $montant;
        $fee = $this->resolveFee((int) $operation['id'], $amount);
        $totalDebit = $amount + $fee;

        if ((float) $client['solde'] < $totalDebit) {
            return redirect()->back()->with('error', 'Solde insuffisant pour effectuer ce transfert.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->clientModel->update((int) $client['id'], ['solde' => (float) $client['solde'] - $totalDebit]);
        $this->clientModel->update((int) $recipient['id'], ['solde' => (float) $recipient['solde'] + $amount]);

        $this->transactionModel->insert([
            'id_client' => (int) $client['id'],
            'id_type_operation' => (int) $operation['id'],
            'montant' => $amount,
            'frais' => $fee,
            'numero_destination' => $numeroDestination,
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Le transfert a échoué.');
        }

        return redirect()->to('/index.php/client/dashboard')->with('success', 'Transfert effectué avec succès.');
    }

    private function processSingleOperation(string $operationName)
    {
        $client = $this->requireClient();
        $montant = $this->request->getPost('montant');

        if (! is_numeric($montant) || (float) $montant <= 0) {
            return redirect()->back()->with('error', 'Le montant est invalide.');
        }

        $operation = $this->getOperationType($operationName);
        if (! $operation) {
            return redirect()->back()->with('error', 'Type d’opération introuvable.');
        }

        $amount = (float) $montant;
        $fee = $this->resolveFee((int) $operation['id'], $amount);
        $newBalance = $this->applyBalanceChange((float) $client['solde'], $amount, $fee, $operationName);

        if ($newBalance === null) {
            return redirect()->back()->with('error', 'Solde insuffisant.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->clientModel->update((int) $client['id'], ['solde' => $newBalance]);
        $this->transactionModel->insert([
            'id_client' => (int) $client['id'],
            'id_type_operation' => (int) $operation['id'],
            'montant' => $amount,
            'frais' => $fee,
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'L’opération a échoué.');
        }

        return redirect()->to('/client/dashboard')->with('success', ucfirst(strtolower($operationName)) . ' effectué avec succès.');
    }

    private function applyBalanceChange(float $currentBalance, float $amount, float $fee, string $operationName): ?float
    {
        if ($operationName === 'Dépôt') {
            return $currentBalance + ($amount - $fee);
        }

        if ($operationName === 'Retrait') {
            $newBalance = $currentBalance - ($amount + $fee);
            return $newBalance < 0 ? null : $newBalance;
        }

        return null;
    }

    private function resolveFee(int $typeOperationId, float $amount): float
    {
        $row = \Config\Database::connect()->table('tranches_frais')
            ->where('id_type_operation', $typeOperationId)
            ->where('montant_min <=', $amount)
            ->where('montant_max >=', $amount)
            ->orderBy('montant_min', 'DESC')
            ->get()
            ->getRowArray();

        return $row ? (float) $row['frais_fixe'] : 0.0;
    }

    private function getOperationType(string $name): ?array
    {
        $types = \Config\Database::connect()->table('types_operation')->get()->getResultArray();

        foreach ($types as $type) {
            if ($this->normalize($type['nom']) === $this->normalize($name)) {
                return $type;
            }
        }

        return null;
    }

    private function getHistory(int $clientId): array
    {
        return \Config\Database::connect()->table('transactions')
            ->select('transactions.*, types_operation.nom AS operation_nom')
            ->join('types_operation', 'types_operation.id = transactions.id_type_operation', 'left')
            ->where('transactions.id_client', $clientId)
            ->orderBy('transactions.date_transaction', 'DESC')
            ->limit(20)
            ->get()
            ->getResultArray();
    }

    private function requireClient(): array
    {
        $clientId = (int) session()->get('client_id');

        if ($clientId <= 0) {
            redirect()->to('/auth')->with('error', 'Veuillez vous connecter.')->send();
            exit;
        }

        $client = $this->clientModel->find($clientId);
        if (! $client) {
            session()->destroy();
            redirect()->to('/auth')->with('error', 'Session invalide.')->send();
            exit;
        }

        return $client;
    }

    private function normalize(string $value): string
    {
        $value = strtolower(trim($value));
        $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);

        return $transliterated === false ? $value : $transliterated;
    }
}