@extends('layout.app')
@section('title')
    Tentang
@endsection

@section('content')
    <div class="card shadow">
        <div class="card-header">
            <h4 class="card-title">
                Data Website
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-tentang" method="POST" enctype="multipart/form-data" action="/tentang/{{ $about->id ?? '' }}">
                        @csrf

                        <div class="form-group">
                            <label for="judul_website">Nama Website</label>
                            <input type="text" name="judul_website" id="judul_website" placeholder="Judul Website"
                                class="form-control" required value="{{ $about->judul_website ?? '' }}">
                        </div>
                        <img src="/storage/about/{{ $about->logo ?? '' }}" alt="" width="260">
                        <div class="form-group">
                            <label for="logo">Logo</label>
                            <input type="file" name="logo" id="logo" class="form-control" id="logo">
                        </div>
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea type="text" name="deskripsi" id="deskripsi" cols="30" rows="10" placeholder="Deskripsi" required
                                class="form-control">{{$about->deskripsi ?? ''}}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="atas_nama">Atas Nama</label>
                            <input type="text" name="atas_nama" id="atas_nama" cols="30" rows="10" placeholder="atas_nama" required
                                class="form-control" value="{{ $about->atas_nama ?? ''}}"></input>
                        </div>
                        <div class="form-group">
                            <label for="no_rekening">No Rekening</label>
                            <input type="text" name="no_rekening" id="no_rekening" cols="30" rows="10" placeholder="no_rekening" required
                                class="form-control" value="{{ $about->no_rekening ?? ''}}"></input>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" name="alamat" id="alamat" cols="30" rows="10" placeholder="Alamat" required
                                class="form-control" value="{{ $about->alamat ?? ''}}"></input>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" cols="30" rows="10" placeholder="Email" required
                                class="form-control" value="{{ $about->email ?? ''}}"></input>
                        </div>
                        <div class="form-group">
                            <label for="telepon">Telepon</label>
                            <input type="telepon" name="telepon" id="telepon" cols="30" rows="10" placeholder="Telepon" required
                                class="form-control" value="{{ $about->telepon ?? ''}}"></input>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

