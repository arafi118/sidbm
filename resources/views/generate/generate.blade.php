<link id="pagestyle" href="/assets/css/material-dashboard.min.css" rel="stylesheet" />
<link rel="stylesheet" href="/assets/css/style.css">

<body class="w-100 min-vh-100 d-flex align-items-center justify-content-center flex-column">
    <form action="/generate/save/{{ $offset }}" method="post">
        @csrf

        @foreach ($data as $key => $val)
            @php
                if ($key == '_token') {
                    continue;
                }
            @endphp

            @if (is_array($val))
                @php
                    $opt = $val['operator'];
                    $value = $val['value'];
                @endphp

                <input type="hidden" name="{{ $key }}[operator]" value="{{ $opt }}">
                <input type="hidden" name="{{ $key }}[value]" value="{{ $value }}">
            @else
                @php
                    $value = $val;
                @endphp

                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endif
        @endforeach

        <div class="text-center">
            Generate <b>{{ $offset }}</b> data.
        </div>
        <button type="submit" class="btn btn-info" id="generate">Generate</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    @if (count($data_id_pinj) >= $limit)
        <script>
            $('#generate').trigger('click')
        </script>
    @else
        <script>
            window.close()
        </script>
    @endif
</body>
