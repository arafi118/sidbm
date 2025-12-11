@extends('admin.layout.base')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form id="version-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3">
                                    <label for="latest_version">Latest Version</label>
                                    <input autocomplete="off" type="text" name="latest_version" id="latest_version"
                                        class="form-control" value="{{ $daftarUpdate[0]->latest_version ?? '1.0.1' }}">
                                    <small class="text-danger" id="msg_latest_version"></small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-static my-3">
                                    <label for="version_code">Version Code</label>
                                    <input autocomplete="off" type="text" name="version_code" id="version_code"
                                        class="form-control" value="{{ $daftarUpdate[0]->version_code + 1 ?? '1' }}"
                                        readonly>
                                    <small class="text-danger" id="msg_version_code"></small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group input-group-static my-3">
                                    <label for="changelog">Changelog</label>
                                    <textarea name="changelog" id="changelog" class="form-control" rows="5"></textarea>
                                    <small class="text-danger" id="msg_changelog"></small>
                                </div>
                            </div>
                        </div>

                        <!-- Dropzone Area -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group input-group-static my-3">
                                    <label>Upload File APK/AAB</label>
                                    <div id="file-dropzone" class="dropzone mt-2">
                                        <div class="dz-message needsclick">
                                            <i class="material-icons opacity-10" style="font-size: 48px;">cloud_upload</i>
                                            <h5>Drop file di sini atau klik untuk upload</h5>
                                            <span class="note needsclick">Maksimal ukuran file:
                                                <strong>100MB</strong></span>
                                        </div>
                                    </div>
                                    <small class="text-danger" id="msg_file"></small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-github w-100">
                                    <i class="material-icons">save</i> Simpan Version
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Daftar Version yang sudah diupload -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Version History</h6>
                </div>
                <div class="card-body pt-0">
                    <div id="version-list">
                        @foreach ($daftarUpdate as $update)
                            <div class="version-item">
                                <h6>v{{ $update->latest_version }} ({{ $update->version_code }})</h6>
                                <small>{!! nl2br($update->changelog) !!}</small>
                                <br>
                                <small class="text-muted">{{ date('Y-m-d', strtotime($update->created_at)) }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css">
    <style>
        .dropzone {
            width: 100%;
            border: 2px dashed #d2d6da;
            border-radius: 8px;
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dropzone:hover {
            border-color: #5e72e4;
            background: #f0f3ff;
        }

        .dropzone .dz-message {
            margin: 0;
        }

        .dropzone .dz-message i {
            color: #5e72e4;
        }

        .dropzone .dz-message h5 {
            font-weight: 600;
            color: #32325d;
            margin: 10px 0;
        }

        .dropzone .dz-message .note {
            font-size: 0.875rem;
            color: #8898aa;
        }

        .dropzone.dz-drag-hover {
            border-color: #5e72e4;
            background: #e8ecff;
        }

        .dropzone .dz-preview {
            margin: 16px 0;
        }

        .dropzone .dz-preview .dz-image {
            border-radius: 8px;
        }

        .dropzone .dz-preview .dz-error-message {
            background: #f5365c;
            border-radius: 8px;
        }

        .version-item {
            padding: 15px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .version-item h6 {
            margin-bottom: 5px;
            color: #32325d;
        }

        .version-item small {
            color: #8898aa;
        }
    </style>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

    <script>
        Dropzone.autoDiscover = false;
        let uploadedFile = null;

        const myDropzone = new Dropzone("#file-dropzone", {
            url: "/master/upload_aplikasi/upload",
            method: "post",
            paramName: "file",
            maxFilesize: 100,
            maxFiles: 1,
            acceptedFiles: ".apk,.aab",
            addRemoveLinks: true,
            dictDefaultMessage: "",
            dictRemoveFile: "Hapus",
            dictCancelUpload: "Batalkan",
            dictFileTooBig: "File terlalu besar (@{{ filesize }}MB). Maksimal: @{{ maxFilesize }}MB",
            dictInvalidFileType: "Hanya file APK atau AAB yang diizinkan",
            dictMaxFilesExceeded: "Hanya bisa upload 1 file",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            init: function() {
                this.on("sending", function(file, xhr, formData) {
                    formData.append("version", document.getElementById('latest_version').value);
                });

                this.on("success", function(file, response) {
                    uploadedFile = response.data;
                    showNotification('success', 'File berhasil diupload');
                });

                this.on("error", function(file, errorMessage) {
                    let message = typeof errorMessage === 'string' ?
                        errorMessage :
                        errorMessage.message || 'Terjadi kesalahan saat upload';
                    showNotification('error', message);
                });

                this.on("removedfile", function(file) {
                    uploadedFile = null;
                });

                this.on("maxfilesexceeded", function(file) {
                    this.removeAllFiles();
                    this.addFile(file);
                });
            }
        });

        document.getElementById('version-form').addEventListener('submit', function(e) {
            e.preventDefault();

            document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');

            let isValid = true;
            const latestVersion = document.getElementById('latest_version').value;
            const versionCode = document.getElementById('version_code').value;
            const changelog = document.getElementById('changelog').value;

            if (!latestVersion) {
                document.getElementById('msg_latest_version').textContent = 'Latest version harus diisi';
                isValid = false;
            }

            if (!versionCode) {
                document.getElementById('msg_version_code').textContent = 'Version code harus diisi';
                isValid = false;
            }

            if (!changelog) {
                document.getElementById('msg_changelog').textContent = 'Changelog harus diisi';
                isValid = false;
            }

            if (!uploadedFile) {
                document.getElementById('msg_file').textContent = 'File APK/AAB harus diupload';
                isValid = false;
            }

            if (!isValid) {
                showNotification('error', 'Mohon lengkapi semua field');
                return;
            }

            const formData = {
                latest_version: latestVersion,
                version_code: versionCode,
                changelog: changelog,
                file: uploadedFile
            };

            fetch("/master/upload_aplikasi/store", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(formData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('success', 'Version berhasil disimpan');

                        document.getElementById('version-form').reset();
                        myDropzone.removeAllFiles();
                        uploadedFile = null;

                        window.location.reload();
                    } else {
                        showNotification('error', data.message || 'Terjadi kesalahan');
                    }
                })
                .catch(error => {
                    showNotification('error', 'Terjadi kesalahan saat menyimpan data');
                    console.error(error);
                });
        });

        function showNotification(type, message) {
            if (type === 'success') {
                Toastr('success', message);
            } else {
                Toastr('error', message);
            }
        }
    </script>
@endsection
