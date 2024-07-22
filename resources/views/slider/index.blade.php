@extends('layout.app')

@section('title', 'Data Slider')

@section('content')

    <div class="card shadow">
        <div class="card-header">
            <h4 class="card-title">
                Data Slider
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
                            <th>Nama Slider</th>
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
                    <h5 class="modal-title">Form Slider</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-slider">
                                <div class="form-group">
                                    <label for="nama_slider">Nama Slider</label>
                                    <input type="text" name="nama_slider" id="nama_slider"
                                        placeholder="Nama slider" class="form-control" required>
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
                url: '/api/slider',
                success: function({
                    data
                }) {
                    let row;
                    data.map(function(val, index) {
                        row += `
                        <tr>
                            <td>${index+1 }</td>
                            <td>${val.nama_slider}</td>
                            <td>${val.deskripsi}</td>
                            <td>
                                <img src="/storage/sliders/${val.gambar}" width="100" alt="Category Image">
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
                        url: `/api/slider/` + id,
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
                $('input[name=nama_slider]').val("")
                $('textarea[name=deskripsi]').val("")

                $('#modal-form').modal('show')

                $('.form-slider').submit(function(e) {
                    const token = localStorage.getItem('token');
                    const frmdata = new FormData(this);

                    e.preventDefault()
                    $.ajax({
                        url: 'api/slider',
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
                $.get('api/slider/' + id, function(data) {
                    $('input[name=nama_slider]').val(data.data.nama_slider);
                    $('textarea[name=deskripsi]').val(data.data.deskripsi);
                });
                $('.form-slider').submit(function(e) {
                    const token = localStorage.getItem('token');
                    const frmdata = new FormData(this);

                    e.preventDefault()
                    $.ajax({
                        url: `api/slider/${id}?_method=PUT`,
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
