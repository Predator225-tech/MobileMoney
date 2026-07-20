<?php

namespace App\Models\Client;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $allowedFields = ['numero_telephone', 'solde'];
}