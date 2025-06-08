<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Sekolah;
use App\Models\Siswa;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $data = [
            'domain' => env('API_URL'),
            'today' => Carbon::createFromFormat('Y-m-d', date('Y-m-d'))->isoFormat('dddd, D MMMM Y')
        ];
        return view('admin.dashboard.index', $data)->with('sb', 'Dashboard');
    }

    
}
