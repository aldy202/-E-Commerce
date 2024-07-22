@extends('layout.app')

@section('title', 'Data SubKategori')

@section('content')

    <div class="card shadow">
        <div class="card-header">
            <h4 class="card-title">
                Data Sub Kategori
            </h4>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-4">
                <a href="#modal-form" class="btn btn-primary modal-tambah">Tambah Data</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama SubKategori</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-form" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form SubKategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-subkategori">
                                <div class="form-group">
                                    <label for="nama_kategori">Nama SubKategori</label>
                                    <input type="text" name="nama_subkategori" id="nama_kategori"
                                        placeholder="Nama Kategori" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Kategori</label>
                                    <select name="id_kategori" id="id_kategori" class="form-control">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi">Dekripsi</label>
                                    <textarea type="text" name="deskripsi" id="deskripsi" cols="30" rows="10" placeholder="Deskripsi" required
                                        class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="gambar">Gambar</label>
                                    <input type="file" name="gambar" id="gambar" class="form-control" id="gambar">
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        $(function() {
            $.ajax({
                url: '/api/subcategories',
                success: function({
                    data
                }) {
                    let row;
                    data.map(function(val, index) {
                        row += `
                        <tr>
                            <td>${index+1 }</td>
                            <td>${val.nama_subkategori}</td>
                            <td>${val.category.nama_kategori}</td>
                            <td>${val.deskripsi}</td>
                            <td>
                                <img src="/storage/submenus/${val.gambar}" width="100" alt="Category Image">
                            </td>
                            <td>
                                <a data-toggle="modal" href="#modal-form" data-id="${val.id}" class="btn btn-warning modal-ubah">Edit</a>
                                <a href="#" data-id="${val.id}" class="btn btn-danger btn-hapus">Hapus</a>
                            </td>
                        </tr>
                        `;

                    });
                    $('tbody').append(row)
                }
            });

            $(document).on('click', '.btn-hapus', function() {
                const id = $(this).data('id');

                const token = localStorage.getItem('token');

                confirm_dialog = confirm('Apakah anda yakin ingin menghapus data ini?');


                if (confirm_dialog) {
                    $.ajax({
                        url: `/api/subcategories/` + id,
                        type: 'DELETE',
                        headers: {
                            "Authorization": 'Bearer ' + token
                        },
                        success: function(data) {

                            if (data.message == 'success') {
                                location.reload()
                            } else {
                                // Handle deletion error
                                alert('Deletion failed! ' + data.message);
                            }
                        }
                    })

                }
            });

            $('.modal-tambah').click(function() {
                $('input[name=nama_kategori]').val("")
                $('textarea[name=deskripsi]').val("")
                $('select[name="id_kategori"]').val("");
                $('#modal-form').modal('show')

                $('.form-subkategori').submit(function(e) {
                    const token = localStorage.getItem('token');
                    const frmdata = new FormData(this);

                    e.preventDefault()
                    $.ajax({
                        url: 'api/subcategories',
                        type: 'POST',
                        data: frmdata,
                        cache: false,
                        contentType: false,
                        processData: false,
                        headers: {
                            "Authorization": 'Bearer ' + token
                        },
                        success: function(data) {

                            if (data.success) {
                                alert('Data Berhasil Simpan')
                                location.reload();
                            }
                        },
                        error: function(err) {
                            alert('Data Gagal Simpan')
                        }
                    })

                })
            });

            $(document).on('click', '.modal-ubah', function() {
                $('#modal-form').modal('show')
                const id = $(this).data('id');

                $.get('api/subcategories/' + id, function({
                    data
                }) {
                    $('input[name=nama_subkategori]').val(data.nama_subkategori);
                    $('select[name="id_kategori"]').val(data.id_kategori);
                    $('textarea[name=deskripsi]').val(data.deskripsi);
                });
                $('.form-subkategori').submit(function(e) {
                    const token = localStorage.getItem('token');
                    const frmdata = new FormData(this);

                    e.preventDefault()
                    $.ajax({
                        url: `api/subcategories/${id}?_method=PUT`,
                        type: 'POST',
                        data: frmdata,
                        cache: false,
                        contentType: false,
                        processData: false,
                        headers: {
                            "Authorization": 'Bearer ' + token
                        },
                        success: function(data) {

                            if (data.success) {
                                alert('Data Berhasil diubah')
                                location.reload();
                            }
                        },
                        error: function(err) {
                            alert('Data Gagal Simpan')
                        }
                    })

                })

            })
        })
    </script>
@endpush
