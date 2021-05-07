<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\SaldoHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoryTransaksiController extends Controller
{

    function getHistory($param)
    {

        $data = SaldoHistory::all();

        if ($param == "all") {
            $data = SaldoHistory::all();
        }

        if ($param == "income") {
            $data = DB::table("saldo_history")->where('type', 'like', '%INCOME%')->get();
        }

        if ($param == "outcome") {
            $data = DB::table("saldo_history")->where('type', 'like', '%OUTCOME%')->get();
        }

        if ($data) {
            return \Response::json([
                "http" => 200,
                "status" => "success",
                "message" => "Berhasil Mendapatkan Data Transaksi",
                "type" => "$param",
                "data" => $data,
            ], 200);
        } else {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Gagal Mendapatkan Data Transaksi",
                "type" => "$param",
            ], 400);
        }
    }
}
