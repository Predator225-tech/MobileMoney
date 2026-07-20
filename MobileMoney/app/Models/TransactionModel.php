<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_client',
        'id_type_operation',
        'montant',
        'frais',
        'numero_destination',
        'date_transaction',
    ];
}