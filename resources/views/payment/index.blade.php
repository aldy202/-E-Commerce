@extends('layout.app')

@section('title', 'Data Pembayaran')

@section('content')

    <div class="card shadow">
        <div class="card-header">
            <h4 class="card-title">
                Data Pembayaran
            </h4>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Order</th>
                            <th>Jumlah</th>
                            <th>No Rekening</th>
                            <th>Atas Nama</th>
                            <th>Status</th>
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
                    <h5 class="modal-title">Form Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-kategori">
                                <div class="form-group">
                                    <label for="nama_kategori">Tanggal</label>
                                    <input type="text" name="tanggal" id="tanggal" placeholder="tanggal"
                                        class="form-control" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi">Jumlah</label>
                                    <input type="text" name="jumlah" id="jumlah" placeholder="jumlah"
                                        class="form-control" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi">No rekening</label>
                                    <input type="text" name="no_rekening" id="no_rekening" placeholder="no_rekening"
                                        class="form-control" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi">Atas Nama</label>
                                    <input type="text" name="atas_nama" id="atas_nama" placeholder="atas_nama"
                                        class="form-control" required readonly>
                                </div>
                                <div class="form-group">
                                    <select name="status" id="status" class="form-control">
                                        <option value="DITERIMA">DITERIMA</option>
                                        <option value="DITOLAK">DITOLAK</option>
                                        <option value="MENUNGGU">MENUNGGU</option>
                                    </select>
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
            function formatdate(date) {
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                    'September', 'Oktober', 'November', 'Desember'
                ];
                const d = new Date(date);
                const day = d.getDate();
                const month = d.getMonth();
                const year = d.getFullYear();
                return day + ' ' + months[month] + ' ' + year
            }
            $.ajax({
                url: '/api/payments',
                success: function({
                    data
                }) {
                    let row;
                    data.map(function(val, index) {
                        row += `
                        <tr>
                            <td>${index+1 }</td>
                            <td>${formatdate(val.created_at)}</td>
                            <td>${val.id_order}</td>
                            <td>${val.jumlah}</td>
                            <td>${val.no_rekening}</td>
                            <td>${val.atas_nama}</td>
                            <td>${val.status}</td>
                            <td>
                                <a data-toggle="modal" href="#modal-form" data-id="${val.id}" class="btn btn-warning modal-ubah">Edit</a>

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
                        url: `/api/subpayments/` + id,
                        type: 'DELETE',
                        headers: {
                            "Authorization": token
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



            $(document).on('click', '.modal-ubah', function() {
                $('#modal-form').modal('show')
                const id = $(this).data('id');
                $.get('api/payments/' + id, function(data) {
                    $('input[name=tanggal]').val(formatdate(data.data.created_at));
                    $('input[name=jumlah]').val(data.data.jumlah);
                    $('input[name=no_rekening]').val(data.data.no_rekening);
                    $('input[name=atas_nama]').val(data.data.atas_nama);
                    $('select[name=status]').val(data.data.status);
                });
                $('.form-kategori').submit(function(e) {
                    const token = localStorage.getItem('token');
                    const frmdata = new FormData(this);

                    e.preventDefault()
                    $.ajax({
                        url: `api/payments/${id}?_method=PUT`,
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
