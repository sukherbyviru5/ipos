<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Models\Video;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    public function index()
    {
        return view('admin.setting.video.index')->with('sb', 'Video');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'video_url' => 'required|url|regex:/^https:\/\/www\.youtube\.com\/watch\?v=/',
            'no_urut' => 'required|integer|unique:video,no_urut',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Video::create([
            'judul' => $request->judul,
            'video_url' => $request->video_url,
            'no_urut' => $request->no_urut,
        ]);

        return redirect()->back()->with('message', 'Video berhasil ditambahkan');
    }

    public function getall(Request $request)
    {
        $query = Video::select('id', 'judul', 'video_url', 'no_urut')->orderBy('no_urut', 'ASC')->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function (Video $video) {
                return '
                <div class="dropdown d-inline dropleft">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false">
                        Action
                    </button>
                    <ul class="dropdown-menu">
                        <li><a data-id="' .
                    $video->id .
                    '" class="dropdown-item edit" href="#">Edit</a></li>
                        <li><a data-id="' .
                    $video->id .
                    '" class="dropdown-item hapus" href="#">Hapus</a></li>
                    </ul>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function get(Request $request)
    {
        return response()->json(Video::select('id', 'judul', 'video_url', 'no_urut')->where('id', $request->id)->first(), 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:video,id',
            'judul' => 'required|string|max:255',
            'video_url' => 'required|url|regex:/^https:\/\/www\.youtube\.com\/watch\?v=/',
            'no_urut' => 'required|integer|string|max:50|unique:video,no_urut,' . $request->id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Video::where('id', $request->id)->update([
            'judul' => $request->judul,
            'video_url' => $request->video_url,
            'no_urut' => $request->no_urut,
        ]);

        return redirect()->back()->with('message', 'Video berhasil diupdate');
    }

    public function delete(Request $request)
    {
        Video::where('id', $request->id)->delete();
        return response()->json(
            [
                'message' => 'Video berhasil dihapus',
            ],
            200,
        );
    }
}
