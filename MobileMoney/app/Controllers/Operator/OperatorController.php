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
        $db = \Config\Database::connect();
        // Calcul des gains (Retrait = 2, Transfert = 3)
        $builder = $db->table('transactions');
        $builder->selectSum('frais');
        $builder->whereIn('id_type_operation', [2, 3]);
        $data['total_gains'] = $builder->get()->getRow()->frais;

        return view('admin/gains', $data);
    }
}