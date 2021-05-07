<?php

namespace App\Http\Controllers;

use App\Models\KasMasuk;
use App\Models\Saldo;
use Exception;
use Illuminate\Http\Request;

class KasMasukController extends Controller
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

        $kas = new KasMasuk();
        $path_db = "";
        if ($request->hasFile('bukti_transaksi')) {
            $file = $request->file('bukti_transaksi');
            $extension = $file->getClientOriginalExtension(); // you can also use file name
            $fileName = time() . '.' . $extension;

            $init_path = "/photo/income/";
            $path_db = $init_path . "$fileName";
            $path_save = public_path() . $init_path;
            $file->move($path_save, $fileName);
        }


        $kode = "INC" . time();
        $kas->type = $request->type;
        $kas->kode_transaksi = $kode . "_" . $request->type;
        $kas->keterangan = $request->keterangan;
        $kas->bukti_transaksi = $path_db;
        $kas->rincian = $request->rincian;
        $kas->jumlah = $request->jumlah;
        $kas->save();

        if ($kas) {

            // //nambah jumlah saldo di database
            // $saldo = Saldo::first();
            // $saldo->saldo = $saldo->saldo + $kas->jumlah;
            // $saldo->save();

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

    #delete by id
    function delete($id)
    {
        $kas = KasMasuk::findOrFail($id);
        
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

            // $saldo = Saldo::first();
            // $saldo->saldo = $saldo->saldo - $kas->jumlah;
            // $saldo->save();

            $saldo = Saldo::first();

            return \Response::json([
                "http" => 200,
                "status" => "success",
                "message" => "Berhasil Menghapus Transaksi Masuk dan Mengurangi Saldo di Database",
                "data" => $kas,
                "saldo" => $saldo,
            ], 200);
        } else {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Gagal Menghapus Transaksi Masuk dan Mengurangi Saldo di Database",
            ], 400);
        }
    }

    #get all
    function getAll()
    {
        $kas = KasMasuk::all();
        
        if ($kas) {
            $saldo = Saldo::first();
            return \Response::json([
                "http" => 200,
                "status" => "success",
                "message" => "Berhasil Mendapatkan Data Transaksi",
                "saldo" => $saldo,
                "data" => $kas,
            ], 200);
        } else {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Gagal Mendapatkan Data Transaksi Masuk",
            ], 400);
        }
    }

    #detail
    function detail($id)
    {
        $kas = KasMasuk::findOrFail($id);

        if ($kas) {
            $saldo = Saldo::first();
            return \Response::json([
                "http" => 200,
                "status" => "success",
                "message" => "Berhasil Mendapatkan Data Transaksi Masuk",
                "saldo" => $saldo,
                "data" => $kas,
            ], 200);
        } else {
            return \Response::json([
                "http" => 400,
                "status" => "error",
                "message" => "Gagal Mendapatkan Data Transaksi Masuk",
            ], 400);
        }
    }
}
