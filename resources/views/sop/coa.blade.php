@extends('layouts.base')

@section('content')
    <div class="card">
        <div class="card-body">
            <div id="akun"></div>
        </div>
    </div>

    <form action="" method="post" id="formCoa">
        @csrf

        @method('POST')
        <input type="hidden" name="id_akun" id="id_akun">
        <input type="hidden" name="nama_akun" id="nama_akun">
    </form>
@endsection

@section('script')
    <script>
        $('#akun').jstree({
            'core': {
                'check_callback': true,
                'data': {
                    'url': '/pengaturan/coa',
                }
            },
            'plugins': ['contextmenu', 'dnd', 'crrm'],
            'contextmenu': {
                'items': function($node) {
                    var tree = $('#akun').jstree(true);

                    var kode_akun = tree.get_node($node).id.split('.')
                    var lev1 = parseInt(kode_akun[0]);
                    var lev2 = parseInt(kode_akun[1]);
                    var lev3 = parseInt(kode_akun[2]);
                    var lev4 = parseInt(kode_akun[3]);

                    var items = {};
                    if ((lev1 > 0 && lev2 > 0 && lev3 > 0 && lev4 > 0) || tree.get_node($node).children
                        .length === 0) {
                        items.Rename = {
                            "separator_before": false,
                            "separator_after": false,
                            "label": "Edit",
                            "action": function(obj) {
                                tree.edit($node);
                            }
                        };
                    }
                    return items;
                }
            }
        }).on('rename_node.jstree', function(e, data) {
            var id = data.node.id
            var text = data.node.text
            var old_text = data.old

            if (text != old_text) {
                $('#id_akun').val(id)
                $('#nama_akun').val(text)
                $('#formCoa input[name=_method]').val('PUT')

                $('#formCoa').attr('action', '/pengaturan/coa/' + id)
                formSubmit('update', data)
            }
        });

        function formSubmit(action, data = null) {
            var form = $('#formCoa')
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        if (action == 'create') {
                            data.instance.set_id(data.node, result.id);
                        }

                        if (action != 'delete') {
                            data.instance.set_text(data.node, result.nama_akun);
                        }

                        Toastr('success', result.msg)
                    } else {
                        if (action == 'create') {
                            data.instance.delete_node(data.node);
                        }

                        if (action == 'update') {
                            data.instance.set_text(data.node, data.old);
                        }

                        Toastr('warning', result.msg)
                    }
                }
            })
        }
    </script>
@endsection
