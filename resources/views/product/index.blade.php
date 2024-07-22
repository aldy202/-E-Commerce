@extends('layout.app')

@section('title', 'Data product')

@section('content')

    <div class="card shadow">
        <div class="card-header">
            <h4 class="card-title">
                Data Product
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
                            <th>Kategori</th>
                            <th>Subkategori</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Diskon</th>
                            <th>Bahan</th>
                            <th>Sku</th>
                            <th>Ukuran</th>
                            <th>Warna</th>
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
                    <h5 class="modal-title">Form Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-product">
                                <div class="form-group">
                                    <label for="">Kategori</label>
                                    <select name="id_kategori" id="id_kategori" class="form-control">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Sub Kategori</label>
                                    <select name="id_subkategori" id="id_subkategori" class="form-control">
                                        @foreach ($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->nama_subkategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama_barang">Nama Barang</label>
                                    <input type="text" name="nama_barang" id="nama_barang" placeholder="Nama barang"
                                        class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="harga">Harga</label>
                                    <input type="number" name="harga" id="harga" class="form-control" id="harga">
                                </div>
                                <div class="form-group">
                                    <label for="diskon">Diskon</label>
                                    <input type="number" name="diskon" id="diskon" class="form-control" id="diskon">
                                </div>
                                <div class="form-group">
                                    <label for="bahan">Bahan</label>
                                    <input type="text" name="bahan" id="bahan" class="form-control" id="bahan">
                                </div>
                                <div class="form-group">
                                    <label for="tags">Tags</label>
                                    <input type="text" name="tags" id="tags" class="form-control" id="tags">
                                </div>
                                <div class="form-group">
                                    <label for="sku">Sku</label>
                                    <input type="text" name="sku" id="sku" class="form-control" id="sku">
                                </div>
                                <div class="form-group">
                                    <label for="ukuran">Ukuran</label>
                                    <input type="text" name="ukuran" id="ukuran" class="form-control" id="ukuran">
                                </div>
                                <div class="form-group">
                                    <label for="warna">Warna</label>
                                    <input type="text" name="warna" id="warna" class="form-control" id="warna">
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi">Dekripsi</label>
                                    <textarea type="text" name="deskripsi" id="deskripsi" cols="30" rows="10" placeholder="Deskripsi" required
                                        class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="gambar">Gambar</label>
                                    <input type="file" name="gambar" id="gambar" class="form-control"
                                        id="gambar">
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
                url: '/api/products',
                success: function({
                    data
                }) {
                    let row;
                    data.map(function(val, index) {
                        row += `
                        <tr>
                            <td>${index+1 }</td>
                            <td>${val.category.nama_kategori}</td>
                            <td>${val.subcategory.nama_subkategori}</td>
                            <td>${val.nama_barang}</td>
                            <td>${val.harga}</td>
                            <td>${val.diskon}</td>
                            <td>${val.bahan}</td>
                            <td>${val.sku}</td>
                            <td>${val.ukuran}</td>
                            <td>${val.warna}</td>
                            <td>
                                <img src="/storage/products/${val.gambar}" width="100" alt="Category Image">
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
                        url: `/api/products/` + id,
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
                $('input[name=nama_product]').val("")
                $('textarea[name=deskripsi]').val("")

                $('#modal-form').modal('show')

                $('.form-product').submit(function(e) {
                    const token = localStorage.getItem('token');
                    const frmdata = new FormData(this);

                    e.preventDefault()
                    $.ajax({
                        url: 'api/products',
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
                $.get('api/products/' + id, function({
                    data
                }) {
                    $('select[name=id_kategori]').val(data.id_kategori);
                    $('select[name=id_subkategori]').val(data.id_subkategori);
                    $('input[name=nama_barang]').val(data.nama_barang);
                    $('input[name=harga]').val(data.harga);
                    $('input[name=diskon]').val(data.diskon);
                    $('input[name=bahan]').val(data.bahan);
                    $('input[name=tags]').val(data.tags);
                    $('input[name=sku]').val(data.sku);
                    $('input[name=ukuran]').val(data.ukuran);
                    $('input[name=warna]').val(data.warna);
                    $('textarea[name=deskripsi]').val(data.deskripsi);
                });
                $('.form-product').submit(function(e) {
                    const token = localStorage.getItem('token');
                    const frmdata = new FormData(this);

                    e.preventDefault()
                    $.ajax({
                        url: `api/products/${id}?_method=PUT`,
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
