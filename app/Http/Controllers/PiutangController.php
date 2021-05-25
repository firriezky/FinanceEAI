<?php

namespace App\Http\Controllers;

use App\Models\Hutang;
use App\Models\Piutang;
use App\Models\Saldo;
use Exception;
use Illuminate\Http\Request;

class PiutangController extends Controller
{
    function store(Request $request)
    {

        $rules = [
            "nama_peminjam" => "required",
            "kontak_peminjam" => "required",
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

        $kas = new Piutang();
        $kode = "CRED" . time();
        $kas->kode_transaksi = $kode;
        $kas->nama_peminjam = $request->nama_peminjam;
        $kas->kontak_peminjam = $request->kontak_peminjam;
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
                "message" => "Berhasil Menyimpan Catatan Piutang",
                "data" => $kas,
                "saldo" => $saldo,
            ], 200);
        } else {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Gagal Menyimpan Catatan Piutang",
            ], 400);
        }
    }

    #detail
    function detail($id)
    {
        $kas = Piutang::find($id);

        if ($kas != null) {
            $saldo = Saldo::first();
            return \Response::json([
                "http" => 200,
                "status" => "success",
                "message" => "Berhasil Mendapatkan Data Piutang",
                "saldo" => $saldo,
                "data" => $kas,
            ], 200);
        } else {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Gagal Mendapatkan Data Piutang",
            ], 400);
        }
    }


    function getAll()
    {
        $kas = Piutang::all();

        if ($kas != null) {
            $saldo = Saldo::first();
            return \Response::json([
                "http" => 200,
                "status" => "success",
                "message" => "Berhasil Mendapatkan Data Piutang",
                "saldo" => $saldo,
                "data" => $kas,
            ], 200);
        } else {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Gagal Mendapatkan Data Piutang",
            ], 400);
        }
    }


    #delete by id
    function delete($id)
    {
        $kas = Piutang::findOrFail($id);
        $kas->delete();

        if ($kas) {
            $saldo = Saldo::first();
            return \Response::json([
                "http" => 200,
                "status" => "success",
                "message" => "Berhasil Menghapus Data Piutang dan Menambah Kembali Saldo di Database",
                "data" => $kas,
                "saldo" => $saldo,
            ], 200);
        } else {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Gagal Menghapus Data Piutang dan Menambah Kembali Saldo di Database",
            ], 400);
        }
    }
}
