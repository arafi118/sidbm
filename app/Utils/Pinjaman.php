<?php

namespace App\Utils;

class Pinjaman
{
    public static function keyword($text = false, $data = [])
    {
        if ($text === false) {
            return [
                [
                    'key' => '{kepala_lembaga}',
                    'des' => 'Menampilkan Sebutan Kepala Lembaga',
                ],
                [
                    'key' => '{kabag_administrasi}',
                    'des' => 'Menampilkan Sebutan Kabag Administrasi',
                ],
                [
                    'key' => '{kabag_keuangan}',
                    'des' => 'Menampilkan Sebutan Kabag Keuangan',
                ],
                [
                    'key' => '{verifikator}',
                    'des' => 'Menampilkan Nama Sebutan Verifikator',
                ],
                [
                    'key' => '{pengawas}',
                    'des' => 'Menampilkan Nama Sebutan Pengawas',
                ],
                [
                    'key' => '{ketua}',
                    'des' => 'Menampilkan Nama Ketua Kelompok',
                ],
                [
                    'key' => '{sekretaris}',
                    'des' => 'Menampilkan Nama Sekretaris Kelompok',
                ],
                [
                    'key' => '{bendahara}',
                    'des' => 'Menampilkan Nama Bendahara Kelompok',
                ],
                [
                    'key' => '{kades}',
                    'des' => 'Menampilkan Nama Kepala Desa/Lurah',
                ],
                [
                    'key' => '{pangkat}',
                    'des' => 'Menampilkan Pangkat Kepala Desa/Lurah',
                ],
                [
                    'key' => '{nip}',
                    'des' => 'Menampilkan Nip Kepala Desa/Lurah',
                ],
                [
                    'key' => '{sekdes}',
                    'des' => 'Menampilkan Nama Sekdes',
                ],
                [
                    'key' => '{ked}',
                    'des' => 'Menampilkan Nama Kader Ekonomi Desa',
                ],
                [
                    'key' => '{desa}',
                    'des' => 'Menampilkan Nama Desa',
                ],
                [
                    'key' => '{sebutan_kades}',
                    'des' => 'Menampilkan Sebutan Kepala Desa/Lurah',
                ],
            ];
        } else {
            $kec = $data['kec'];

            $pinkel = $data['pinkel'];
            $kel = $pinkel->kelompok;
            $desa = $pinkel->kelompok->d;

            $ttd = strtr(json_decode($text, true), [
                '{kepala_lembaga}' => $kec->sebutan_level_1,
                '{kabag_administrasi}' => $kec->sebutan_level_2,
                '{kabag_keuangan}' => $kec->sebutan_level_3,
                '{verifikator}' => $kec->nama_tv_long,
                '{pengawas}' => $kec->nama_bp_long,
                '{ketua}' => $kel->ketua,
                '{sekretaris}' => $kel->sekretaris,
                '{bendahara}' => $kel->bendahara,
                '{kades}' => $desa->kades,
                '{nip}' => $desa->nip,
                '{sekdes}' => $desa->sekdes,
                '{ked}' => $desa->ked,
                '{desa}' => $desa->nama_desa,
                '{sebutan_kades}' => $desa->sebutan_desa->sebutan_kades,
                '1' => '1',
                '0' => '0'
            ]);

            return $ttd;
        }
    }
}
