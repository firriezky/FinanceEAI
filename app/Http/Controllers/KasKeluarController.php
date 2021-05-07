<?php

namespace App\Http\Controllers;

use App\Models\KasKeluar;
use App\Models\Saldo;
use Exception;
use Illuminate\Http\Request;

class KasKeluarController extends Controller
{
    #store
    function store(Request $request)
    {
        $rules = [
            "type" => "required|numeric",
            "keterangan" => "required",
            "rincian" => "required",
            "bukti_transaksi" => "required",
            "jumlah" => "required|numeric",
        ];

        $messages = [
            'required' => 'Lengkapi :attribute terlebih dahulu'
        ];

        $this->validate($request, $rules, $messages);

        $saldo = Saldo::first();

        if ($request->jumlah >= $saldo->saldo) {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Saldo Perusahaan Tidak Cukup",
            ], 400);
        }

        $kas = new KasKeluar();
        $path_db = "";
        if ($request->hasFile('bukti_transaksi')) {
            $file = $request->file('bukti_transaksi');
            $extension = $file->getClientOriginalExtension(); // you can also use file name
            $fileName = time() . '.' . $extension;

            $init_path = "/photo/outcome/";
            $path_db = $init_path . "$fileName";
            $path_save = public_path() . $init_path;
            $file->move($path_save, $fileName);
        }


        $kode = "OUT" . time();
        $kas->type = $request->type;
        $kas->kode_transaksi = $kode . "_" . $request->type;
        $kas->keterangan = $request->keterangan;
        $kas->bukti_transaksi = $path_db;
        $kas->rincian = $request->rincian;
        $kas->jumlah = $request->jumlah;
        $kas->save();

        if ($kas) {

            $saldo = Saldo::first();

            return \Response::json([
                "http" => 200,
                "status" => "success",
                "message" => "Berhasil Menyimpan Data Pengeluaran",
                "data" => $kas,
                "saldo" => $saldo,
            ], 200);
        } else {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Gagal Menyimpan Data Pengeluaran",
            ], 400);
        }
    }

    #delete by id
    function delete($id)
    {
        $kas = KasKeluar::findOrFail($id);
        
        $file_path = public_path().$kas->bukti_transaksi;
        if (file_exists($file_path)) {
            try{
                unlink($file_path);
            }catch(Exception $e){
                //Do Nothing
            }
        }

        $kas->delete();

        if ($kas) {
            $saldo = Saldo::first();

            return \Response::json([
                "http" => 200,
                "status" => "success",
                "message" => "Berhasil Menghapus Data Pengeluaran dan Menambah Kembali Saldo di Database",
                "data" => $kas,
                "saldo" => $saldo,
            ], 200);
        } else {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Gagal Menghapus Data Pengeluaran dan Menambah Kembali Saldo di Database",
            ], 400);
        }
    }

    #get all
    function getAll()
    {
        $kas = KasKeluar::all();
        
        if ($kas) {
            $saldo = Saldo::first();
            return \Response::json([
                "http" => 200,
                "status" => "success",
                "message" => "Berhasil Mendapatkan Data Pengeluaran",
                "saldo" => $saldo,
                "data" => $kas,
            ], 200);
        } else {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Gagal Mendapatkan Data Pengeluaran",
            ], 400);
        }
    }

    #detail
    function detail($id)
    {
        $kas = KasKeluar::findOrFail($id);

        if ($kas) {
            $saldo = Saldo::first();
            return \Response::json([
                "http" => 200,
                "status" => "success",
                "message" => "Berhasil Mendapatkan Data Pengeluaran",
                "saldo" => $saldo,
                "data" => $kas,
            ], 200);
        } else {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Gagal Mendapatkan Data Pengeluaran",
            ], 400);
        }
    }
}
