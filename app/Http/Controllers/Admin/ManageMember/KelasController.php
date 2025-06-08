<?php

namespace App\Http\Controllers\Admin\ManageMember;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Yajra\DataTables\Facades\DataTables;

class KelasController extends Controller
{
    public function index()
    {
        $tingkat_kelas = array();
        foreach (range(1, 12) as $n) : array_push($tingkat_kelas, $this->numberToRomanRepresentation($n));
        endforeach;
        $data = [
            'jurusan' => array("UMUM", "IPA", "IPS", "TIDAK_ADA"),
            'tingkat_kelas' => $tingkat_kelas,
        ];
        return view('admin.manage_member.kelas.index', $data)->with('sb', "Data Kelas");
    }

    public function create(Request $request)
    {
        if (
            Kelas::where('tingkat_kelas', $request->tingkat_kelas)
            ->where('jurusan', $request->jurusan)
            ->where('urusan_kelas', $request->urusan_kelas)
            ->first() != null
        ) {
            return redirect()->to('admin/manage-member/kelas')->with('message', "Data kelas sudah ada");
        } else {
            Kelas::create([
                'tingkat_kelas' => $request->tingkat_kelas,
                'jurusan' => $request->jurusan,
                'kelompok' => $request->kelompok,
                'urusan_kelas' => $request->urusan_kelas
            ]);
            return redirect()->to('admin/manage-member/kelas')->with('message', "Data kelas berhasil dibuat");
        }
    }

    public function getall(Request $request)
    {
        $query = Kelas::select('id', 'tingkat_kelas', 'jurusan', 'urusan_kelas', 'kelompok')
            ->orderBy('tingkat_kelas', "ASC")
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('jurusan', function (Kelas $k) {
                return ($k->jurusan == "TIDAK_ADA") ? "TIDAK ADA" : $k->jurusan;
            })
            ->addColumn('action', function (Kelas $k) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' . $k->id . '" class="dropdown-item edit" href="#">Edit</a></li>
                        <li><a data-id="' . $k->id . '" class="dropdown-item hapus" href="#">Hapus</a></li>
                    </ul>
                </div>
            ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function get(Request $request)
    {
        return response()->json(
            Kelas::where('id', $request->id)->first(),
            200
        );
    }

    public function update(Request $request)
    {
        Kelas::where('id', $request->id)->update([
            'tingkat_kelas' => $request->tingkat_kelas,
            'jurusan' => $request->jurusan,
            'urusan_kelas' => $request->urusan_kelas,
            'kelompok' => $request->kelompok,
        ]);
        return redirect()->to('admin/manage-member/kelas')->with('message', "Data kelas berhasil diupdate");
    }

    public function delete(Request $request)
    {
        Kelas::where('id', $request->id)->delete();
        return response()->json([
            'message' => "Kelas berhasil dihapus"
        ], 200);
    }

    /**
     * @param int $number
     * @return string
     */
    function numberToRomanRepresentation($number)
    {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}