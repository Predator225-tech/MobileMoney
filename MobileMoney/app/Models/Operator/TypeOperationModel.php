<?php

namespace App\Models\Operator;

use CodeIgniter\Model;

class TypeOperationModel extends Model
{
    protected $table = 'types_operation';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nom'];
}