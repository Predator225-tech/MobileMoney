<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use App\Models\Client\ClientModel;

class AuthController extends BaseController
{
    public function index()
    {
        return view('auth/login');
    }

    public function login()
    {
        $numero = preg_replace('/\s+/', '', (string) $this->request->getPost('numero_telephone'));

        if ($numero === '') {
            return redirect()->back()->with('error', 'Veuillez entrer un numéro de téléphone.');
        }

        if (! preg_match('/^\d{10}$/', $numero)) {
            return redirect()->back()->with('error', 'Le numéro doit contenir 10 chiffres.');
        }

        $prefixes = array_map(static fn (array $row) => $row['code'], \Config\Database::connect()->table('prefixes')->get()->getResultArray());
        $isValidPrefix = false;

        foreach ($prefixes as $prefix) {
            if (str_starts_with($numero, $prefix)) {
                $isValidPrefix = true;
                break;
            }
        }

        if (! $isValidPrefix) {
            return redirect()->back()->with('error', 'Le numéro ne correspond à aucun préfixe autorisé.');
        }

        $clientModel = new ClientModel();
        $client = $clientModel->where('numero_telephone', $numero)->first();

        if (! $client) {
            $clientModel->insert([
                'numero_telephone' => $numero,
                'solde' => 0,
            ]);

            $client = $clientModel->where('numero_telephone', $numero)->first();
        }

        session()->set([
            'client_id' => $client['id'],
            'numero' => $client['numero_telephone'],
            'isLoggedIn' => true,
            'role' => 'client',
        ]);

        return redirect()->to('/client/dashboard')->with('success', 'Connexion réussie.');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/auth')->with('success', 'Vous avez été déconnecté.');
    }
}