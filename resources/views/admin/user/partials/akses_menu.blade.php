@php
    $hak_akses_menu = explode(',', $user->akses_menu);
@endphp

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Pengaturan Hak Akses Menu</h5>
    </div>
    <div class="card-body pt-0">
        <form action="/master/users/akses_tombol/{{ $user->id }}" method="post" id="FormAccesMenuUser">
            @csrf
            <ul class="list-group">
                @foreach ($DaftarMenu as $menu)
                    @php
                        $have_child = false;
                        if (count($menu->child) > 0) {
                            $have_child = true;
                        }

                        $bold = '';
                        if ($menu->link != '' || $have_child) {
                            $bold = 'fw-bold';
                        }

                        $checked = '';
                        if (!in_array($menu->id, $hak_akses_menu)) {
                            $checked = 'checked';
                        }
                    @endphp
                    <li class="list-group-item d-flex align-items-center">
                        <div class="form-check form-switch mb-0 d-flex align-items-center">
                            <input class="form-check-input" type="checkbox" name="akses_menu[]" id="{{ $menu->id }}"
                                {{ $checked }} value="{{ $menu->id }}">
                            <label class="form-check-label {{ $bold }} mb-0 ms-3"
                                for="{{ $menu->id }}">{{ $menu->title }}
                            </label>
                        </div>
                    </li>

                    @if (count($menu->child) > 0)
                        <li class="list-group-item p-0 ps-4">
                            <ul class="list-group">
                                @foreach ($menu->child as $child)
                                    @php
                                        $bold = '';
                                        if (count($child->child) > 0) {
                                            $bold = 'fw-bold';
                                        }

                                        $checked = '';
                                        if (!in_array($child->id, $hak_akses_menu)) {
                                            $checked = 'checked';
                                        }
                                    @endphp

                                    <li class="list-group-item border-0">
                                        <div class="form-check form-switch mb-0 d-flex align-items-center">
                                            <input class="form-check-input" data-parent="{{ $menu->id }}"
                                                name="akses_menu[]" type="checkbox" id="{{ $child->id }}"
                                                {{ $checked }} value="{{ $child->id }}">
                                            <label class="form-check-label {{ $bold }} mb-0 ms-3"
                                                for="{{ $child->id }}">{{ $child->title }}
                                            </label>
                                        </div>
                                    </li>

                                    @if (count($child->child) > 0)
                                        <li class="list-group-item p-0 ps-4 border-0">
                                            <ul class="list-group">
                                                @foreach ($child->child as $subchild)
                                                    @php
                                                        $checked = '';
                                                        if (!in_array($subchild->id, $hak_akses_menu)) {
                                                            $checked = 'checked';
                                                        }
                                                    @endphp
                                                    <li class="list-group-item border-0">
                                                        <div
                                                            class="form-check form-switch mb-0 d-flex align-items-center">
                                                            <input class="form-check-input"
                                                                data-parent="{{ $menu->id }}"
                                                                data-child="{{ $child->id }}" name="akses_menu[]"
                                                                type="checkbox" id="{{ $subchild->id }}"
                                                                {{ $checked }} value="{{ $subchild->id }}">
                                                            <label class="form-check-label mb-0 ms-3"
                                                                for="{{ $subchild->id }}">{{ $subchild->title }}
                                                            </label>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        </form>

        <div class="d-flex justify-content-end mt-3">
            <button type="button" id="NextStep" class="btn btn-sm btn-info">Lanjutkan</button>
        </div>
    </div>
</div>
