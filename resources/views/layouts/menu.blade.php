@foreach ($parent_menu as $menu)
    @if (count($menu->child) > 0)
        <li class="nav-item">
            <a data-bs-toggle="collapse" href="#{{ str_replace('#', '', $menu->link) }}" class="nav-link text-white"
                aria-controls="{{ str_replace('#', '', $menu->link) }}" role="button" aria-expanded="false">
                @if ($menu->type == 'material')
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">{{ $menu->ikon }}</i>
                    </div>
                @else
                    <span class="sidenav-mini-icon"> {{ $menu->ikon }} </span>
                @endif
                <span class="nav-link-text ms-1">{{ $menu->title }}</span>
            </a>
            <div class="collapse" id="{{ str_replace('#', '', $menu->link) }}">
                <ul class="nav nav-sm flex-column">
                    @include('layouts.menu', ['parent_menu' => $menu->child])
                </ul>
            </div>
        </li>
    @else
        @if ($menu->link == '' && $menu->type == '' && $menu->ikon == '')
            <li class="nav-item mt-3">
                <h6 class="ps-4  ms-2 text-uppercase text-xs font-weight-bolder text-white">{{ $menu->title }}</h6>
            </li>
        @else
            <li class="nav-item nav-item-link">
                <a class="nav-link text-white " href="{{ $menu->link }}">
                    @if ($menu->type == 'material')
                        <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="material-icons opacity-10">{{ $menu->ikon }}</i>
                        </div>
                    @else
                        <span class="sidenav-mini-icon"> {{ $menu->ikon }} </span>
                    @endif
                    <span class="nav-link-text ms-1">{{ $menu->title }}</span>
                </a>
            </li>
        @endif
    @endif
@endforeach
