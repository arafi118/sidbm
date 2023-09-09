@php
    use App\Utils\Tanggal;
@endphp

<table class="table table-striped">
    <tbody>
        <tr>
            <td>Kode Kelompok</td>
            <td>:</td>
            <td>{{ $pinkel->kelompok->kd_kelompok }}</td>

            <td>&nbsp;</td>

            <td>Loan Id.</td>
            <td>:</td>
            <td>{{ $pinkel->jpp->nama_jpp }}-{{ $pinkel->id }}</td>
        </tr>
        <tr>
            <td>Nama Kelompok</td>
            <td>:</td>
            <td>{{ $pinkel->kelompok->nama_kelompok }}</td>

            <td>&nbsp;</td>

            <td>No. SPK</td>
            <td>:</td>
            <td>{{ $pinkel->spk_no }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $pinkel->kelompok->alamt_kelompok }}</td>

            <td>&nbsp;</td>

            <td>Tgl Cair</td>
            <td>:</td>
            <td>{{ Tanggal::tglIndo($pinkel->tgl_cair) }}</td>
        </tr>
        <tr>
            <td>Desa</td>
            <td>:</td>
            <td>{{ $pinkel->kelompok->d->nama_desa }}</td>

            <td>&nbsp;</td>

            <td>Alokasi</td>
            <td>:</td>
            <td>{{ number_format($pinkel->alokasi) }}</td>
        </tr>
        <tr>
            <td>Telpon/SMS</td>
            <td>:</td>
            <td>{{ $pinkel->kelompok->telpon }}</td>

            <td>&nbsp;</td>

            <td>Jasa</td>
            <td>:</td>
            <td>{{ $pinkel->pros_jasa / $pinkel->jangka }}%</td>
        </tr>
        <tr>
            <td>Ketua</td>
            <td>:</td>
            <td>{{ $pinkel->kelompok->ketua }}</td>

            <td>&nbsp;</td>

            <td>Sistem Angsuran</td>
            <td>:</td>
            <td>{{ $pinkel->jangka }} bulan @ {{ $pinkel->sis_pokok->nama_sistem }}</td>
        </tr>
        <tr>
            <td>Sekretaris</td>
            <td>:</td>
            <td>{{ $pinkel->kelompok->sekretaris }}</td>

            <td>&nbsp;</td>

            <td>Angsuran Pokok</td>
            <td>:</td>
            <td>{{ number_format($pinkel->target->wajib_pokok) }} x {{ $pinkel->jangka }}</td>
        </tr>
        <tr>
            <td>Bendahara</td>
            <td>:</td>
            <td>{{ $pinkel->kelompok->bendahara }}</td>

            <td>&nbsp;</td>

            <td>Angsuran Jasa</td>
            <td>:</td>
            <td>{{ number_format($pinkel->target->wajib_jasa) }} x {{ $pinkel->jangka }}</td>
        </tr>
    </tbody>
</table>
