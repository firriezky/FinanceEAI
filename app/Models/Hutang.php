<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hutang extends Model
{
    use HasFactory;
    protected $fillable = [
        "kode_transaksi",
        "nama_stakeholder",
        "kontak_stakeholder",
        "tenggat_waktu",
        "tanggal_kembali",
        "keterangan",
        "jumlah"
    ];


}
