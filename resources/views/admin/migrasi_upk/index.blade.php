@extends('admin.layout.base')

@section('content')
    <div class="row">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="my-2">
                                <label class="form-label" for="server">Server</label>
                                <select class="form-control" name="server" id="server">
                                    <option value="net">NET</option>
                                    <option value="com">COM</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="my-2">
                                <label class="form-label" for="lokasi">Lokasi</label>
                                <select class="form-control" name="lokasi" id="lokasi">
                                    <option value="com">COM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        new Choices($('#server')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })
        new Choices($('#lokasi')[0], {
            shouldSort: false,
            fuseOptions: {
                threshold: 0.1,
                distance: 1000
            }
        })
    </script>
@endsection
