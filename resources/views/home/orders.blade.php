@extends('layout.home')
@section('title', 'Chekcout us')
@section('content')
    <section class="section-wrap checkout pb-70">
        <div class="container relative">
            <div class="row">

                <div class="ecommerce col-xs-12">
                    <h2>My Orders</h2>
                    <table class="table table-ordered table-hover table-striped">
                        <thead>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Grand total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody>

                            @foreach ($orders as $index => $order)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>{{ number_format($order->total) }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td>
                                        <form action="/pesanan_selesai/{{ $order->id }}" method="POST">
                                            @csrf
                                            @if ($order->status != 'Selesai' && $order->status == 'Diterima')
                                            <input type="hidden" name="id_order" value="{{ $order->id }}">
                                            <button class="btn btn-success" type="submit">Selesai</button>
                                        @endif
                                        </form></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h2>My Payments</h2>
                    <table class="table table-ordered table-hover table-striped">
                        <thead>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nominal Transfer</th>
                            <th>Status</th>
                        </thead>
                        <tbody>

                            @foreach ($payments as $index => $payment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $payment->created_at }}</td>
                                    <td>{{ number_format($payment->jumlah) }}</td>
                                    <td>{{ $payment->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div> <!-- end ecommerce -->

            </div> <!-- end row -->
        </div> <!-- end container -->
    </section> <!-- end checkout -->
@endsection


