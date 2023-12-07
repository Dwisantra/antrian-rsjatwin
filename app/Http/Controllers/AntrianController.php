<?php

namespace App\Http\Controllers;

use \App\Events\AntrianUpdated;
use \App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AntrianController extends Controller
{
    public function getAntrian()
    {
        $today = now()->toDateString();

        $antrianData = Antrian::whereDate('created_at', $today)->get();

        return DataTables::of($antrianData)
        ->addColumn('formatted_nomor', function ($antrian) {
            return 'A-' . str_pad($antrian->nomor, 3, '0', STR_PAD_LEFT);
        })
        ->addColumn('action', function ($antrian) {
            if ($antrian->status == 0) {
                return '<button class="panggil-btn badge badge-success p-2" ' .
                    'data-no-id="' . $antrian->id . '" ' .
                    'data-no-antrian="' . $antrian->nomor . '">Panggil</button>';
            } else if ($antrian->status == 1) {
                return '<button class="panggil-ulang-btn badge badge-danger p-2" ' .
                    'data-no-id="' . $antrian->id . '" ' .
                    'data-no-antrian="' . $antrian->nomor . '">Panggil Ulang</button>';
            }
        })
            ->make(true);
    }

    public function getNomorAntrian()
    {
        $tanggal = now()->toDateString();
        $latestAntrian = Antrian::where('tanggal', $tanggal)->latest()->first();

        if ($latestAntrian) {
            // $nomorAntrian = 'A-' . str_pad($latestAntrian->nomor + 1, 3, '0', STR_PAD_LEFT);
            $nomorAntrian = $latestAntrian->nomor + 1;
            return response()->json(['nomor_antrian' => $nomorAntrian]);
        } else {
            return response()->json(['nomor_antrian' => '1']);
        }
    }


    public function generateAntrian()
    {
        // Generate nomor antrian baru
        $tanggal = now()->toDateString();
        $nomor = Antrian::where('tanggal', $tanggal)->max('nomor') + 1;

        // Simpan ke database
        $antrian = Antrian::create([
            'tanggal' => $tanggal,
            'nomor' => $nomor,
            'status' => 0, // 0: belum dipanggil
        ]);

        return response()->json(['nomor_antrian' => $antrian->nomor]);
    }

    public function panggilAntrian($noId)
    {
        // Panggil antrian dengan noAntrian tertentu
        $antrian = Antrian::find($noId);

        if ($antrian) {
            $antrian->status = 1;
            $antrian->save();
            event(new AntrianUpdated($antrian->formatted_nomor));
            return response()->json(['message' => 'Antrian dipanggil berhasil']);
        } else {
            return response()->json(['message' => 'Antrian tidak ditemukan'], 404);
        }
    }

    public function getDataAntrian()
    {
        $tanggalSekarang = Carbon::now()->toDateString();
        // Mengambil semua data antrian berdasarkan tanggal dan outputnya jumlah
        $totalAntrian = Antrian::whereDate('created_at', $tanggalSekarang)->count();

        $antrianBelumDipanggil = Antrian::whereDate('created_at', $tanggalSekarang)->where('status', 0)->count();

        // $antrianSekarang = DB::table('antrians')->where('status', 'called')->first();
        $antrianSekarang = Antrian::where('status', '1')->first();

        if(request()->ajax()) {
            return response()->json([
                'totalAntrian' => $totalAntrian,
                'antrianBelumDipanggil' => $antrianBelumDipanggil,
                'antrianSekarang' => $antrianSekarang,
            ]);
        }

        return view('pendaftaran.index', compact('totalAntrian', 'antrianBelumDipanggil', 'antrianSekarang'));
    }

    public function getNomorAntrianSelanjutnya()
    {
        $tanggalSekarang = Carbon::now()->toDateString();
        // Ambil data antrian terakhir dengan status 0
        $latestAntrian = Antrian::whereDate('created_at', $tanggalSekarang)->where('status', 1)->latest()->first();

        if ($latestAntrian) {
            // Ambil nomor antrian selanjutnya
            $nomorAntrianSelanjutnya = 'A-' . str_pad($latestAntrian->nomor + 1, 3, '0', STR_PAD_LEFT);
            // $nomorAntrianSelanjutnya = $latestAntrian->nomor + 1;
            return response()->json(['nomorAntrianSelanjutnya' => $nomorAntrianSelanjutnya]);
        } else {
            // Jika tidak ada antrian dengan status 0, beri nomor awal
            return response()->json(['nomorAntrianSelanjutnya' => '-']);
        }
    }
}
