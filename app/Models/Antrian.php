<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    // use HasFactory;

    protected $fillable = ['tanggal', 'nomor', 'status'];

    public function getFormattedNomorAttribute()
    {
        // Ubah nomor menjadi format yang diinginkan, misalnya "A-001"
        return 'A-' . str_pad($this->attributes['nomor'], 3, '0', STR_PAD_LEFT);
    }

    public function getLatestAntrian($tanggal)
    {
        // Menggunakan Eloquent untuk mendapatkan nomor antrian terbaru
        $latestAntrian = Antrian::where('tanggal', $tanggal)
            ->where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->first();

        // Jika data ditemukan, kembalikan nomor antrian
        if ($latestAntrian) {
            return $latestAntrian->no_antrian;
        }

        // Jika tidak ada data yang ditemukan, kembalikan nilai default atau sesuai kebutuhan
        return 'N/A';
    }
}
