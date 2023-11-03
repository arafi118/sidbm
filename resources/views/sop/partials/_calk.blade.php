{{-- 
    {
        "peraturan_desa":"01 TAHUN 2022",
        "D":{
            "1":{
                "d":{
                    "1":"25",
                    "2":"25",
                    "3":"40"
                }
            },
            "2":{
                "a":"151700007",
                "b":"250000000",
                "c":"450000000"
            }
        }
    }
--}}

@php
    $calk = json_decode($kec->calk, true);
    $peraturan_desa = $calk['peraturan_desa'];
@endphp

<form action="/pengaturan/calk/{{ $kec->id }}" method="post" id="formCalk">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-12">
            <div class="input-group input-group-static my-3">
                <label for="peraturan_desa">Peraturan Desa Nomor</label>
                <input autocomplete="off" type="text" name="peraturan_desa" id="peraturan_desa" class="form-control"
                    value="{{ $peraturan_desa }}">
                <small class="text-danger" id="msg_peraturan_desa"></small>
            </div>
        </div>
    </div>

    <div class="alert alert-secondary text-light mb-0">
        Bagian milik bersama masyarakat Desa
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="input-group input-group-static my-3">
                <label for="bantuan_rumah_tangga">Bantuan Rumah Tangga (%)</label>
                <input autocomplete="off" type="number" name="bantuan_rumah_tangga" id="bantuan_rumah_tangga"
                    class="form-control" value="{{ $calk['D']['1']['d']['1'] }}">
                <small class="text-danger" id="msg_bantuan_rumah_tangga"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-static my-3">
                <label for="pengembangan_kapasitas">Pengembangan Kapasitas Pinjaman (%)</label>
                <input autocomplete="off" type="number" name="pengembangan_kapasitas" id="pengembangan_kapasitas"
                    class="form-control" value="{{ $calk['D']['1']['d']['2'] }}">
                <small class="text-danger" id="msg_pengembangan_kapasitas"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-static my-3">
                <label for="pelatihan_masyarakat">Pelatihan Masyarakat (%)</label>
                <input autocomplete="off" type="number" name="pelatihan_masyarakat" id="pelatihan_masyarakat"
                    class="form-control" value="{{ $calk['D']['1']['d']['3'] }}">
                <small class="text-danger" id="msg_pelatihan_masyarakat"></small>
            </div>
        </div>
    </div>

    <div class="alert alert-secondary text-light mb-0">
        Laba Ditahan
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="input-group input-group-static my-3">
                <label for="peningkatan_modal">Peningkatan Modal DBM</label>
                <input autocomplete="off" type="text" name="peningkatan_modal" id="peningkatan_modal"
                    class="form-control money" value="{{ number_format($calk['D']['2']['a'], 2) }}">
                <small class="text-danger" id="msg_peningkatan_modal"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-static my-3">
                <label for="penambahan_investasi">Penambahan Investasi</label>
                <input autocomplete="off" type="text" name="penambahan_investasi" id="penambahan_investasi"
                    class="form-control money" value="{{ number_format($calk['D']['2']['b'], 2) }}">
                <small class="text-danger" id="msg_penambahan_investasi"></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-static my-3">
                <label for="pendirian_unit_usaha">Pendirian Unit Usaha</label>
                <input autocomplete="off" type="text" name="pendirian_unit_usaha" id="pendirian_unit_usaha"
                    class="form-control money" value="{{ number_format($calk['D']['2']['c'], 2) }}">
                <small class="text-danger" id="msg_pendirian_unit_usaha"></small>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button type="button" id="SimpanCalk" data-target="#formCalk"
            class="btn btn-sm btn-github mb-0 btn-simpan">Simpan Perubahan</button>
    </div>
</form>
