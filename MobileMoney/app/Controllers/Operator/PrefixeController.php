<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;
use App\Models\Operator\PrefixeModel;

class PrefixeController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new PrefixeModel();
    }

    // Affiche la liste et le formulaire
    public function index()
    {
        $data['prefixes'] = $this->model->findAll();
        return view('admin/prefixes', $data);
    }

    // Enregistre un nouveau préfixe
    public function store()
    {
        $code = trim((string) $this->request->getPost('code'));

        if ($code === '') {
            return redirect()->back()->with('error', 'Le code est obligatoire.');
        }

        if (! preg_match('/^\d{3}$/', $code)) {
            return redirect()->back()->with('error', 'Le préfixe doit contenir exactement 3 chiffres.');
        }

        try {
            $this->model->save(['code' => $code]);
            return redirect()->to('/admin/prefixes')->with('success', 'Préfixe ajouté avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ce préfixe existe déjà.');
        }
    }

    // Supprime un préfixe
    public function delete($id = null)
    {
        if ($id && $this->model->find($id)) {
            $this->model->delete($id);
            return redirect()->to('/admin/prefixes')->with('success', 'Supprimé avec succès.');
        }
        return redirect()->to('/admin/prefixes')->with('error', 'Erreur lors de la suppression.');
    }
}