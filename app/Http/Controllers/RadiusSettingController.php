<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\RadiusSetting;

class RadiusSettingController extends Controller
{
    public function index()
    {
        $radiusSettings = RadiusSetting::orderBy('id', 'DESC')->get();

        return view('admin.radius', compact('radiusSettings'));
    }
    public function update(Request $request, $id)
{
    $request->validate([
        'value' => 'required|numeric|min:1|max:100'
    ]);

    RadiusSetting::where('id', $id)->update([
        'value' => $request->value
    ]);

    cache()->forget('store_search_radius');

    return redirect()->back()->with('success', 'Radius updated successfully');
}

}