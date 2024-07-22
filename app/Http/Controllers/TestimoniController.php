<?php

namespace App\Http\Controllers;

use App\Models\Testimoni;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class TestimoniController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['list']);
        $this->middleware('auth:api')->only(['store', 'update','destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonis = Testimoni::all();

        return response()->json([
            'success' => true,
            'data' => $testimonis
        ]);
    }

    public function list()
    {

        return view('testimoni.index');
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
            'nama_testimoni' => 'required',
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
            $gambar->storeAs('public/testimonis', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }

        $testimoni = Testimoni::create($input);

        return response()->json([
            'success' => true,
            'data' => $testimoni
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimoni $testimoni)
    {

        return response()->json([
            'data' => $testimoni
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimoni $testimoni)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimoni $testimoni)
    {
        //

        $validator = Validator::make($request->all(),[
            'nama_testimoni' => 'required',
            'deskripsi' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(
                $validator->errors(),422
            );
        }

        $input = $request->all();

        if ($request->hasFile('gambar')){
            File::delete('public/testimonis/' . $testimoni->gambar);
            $gambar = $request->file('gambar');
            $nama_gambar = time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension();
            $gambar->storeAs('public/testimonis', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }else {
            unset($input['gambar']);
        }

        $testimoni->update($input);

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $testimoni
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimoni $testimoni)
    {
        $image_path = public_path().'/storage/testimonis/'.$testimoni->gambar;
        if(File::exists($image_path)){
            File::delete($image_path);
        }

        $testimoni->delete();

        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);


    }
}
