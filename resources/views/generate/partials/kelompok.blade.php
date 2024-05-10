@php
    $operator = [
        '=',
        '!=',
        '>',
        '<',
        'LIKE',
        'NOT LIKE',
        [
            'title' => 'IN (...)',
            'value' => 'IN',
        ],
        [
            'title' => 'NOT IN (...)',
            'value' => 'NOT IN',
        ],
    ];

    $continue = ['sumber', 'catatan_verifikasi', 'wt_cair', 'lu'];
@endphp

<form action="/generate/save" method="post" target="_blank">
    @csrf

    <input type="hidden" name="pinjaman" id="pinjaman" value="kelompok">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="bg-dark text-white">
                <tr>
                    <th>Kolom</th>
                    <th>Operator</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($struktur as $val)
                    @php
                        if (in_array($val, $continue)) {
                            continue;
                        }
                    @endphp
                    <tr>
                        <td>
                            <b>{{ ucwords(str_replace('_', ' ', $val)) }}</b>
                        </td>
                        <td>
                            <div class="input-group input-group-static">
                                <select name="{{ $val }}[operator]" class="form-control">
                                    @foreach ($operator as $opt)
                                        @php
                                            $title = $opt;
                                            $value = $opt;
                                            if (is_array($opt)) {
                                                $title = $opt['title'];
                                                $value = $opt['value'];
                                            }
                                        @endphp

                                        <option value="{{ $value }}">{{ $title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="input-group input-group-static">
                                <input type="text" name="{{ $val }}[value]" class="form-control">
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-info btn-sm">Generate</button>
    </div>
</form>
