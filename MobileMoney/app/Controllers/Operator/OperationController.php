<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;
use App\Models\Operator\TypeOperationModel;

class OperationController extends BaseController
{
    protected TypeOperationModel $model;

    public function __construct()
    {
        $this->model = new TypeOperationModel();
    }

    public function index()
    {
        return view('admin/operations', [
            'operations' => $this->model->orderBy('id', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $nom = trim((string) $this->request->getPost('nom'));

        if ($nom === '') {
            return redirect()->back()->with('error', 'Le nom de l’opération est obligatoire.');
        }

        $this->model->insert(['nom' => $nom]);

        return redirect()->to('/admin/operations')->with('success', 'Opération créée avec succès.');
    }

    public function update($id = null)
    {
        if (! $id || ! $this->model->find($id)) {
            return redirect()->back()->with('error', 'Opération introuvable.');
        }

        $nom = trim((string) $this->request->getPost('nom'));

        if ($nom === '') {
            return redirect()->back()->with('error', 'Le nom de l’opération est obligatoire.');
        }

        $this->model->update($id, ['nom' => $nom]);

        return redirect()->to('/admin/operations')->with('success', 'Opération mise à jour avec succès.');
    }

    public function delete($id = null)
    {
        $db = \Config\Database::connect();
        $operation = $id ? $this->model->find($id) : null;

        if (! $operation) {
            return redirect()->back()->with('error', 'Opération introuvable.');
        }

        $inUse = $db->table('tranches_frais')->where('id_type_operation', $id)->countAllResults() > 0
            || $db->table('transactions')->where('id_type_operation', $id)->countAllResults() > 0;

        if ($inUse) {
            return redirect()->back()->with('error', 'Impossible de supprimer cette opération car elle est utilisée.');
        }

        $this->model->delete($id);

        return redirect()->to('/admin/operations')->with('success', 'Opération supprimée avec succès.');
    }
}