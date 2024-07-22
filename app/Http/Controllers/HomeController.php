<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Testimoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
        $categories = Category::all();
        $testimonis = Testimoni::all();
        $products = Product::skip(0)->take(8)->get();

        return view('home.index', compact('sliders', 'categories', 'testimonis', 'products'));
    }
    public function products($id_subkategori)
    {

        $products = Product::where('id_subkategori', $id_subkategori)->get();

        return view('home.products', compact('products'));
    }

    public function add_to_cart(Request $request)
    {
        try {
            $input = $request->all();

            // Proses penyimpanan ke keranjang
            Cart::create($input);

            return response()->json(['info' => 'success', 'message' => 'Produk berhasil ditambahkan ke keranjang']);
        } catch (\Exception $e) {

            return response()->json(['info' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function delete_from_cart(Cart $cart)
    {
        $cart->delete();

        return redirect('/cart');
    }

    public function product($id_product)
    {

        $product = Product::find($id_product);
        $latest_product = Product::orderBydesc('created_at')->take(8)->get();
        return view('home.product', compact('product', 'latest_product'));
    }
    public function cart()
    {
        if (!Auth::guard('webmember')->user()) {
            return redirect('/login_member');
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: f67e0e556e16ff329da0e17d5357dd2f"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }
        $provinsi = json_decode($response);
        $carts = Cart::where('id_member', Auth::guard('webmember')->user()->id)->where('is_checkout', 0)->get();
        $cart_total = Cart::where('id_member', Auth::guard('webmember')->user()->id)->where('is_checkout', 0)->sum('total');


        return view('home.cart', compact('carts', 'provinsi', 'cart_total'));
    }
    public function get_kota($id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/city?province=" . $id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: f67e0e556e16ff329da0e17d5357dd2f"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    public function get_ongkir($destination, $weight)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/cost",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "origin=501&destination=".$destination."&weight=".$weight."&courier=jne",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded",
                "key: f67e0e556e16ff329da0e17d5357dd2f"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    public function checkout_order(Request $request)
    {
        $id = DB::table('orders')->insertGetId([
            'id_member' => $request->id_member,
            'invoice' => date('ymds'),
            'total' => $request->grand_total,
            'status' => 'Baru',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        for($i = 0; $i < count($request->id_product); $i++) {
            DB::table('orders_detail')->insert([
                'id_order' => $id,
                'id_product' => $request->id_product[$i],
                'jumlah' => $request->jumlah[$i],
                'size' => $request->size[$i],
                'color' => $request->color[$i],
                'total' => $request->total[$i],
                'created_at' => date('Y-m-d H:i:s'),

            ]);

        }
        Cart::where('id_member', Auth::guard('webmember')->user()->id)->update([
            'is_checkout' => 1
        ]);

    }
    public function checkout()
    {
        $about = About::first();
        $orders = Order::where('id_member', Auth::guard('webmember')->user()->id)
                  ->where('status', 'Baru') // Filter berdasarkan status "Baru"
                  ->orderBy('created_at', 'desc') // Urutkan berdasarkan waktu pembuatan terbaru
                  ->first();  // Ambil baris kedua

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "key: f67e0e556e16ff329da0e17d5357dd2f"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }
        $provinsi = json_decode($response);
        return view('home.checkout', compact('about', 'orders', 'provinsi'));
    }

    public function payment(Request $request)
    {
        Payment::create([
            'id_order' => $request->id_order,
            'id_member' => Auth::guard('webmember')->user()->id,
            'jumlah' => $request->jumlah,
            'provinsi' => $request->provinsi,
            'kabupaten' => $request->kabupaten,
            'kecamatan' => "",
            'detail_alamat' => $request->detail_alamat,
            'status' => 'MENUNGGU',
            'no_rekening' => $request->no_rekening,
            'atas_nama' => $request->atas_nama,
        ]);
        return redirect('/orders');
    }

    public function orders()
    {
        $orders = Order::where('id_member', Auth::guard('webmember')->user()->id)->get();
        $payments = Payment::where('id_member', Auth::guard('webmember')->user()->id)->get();
        return view('home.orders', compact('orders', 'payments'));
    }

    public function pesanan_selesai(Order $order)
    {
        $order->status = 'Selesai';
        $order->save();
        return redirect('/orders');
    }


    public function about()
    {
        $about = About::first();
        $testimonis = Testimoni::all();
        return view('home.about', compact('about', 'testimonis'));
    }
    public function contact()
    {
        $about = About::first();
        return view('home.contact', compact('about'));
    }
    public function faq()
    {
        return view('home.faq');
    }
}
