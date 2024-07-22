<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only(['list']);
        $this->middleware('auth:api')->only(['store', 'update','destroy']);
    }
    public function index()
    {
        $slider = SLider::all();

        return response()->json([
            'data' => $slider
        ]);
    }

    public function list()
    {
        $sliders = Slider::all();
        return view('slider.index', compact('sliders'));
    }

    public function show(Slider $slider)
    {

        return response()->json([
            'data' => $slider
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'nama_slider' => 'required',
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
            $gambar->storeAs('public/sliders', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }

        $slider = Slider::create($input);

        return response()->json([
            'success' => true,
            'data' => $slider
        ]);
    }

    public function update(Request $request, Slider $slider)
    {

        $validator = Validator::make($request->all(),[
            'nama_slider' => 'required',
            'deskripsi' => 'required',

        ]);

        if($validator->fails()){
            return response()->json(
                $validator->errors(),422
            );
        }

        $input = $request->all();

        if ($request->hasFile('gambar')){
            File::delete('public/sliders/' . $slider->gambar);
            $gambar = $request->file('gambar');
            $nama_gambar = time() . rand(1, 9) . '.' . $gambar->getClientOriginalExtension();
            $gambar->storeAs('public/sliders', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }else {
            unset($input['gambar']);
        }

        $slider->update($input);

        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $slider
        ]);
    }

    public function destroy(Slider $slider)
    {
        $image_path = public_path().'/storage/menus/'.$slider->gambar;
        if (File::exists($image_path)) {
            File::delete($image_path);
        }
        // File::delete('public/menus/' . $slider->gambar);
        $slider->delete();

        return response()->json([
            'success' => true,
            'message' => 'success'
        ]);
    }
}
