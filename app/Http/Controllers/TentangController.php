<?php

namespace App\Http\Controllers;

use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TentangController extends Controller
{
    public function index()
    {
        $about = About::first();
        return view('tentang.index', compact('about'));
    }

    public function update(About $about, Request $request)
    {
        $input = $request->all();
        if ($request->hasFile('logo')){
            File::delete('public/about/' . $about->logo);
            $logo = $request->file('logo');
            $nama_logo = time() . rand(1, 9) . '.' . $logo->getClientOriginalExtension();
            $logo->storeAs('public/about', $nama_logo);
            $input['logo'] = $nama_logo;
        }else {
            unset($input['logo']);
        }

        $about->update($input);

        return redirect('/tentang');
    }
}
