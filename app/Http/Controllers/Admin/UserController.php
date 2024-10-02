<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Menu;
use App\Models\MenuTombol;
use App\Models\User;
use App\Utils\Keuangan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $kabupaten = Kabupaten::with([
            'wilayah',
            'kec' => function ($query) {
                $query->orderBy('nama_kec', 'ASC');
            }
        ])->orderBy('nama_kab', 'ASC')->get();

        $title = 'Daftar User';
        return view('admin.user.index')->with(compact('title', 'kabupaten'));
    }

    public function DaftarUser($kd_kec)
    {
        if (request()->ajax()) {
            $kec = Kecamatan::where('kd_kec', $kd_kec)->first();
            $users = User::where('lokasi', $kec->id)->with([
                'l', 'j'
            ])->get();

            return DataTables::of($users)
                ->editColumn('namadepan', function ($row) {
                    return $row->namadepan . ' ' . $row->namabelakang;
                })
                ->editColumn('j.nama_jabatan', function ($row) {
                    if (!$row->l) {
                        return '';
                    }

                    return $row->l->nama_level;
                })
                ->editColumn('j.nama_jabatan', function ($row) {
                    if (!$row->j) {
                        return '';
                    }

                    return $row->j->nama_jabatan;
                })
                ->make(true);
        }

        abort(404);
    }

    public function show(User $user)
    {
        $data['user'] = $user;
        $data['DaftarMenu'] = Menu::where('parent_id', '0')->where('aktif', 'Y')->with([
            'child',
            'child.child',
        ])->orderBy('sort', 'ASC')->orderBy('id', 'ASC')->get();

        $data['title'] = 'Detail User ' . $user->namadepan . ' ' . $user->namabelakang;
        return view('admin.user.detail', $data);
    }

    public function AksesTombol(Request $request, User $user)
    {
        if (count($request->akses_menu) > 0 && is_array($request->akses_menu)) {
            $MenuSelected = array_values($request->akses_menu);
            $DaftarMenu = Menu::whereIn('id', $MenuSelected)->with([
                'tombol' => function ($query) {
                    $query->where('parent_id', '0');
                },
                'tombol.child' => function ($query) {
                    $query->where('parent_id', '!=', '0');
                },
            ])->orderBy('sort', 'ASC')->orderBy('id', 'ASC')->get();

            return response()->json([
                'success' => true,
                'view' => view('admin.user.partials.akses_tombol')->with(compact('user', 'DaftarMenu', 'MenuSelected'))->render()
            ]);
        }

        return response()->json([
            'success' => false,
            'msg' => 'Tidak ada menu yang dipilih'
        ]);
    }

    public function HakAkses(Request $request, User $user)
    {
        if (count($request->akses_menu) > 0 && is_array($request->akses_menu)) {
            $MenuSelected = explode(',', $request->menu_selected);
            $TombolSelected = array_values($request->akses_menu);

            $Menu = Menu::whereNotIn('id', $MenuSelected)->pluck('id')->toArray();
            $Tombol = MenuTombol::whereNotIn('id', $TombolSelected)->pluck('id')->toArray();

            $SimpanAksesMenu = '';
            if (count($Menu) > 0) {
                $SimpanAksesMenu = implode(',', $Menu);
            }

            $SimpanAksesTombol = '';
            if (count($Tombol) > 0) {
                $SimpanAksesTombol = implode(',', $Tombol);
            }

            $Update = User::where('id', $user->id)->update([
                'akses_menu' => $SimpanAksesMenu,
                'akses_tombol' => $SimpanAksesTombol
            ]);

            return response()->json([
                'success' => true,
                'msg' => 'Hak Akses User ' . $user->namadepan . ' ' . $user->namabelakang . ' berhasil diperbarui.'
            ]);
        }

        return response()->json([
            'success' => false,
            'msg' => 'Tidak ada menu yang dipilih'
        ]);
    }
}
