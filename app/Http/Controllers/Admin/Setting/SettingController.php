<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Services\Image\ImageService;
use App\Models\Setting\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $setting = Setting::first();
        return $setting;
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id, ImageService $imageService)
    {
        $setting = Setting::first();

        $inputs = $request->all();
        if ($request->hasFile('logo')) {
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'logo');
            $result = $imageService->save($request->file('logo'));
            if ($result) {
                $inputs['logo'] = $result;
            } else {
                return response()->json(['error' => 'Failed to upload logo'], 500);
            }
        }

        if ($request->hasFile('favicon')) {
            $imageService->setExclusiveDirectory('images' . DIRECTORY_SEPARATOR . 'settings' . DIRECTORY_SEPARATOR . 'favicon');
            $result = $imageService->save($request->file('favicon'));
            if ($result) {
                $inputs['favicon'] = $result;
            } else {
                return response()->json(['error' => 'Failed to upload favicon'], 500);
            }
        }

        $setting->update($inputs);
        return $setting;
    }
}
