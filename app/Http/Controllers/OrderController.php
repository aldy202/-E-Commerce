<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['list','konfirmasi_list','baru_list','kemas_list','kirim_list','terima_list','selesai_list']);
        $this->middleware('auth:api')->only(['store', 'update','destroy','ubah_status','konfirmasi','baru','kemas','kirim','terima','selesai']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('member')->get();

        return response()->json([
            'data' => $orders
        ]);
    }

    public function list()
    {

        return view('pesanan.index');
    }
    public function konfirmasi_list()
    {

        return view('pesanan.konfirmasi');
    }
    public function kemas_list()
    {

        return view('pesanan.kemas');
    }
    public function kirim_list()
    {

        return view('pesanan.kirim');
    }
    public function terima_list()
    {

        return view('pesanan.terima');
    }
    public function selesai_list()
    {

        return view('pesanan.selesai');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_member' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(), 422
            );
            # code...
        }

        $input = $request->all();
        $Order = Order::create($input);
        for ($i = 0; $i < count($input('id_product')); $i++) {
            OrderDetail::create([
                'id_order' => $Order->id,
                'id_product' => $input('id_product')[$i],
                'jumlah' => $input('jumlah')[$i],
                'size' => $input('size')[$i],
                'color' => $input('color')[$i],
                'total' => $input('total')[$i],
            ]);
        }
        return response()->json([
            'data' => $Order
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return response()->json([
            'data' => $order
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $Order)
    {
        $validator = Validator::make($request->all(),[
            'id_member' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(), 422
            );
            # code...
        }

        $input = $request->all();
        $Order->update($input);

        OrderDetail::where('id_order', $Order->id)->delete();
        for ($i = 0; $i < count($input('id_product')); $i++) {
            OrderDetail::create([
                'id_order' => $Order->id,
                'id_product' => $input('id_product')[$i],
                'jumlah' => $input('jumlah')[$i],
                'size' => $input('size')[$i],
                'color' => $input('color')[$i],
                'total' => $input('total')[$i],
            ]);
        }
        return response()->json([
            'data' => $Order
        ]);
    }

    public function ubah_status(Request $request, Order $Order)
    {
        $Order->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'success',
            'data' => $Order
        ]);
    }

    public function konfirmasi()
    {
        $orders = Order::with('member')->where('status', 'Dikonfirmasi')->get();
        return response()->json([
            'data' => $orders
        ]);
    }
    public function baru()
    {
        $orders = Order::with('member')->where('status', 'Baru')->get();
        return response()->json([
            'data' => $orders
        ]);
    }
    public function kemas()
    {
        $orders = Order::with('member')->where('status', 'Dikemas')->get();
        return response()->json([
            'data' => $orders
        ]);
    }
    public function kirim()
    {
        $orders = Order::with('member')->where('status', 'Dikirim')->get();
        return response()->json([
            'data' => $orders
        ]);
    }
    public function terima()
    {
        $orders = Order::with('member')->where('status', 'Diterima')->get();
        return response()->json([
            'data' => $orders
        ]);
    }
    public function selesai()
    {
        $orders = Order::with('member')->where('status', 'Selesai')->get();
        return response()->json([
            'data' => $orders
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json([
            'message' => 'Order deleted successfully'
        ]);
    }
}
