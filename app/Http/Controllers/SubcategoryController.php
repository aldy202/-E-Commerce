<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only(['list']);
        $this->middleware('auth:api')->only(['store', 'update','destroy']);
    }
    public function index()
    {
        $subcategories = Subcategory::with('category')->get();


        return response()->json([
            'data' => $subcategories
        ]);
    }

    public function list()
    {
        $categories = Category::all();
        return view('subkategori.index', compact('categories'));
    }

    public function show(Subcategory $subcategory)
    {

        return response()->json([
            'data' => $subcategory
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'id_kategori' => 'required',
            'nama_subkategori' => 'required',
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
            $gambar->storeAs('public/submenus', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }

        $subcategory = Subcategory::create($input);

        return response()->json([
            'success' => true,
            'data' => $subcategory
        ]);
    }

    public function update(Request $request, Subcategory $subcategory)
    {

        $validator = Validator::make($request->all(),[
            'id_kategori' => 'required',
            'nama_subkategori' => 'required',
            'deskripsi' => 'required',

        ]);

        if($validator->fails()){
            return response()->json(
                $validator->errors(),422
            );
        }

        $input = $request->all();

        if ($request->hasFile('gambar')){
            File::delete('public/submenus/' . $subcategory->gambar);
            $gambar = $request->file('gambar');
            $nama_gambar = time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension();
            $gambar->storeAs('public/submenus', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }else {
            unset($input['gambar']);
        }

        $subcategory->update($input);

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $subcategory
        ]);
    }

    public function destroy(Subcategory $subcategory)
    {
        $image_path = public_path().'/storage/submenus/'.$subcategory->gambar;
        if (File::exists($image_path)) {
            File::delete($image_path);
        }
        // File::delete('public/submenus/' . $subcategory->gambar);
        $subcategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);
    }
}
