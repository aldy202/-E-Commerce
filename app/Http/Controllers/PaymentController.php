<?php

namespace App\Http\Controllers;

use App\Models\Payment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only(['list']);
        $this->middleware('auth:api')->only(['store', 'update','destroy']);
    }
    public function index()
    {
        $payments = Payment::all();

        return response()->json([
            'data' => $payments
        ]);
    }

    public function list()
    {

        return view('payment.index');
    }

    public function show(Payment $payment)
    {

        return response()->json([
            'data' => $payment
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'nama_kategori' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        if($validator->fails()){
            return response()->json(
                $validator->errors(),422
            );
        }

        $input = $request->all();

        if ($request->hasFile('gambar')){
            $gambar = $request->file('gambar');
            $nama_gambar = time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension();
            $gambar->storeAs('public/menus', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }

        $payment = Payment::create($input);

        return response()->json([
            'success' => true,
            'data' => $payment
        ]);
    }

    public function update(Request $request, Payment $payment)
    {

        $validator = Validator::make($request->all(),[
            'tanggal' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(
                $validator->errors(),422
            );
        }

        $payment->update([
            'status' => request('status')
        ]);

        return response()->json([
            'message' => 'success',
            'success' => true,
            'data' => $payment
        ]);
    }

    public function destroy(Payment $payment)
    {

        $image_path = public_path().'/storage/menus/'.$payment->gambar;
        if (File::exists($image_path)) {
            File::delete($image_path);
        }
        // File::delete('public/menus/' . $payment->gambar);
        $payment->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }
}
