<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\Keuangan;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $users = User::with(['l', 'j', 'kec', 'kec.kabupaten'])->get();
            return DataTables::of($users)
                ->editColumn('namadepan', function ($row) {
                    return $row->namadepan . ' ' . $row->namabelakang;
                })
                ->editColumn('kec.nama_kec', function ($row) {
                    if (!$row->kec) {
                        return '';
                    }

                    $kec = $row->kec->nama_kec;
                    if ($row->kec->kabupaten) {
                        if (Keuangan::startWith($row->kec->kabupaten->nama_kab, 'KOTA') || Keuangan::startWith($row->kec->kabupaten->nama_kab, 'KAB')) {
                            $kec .= ', ' . ucwords(strtolower($row->kec->kabupaten->nama_kab));
                        } else {
                            $kec .= ', Kab. ' . ucwords(strtolower($row->kec->kabupaten->nama_kab));
                        }
                    }

                    return $kec;
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

        $title = 'Daftar User';
        return view('admin.user.index')->with(compact('title'));
    }

    public function show(User $user)
    {
        $data['title'] = 'Detail User ' . $user->namadepan . ' ' . $user->namabelakang;
        return view('admin.user.detail', $data);
    }
}
