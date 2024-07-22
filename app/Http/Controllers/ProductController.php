<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['list']);
        $this->middleware('auth:api')->only(['store', 'update','destroy']);
    }
    public function index()
    {

        $products = Product::with('category', 'subcategory')->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function list()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('product.index', compact('categories', 'subcategories'));
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
            'id_kategori' => 'required',
            'id_subkategori' => 'required',
            'nama_barang' => 'required',
            'gambar' => 'required|image|mimes:png,jpg,jpeg|max:2048',
            'deskripsi' => 'required',
            'harga' => 'required',
            'diskon' => 'required',
            'bahan' => 'required',
            'tags' => 'required',
            'sku' => 'required',
            'ukuran' => 'required',
            'warna' => 'required',

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
            $gambar->storeAs('public/products', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }

        $product = Product::create($input);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {

        return response()->json([
            'data' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {

        $validator = Validator::make($request->all(),[
            'id_kategori' => 'required',
            'id_subkategori' => 'required',
            'nama_barang' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required',
            'diskon' => 'required',
            'bahan' => 'required',
            'tags' => 'required',
            'sku' => 'required',
            'ukuran' => 'required',
            'warna' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(
                $validator->errors(),422
            );
        }

        $input = $request->all();

        if ($request->hasFile('gambar')){
            File::delete('public/products/' . $product->gambar);
            $gambar = $request->file('gambar');
            $nama_gambar = time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension();
            $gambar->storeAs('public/products', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }else {
            unset($input['gambar']);
        }

        $product->update($input);

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $product
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $image_path = public_path().'/storage/products/'.$product->gambar;
        File::delete($image_path);

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);
    }
}
