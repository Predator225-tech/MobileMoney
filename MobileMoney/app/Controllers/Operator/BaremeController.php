<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;
use App\Models\Operator\TrancheFraisModel;

class BaremeController extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new TrancheFraisModel();
    }

    public function index($typeId = null)
    {
        $db = \Config\Database::connect();

        $typesOperations = $db->table('types_operation')
            ->orderBy('id')
            ->get()
            ->getResultArray();

        if (empty($typesOperations)) {
            return view('admin/baremes', [
                'types_operations' => [],
                'selected_type' => null,
                'baremes' => [],
            ]);
        }

        $selectedTypeId = (int) ($typeId ?? $this->request->getGet('type_operation') ?? $typesOperations[0]['id']);
        $selectedType = null;

        foreach ($typesOperations as $typeOperation) {
            if ((int) $typeOperation['id'] === $selectedTypeId) {
                $selectedType = $typeOperation;
                break;
            }
        }

        if ($selectedType === null) {
            $selectedType = $typesOperations[0];
            $selectedTypeId = (int) $selectedType['id'];
        }

        $baremes = $db->table('tranches_frais')
            ->select('tranches_frais.*, types_operation.nom AS type_operation_nom')
            ->join('types_operation', 'types_operation.id = tranches_frais.id_type_operation', 'left')
            ->where('tranches_frais.id_type_operation', $selectedTypeId)
            ->orderBy('tranches_frais.id_type_operation', 'ASC')
            ->orderBy('tranches_frais.montant_min', 'ASC')
            ->get()
            ->getResultArray();

        return view('admin/baremes', [
            'types_operations' => $typesOperations,
            'selected_type' => $selectedType,
            'baremes' => $baremes,
        ]);
    }

    public function store()
    {
        return $this->saveBareme();
    }

    public function update($id = null)
    {
        return $this->saveBareme($id);
    }

    public function delete($id = null)
    {
        $bareme = $id ? $this->model->find($id) : null;

        if (! $bareme) {
            return redirect()->back()->with('error', 'Barème introuvable.');
        }

        $this->model->delete($id);

        return redirect()->to('/admin/baremes/' . $bareme['id_type_operation'])->with('success', 'Barème supprimé avec succès.');
    }

    private function saveBareme($id = null)
    {
        $idTypeOperation = (int) $this->request->getPost('id_type_operation');
        $montantMin = $this->request->getPost('montant_min');
        $montantMax = $this->request->getPost('montant_max');
        $fraisFixe = $this->request->getPost('frais_fixe');

        if ($idTypeOperation <= 0) {
            return redirect()->back()->with('error', 'Le type d’opération est obligatoire.');
        }

        if ($montantMin === null || $montantMin === '' || ! is_numeric($montantMin)) {
            return redirect()->back()->with('error', 'Le montant minimum est obligatoire.');
        }

        if ($montantMax === null || $montantMax === '' || ! is_numeric($montantMax)) {
            return redirect()->back()->with('error', 'Le montant maximum est obligatoire.');
        }

        $montantMin = (float) $montantMin;
        $montantMax = (float) $montantMax;

        if ($montantMin > $montantMax) {
            return redirect()->back()->with('error', 'Le montant minimum doit être inférieur ou égal au montant maximum.');
        }

        if ($fraisFixe === null || $fraisFixe === '' || ! is_numeric($fraisFixe)) {
            return redirect()->back()->with('error', 'Le frais fixe est obligatoire.');
        }

        if ($this->hasOverlap($idTypeOperation, $montantMin, $montantMax, $id ? (int) $id : null)) {
            return redirect()->back()->with('error', 'Cet intervalle chevauche déjà un barème existant pour ce type.');
        }

        $payload = [
            'id_type_operation' => $idTypeOperation,
            'montant_min'       => $montantMin,
            'montant_max'       => $montantMax,
            'frais_fixe'        => (float) $fraisFixe,
            'frais_pourcentage' => 0,
        ];

        if ($id) {
            $this->model->update($id, $payload);
        } else {
            $this->model->insert($payload);
        }

        return redirect()->to('/admin/baremes/' . $idTypeOperation)->with('success', $id ? 'Barème mis à jour avec succès.' : 'Barème ajouté avec succès.');
    }

    private function hasOverlap(int $typeId, float $montantMin, float $montantMax, ?int $excludeId = null): bool
    {
        $builder = \Config\Database::connect()->table('tranches_frais');
        $builder->where('id_type_operation', $typeId);

        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }

        $rows = $builder->get()->getResultArray();

        foreach ($rows as $row) {
            $existingMin = (float) $row['montant_min'];
            $existingMax = (float) $row['montant_max'];

            if (! ($montantMax < $existingMin || $montantMin > $existingMax)) {
                return true;
            }
        }

        return false;
    }
}