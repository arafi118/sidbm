@php
    $hak_akses_tombol = explode(',', $user->akses_tombol);
@endphp

<form action="/master/users/hak_akses/{{ $user->id }}" method="post" id="FormPengaturanHakAksesUser">
    @csrf

    <input type="hidden" name="menu_selected" id="menu_selected" value="{{ implode(',', $MenuSelected) }}">
    <div class="accordion" id="AccordionMenu">
        @foreach ($DaftarMenu as $menu)
            @php
                if (count($menu->tombol) <= 0) {
                    continue;
                }
            @endphp
            <div class="accordion-item">
                <div class="accordion-header" id="menu-{{ $menu->id }}">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#content-{{ $menu->id }}" aria-expanded="false"
                        aria-controls="content-{{ $menu->id }}">
                        <b>{{ $menu->title }}</b>
                    </button>
                </div>
                <div id="content-{{ $menu->id }}" class="accordion-collapse collapse"
                    aria-labelledby="{{ $menu->id }}">
                    <div class="accordion-body p-0 rounded border">
                        <ul class="list-group">
                            @foreach ($menu->tombol as $tombol)
                                @php
                                    $checked = '';
                                    if (!in_array($tombol->id, $hak_akses_tombol)) {
                                        $checked = 'checked';
                                    }
                                @endphp
                                <li class="list-group-item d-flex align-items-center border-0">
                                    <div class="form-check form-switch mb-0 d-flex align-items-center">
                                        <input class="form-check-input" type="checkbox" name="akses_menu[]"
                                            id="tombol-{{ $tombol->id }}" {{ $checked }}
                                            value="{{ $tombol->id }}">
                                        <label class="form-check-label mb-0 ms-3"
                                            for="tombol-{{ $tombol->id }}">{{ $tombol->text }}
                                        </label>
                                    </div>
                                </li>
                                @if (count($tombol->child) > 0)
                                    <li class="list-group-item p-0 ps-4 border-0">
                                        <ul class="list-group">
                                            @foreach ($tombol->child as $child)
                                                @php
                                                    $checked = '';
                                                    if (!in_array($child->id, $hak_akses_tombol)) {
                                                        $checked = 'checked';
                                                    }
                                                @endphp
                                                <li class="list-group-item border-0">
                                                    <div class="form-check form-switch mb-0 d-flex align-items-center">
                                                        <input class="form-check-input"
                                                            data-parent="tombol-{{ $tombol->id }}"
                                                            name="akses_menu[]" type="checkbox"
                                                            id="tombol-{{ $child->id }}" {{ $checked }}
                                                            value="{{ $child->id }}">
                                                        <label class="form-check-label mb-0 ms-3"
                                                            for="tombol-{{ $child->id }}">{{ $child->text }}
                                                        </label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-sm btn-warning" id="BackStep">Kembali</button>
        <button type="button" class="btn btn-sm btn-info ms-2" id="Save">Simpan</button>
    </div>
</form>
