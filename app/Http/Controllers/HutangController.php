<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use App\Models\Saldo;
use Exception;
use Illuminate\Http\Request;

class HutangController extends Controller
{
    function store(Request $request)
    {

        $rules = [
            "nama_stakeholder" => "required",
            "kontak_stakeholder" => "required",
            "tenggat_waktu" => "required|date_format:Y-m-d",
            "tanggal_kembali" => "required|date_format:Y-m-d|after:tenggat_waktu",
            "keterangan" => "required",
            "jumlah" => "required|numeric",
        ];

        $messages = [
            'required' => 'Lengkapi :attribute terlebih dahulu'
        ];

        $this->validate($request, $rules, $messages);

        $saldo = Saldo::first()->saldo;
        if ($request->jumlah > $saldo) {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Uang Perusahaan Tidak Cukup",
            ], 400);
        }

        $kas = new Hutang();
        $kode = "DEBT" . time();
        $kas->kode_transaksi = $kode;
        $kas->nama_stakeholder = $request->nama_stakeholder;
        $kas->kontak_stakeholder = $request->kontak_stakeholder;
        $kas->tenggat_waktu = $request->tenggat_waktu;
        $kas->tanggal_kembali = $request->tanggal_kembali;
        $kas->keterangan = $request->keterangan;
        $kas->jumlah = $request->jumlah;
        $kas->save();

        if ($kas) {
            $saldo = Saldo::first();
            return \Response::json([
                "http" => 200,
                "status" => "success",
                "message" => "Berhasil Menyimpan Transaksi Masuk",
                "data" => $kas,
                "saldo" => $saldo,
            ], 200);
        } else {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Gagal Menyimpan Transaksi Masuk",
            ], 400);
        }
    }

    #detail
    function detail($id)
    {
        $kas = Hutang::find($id);

        if ($kas != null) {
            $saldo = Saldo::first();
            return \Response::json([
                "http" => 200,
                "status" => "success",
                "message" => "Berhasil Mendapatkan Data Hutang",
                "saldo" => $saldo,
                "data" => $kas,
            ], 200);
        } else {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Gagal Mendapatkan Data Hutang",
            ], 400);
        }
    }


    function getAll()
    {
        $kas = Hutang::all();

        if ($kas != null) {
            $saldo = Saldo::first();
            return \Response::json([
                "http" => 200,
                "status" => "success",
                "message" => "Berhasil Mendapatkan Data Hutang",
                "saldo" => $saldo,
                "data" => $kas,
            ], 200);
        } else {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Gagal Mendapatkan Data Hutang",
            ], 400);
        }
    }


    #delete by id
    function delete($id)
    {
        $kas = Hutang::findOrFail($id);
        $kas->delete();

        if ($kas) {
            $saldo = Saldo::first();
            return \Response::json([
                "http" => 200,
                "status" => "success",
                "message" => "Berhasil Menghapus Data Hutang dan Mengurangi Kembali Saldo di Database",
                "data" => $kas,
                "saldo" => $saldo,
            ], 200);
        } else {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Gagal Menghapus Data Hutang dan Mengurangi Kembali Saldo di Database",
            ], 400);
        }
    }
}
