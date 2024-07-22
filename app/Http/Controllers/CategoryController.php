<?php

namespace App\Http\Controllers;

use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only(['list']);
        $this->middleware('auth:api')->only(['store', 'update','destroy']);
    }
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            'data' => $categories
        ]);
    }

    public function list()
    {

        return view('kategori.index');
    }

    public function show(Category $category)
    {

        return response()->json([
            'data' => $category
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

        $category = Category::create($input);

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    public function update(Request $request, Category $category)
    {

        $validator = Validator::make($request->all(),[
            'nama_kategori' => 'required',
            'deskripsi' => 'required',

        ]);

        if($validator->fails()){
            return response()->json(
                $validator->errors(),422
            );
        }

        $input = $request->all();

        if ($request->hasFile('gambar')){
            File::delete('public/menus/' . $category->gambar);
            $gambar = $request->file('gambar');
            $nama_gambar = time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension();
            $gambar->storeAs('public/menus', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }else {
            unset($input['gambar']);
        }

        $category->update($input);

        return response()->json([
            'message' => 'success',
            'success' => true,
            'data' => $category
        ]);
    }

    public function destroy(Category $category)
    {

        $image_path = public_path().'/storage/menus/'.$category->gambar;
        if (File::exists($image_path)) {
            File::delete($image_path);
        }
        // File::delete('public/menus/' . $category->gambar);
        $category->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }
}
