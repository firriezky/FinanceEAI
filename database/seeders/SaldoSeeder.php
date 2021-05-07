<?php

namespace Database\Seeders;

use App\Models\Saldo;
use Illuminate\Database\Seeder;

class SaldoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $saldo = new Saldo();
       $saldo->saldo = 100000000;
       $saldo->save();
    }
}
