<?php

namespace App\Models\Operator;

use CodeIgniter\Model;

class TrancheFraisModel extends Model
{
    protected $table = 'tranches_frais';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_type_operation',
        'montant_min',
        'montant_max',
        'frais_fixe',
    ];
}