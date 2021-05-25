<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piutang extends Model
{
    use HasFactory;
    protected $fillable = [
        "kode_transaksi",
        "nama_peminjam",
        "kontak_peminjam",
        "tenggat_waktu",
        "tanggal_kembali",
        "keterangan",
        "jumlah"
    ];
}
