<?php

namespace App\Http\Controllers;

use App\AppSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class AppSettingController extends Controller
{
    public function index()
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('setting.edit')) {
            $role = $user->roles[0]->slug;
            $data = AppSettings::first();
            return view('setting.setting', compact('user', 'role', 'data'));
        }
    }

    public function update(Request $request)
    {
        $setting = AppSettings::first();
        // validate the data
        $request->validate([
            'title' => "required|min:5|max:40",
            'footer_left' => "required|min:5|max:40",
            'footer_right' => "required|min:5|max:80",
        ]);

        if ($request->logo_sm &&  $request->logo_sm != null) {
            $request->validate([
                'logo_sm' => "max:2048|mimes:jpg,png,svg",
            ]);
        }

        if ($request->logo_lg &&  $request->logo_lg != null) {
            $request->validate([
                'logo_lg' => "max:2048|mimes:jpg,png,svg",
            ]);
        }

        if ($request->logo_dark_sm &&  $request->logo_dark_sm != null) {
            $request->validate([
                'logo_dark_sm' => "max:2048|mimes:jpg,png,svg",
            ]);
        }

        if ($request->logo_dark_lg &&  $request->logo_dark_lg != null) {
            $request->validate([
                'logo_dark_lg' => "max:2048|mimes:jpg,png,svg",
            ]);
        }

        if ($request->favicon &&  $request->favicon != null) {
            $request->validate([
                'favicon' => "max:2048|mimes:jpg,png,svg,ico",
            ]);
        }

        try {

            $update = AppSettings::first();
            $update->title = $request->title;
            $update->footer_left = $request->footer_left;
            $update->footer_right = $request->footer_right;

            // light logo small
            if ($request->hasfile('logo_sm')) {
                $des = 'build/images/' . $setting->logo_sm;
                if (File::exists($des)) {
                    File::delete($des);
                }
                $logo_sm = $request->file('logo_sm');
                $logo_small = 'logo-light1.' . $logo_sm->getClientOriginalExtension();
                $logo_sm->move(public_path('build/images'), $logo_small);
                $update->logo_sm = $logo_small;
            } else {
                $update->logo_sm = $setting->logo_sm;
            }

            // light logo large

            if ($request->hasfile('logo_lg')) {
                $des = 'build/images/' . $setting->logo_lg;
                if (File::exists($des)) {
                    File::delete($des);
                }
                $logo_lg = $request->file('logo_lg');
                $logo_large = 'logo-light.' . $logo_lg->getClientOriginalExtension();
                $logo_lg->move(public_path('build/images'), $logo_large);
                $update->logo_lg = $logo_large;
            } else {
                $update->logo_lg = $setting->logo_lg;
            }

            // dark logo small

            if ($request->hasfile('logo_dark_sm')) {
                $des = 'build/images/' . $setting->logo_dark_sm;
                if (File::exists($des)) {
                    File::delete($des);
                }
                $logo_dark_sm = $request->file('logo_dark_sm');
                $logo_dark_small ='logo-dark1.' . $logo_dark_sm->getClientOriginalExtension();
                $logo_dark_sm->move(public_path('build/images'), $logo_dark_small);
                $update->logo_dark_sm = $logo_dark_small;
            } else {
                $update->logo_dark_sm = $setting->logo_dark_sm;
            }

            // dark logo large

            if ($request->hasfile('logo_dark_lg')) {
                $des = 'build/images/' . $setting->logo_dark_lg;
                if (File::exists($des)) {
                    File::delete($des);
                }
                $logo_dark_lg = $request->file('logo_dark_lg');
                $logo_dark_large = 'logo-dark.' . $logo_dark_lg->getClientOriginalExtension();
                $logo_dark_lg->move(public_path('build/images'), $logo_dark_large);
                $update->logo_dark_lg = $logo_dark_large;
            } else {
                $update->logo_dark_lg = $setting->logo_dark_lg;
            }

            // favicon

            if ($request->hasfile('favicon')) {
                $des = 'build/images/' . $setting->favicon;
                if (File::exists($des)) {
                    File::delete($des);
                }
                $favicon = $request->file('favicon');
                $app_favicon = 'favicon.' . $favicon->getClientOriginalExtension();
                $favicon->move(public_path('build/images'), $app_favicon);
                $update->favicon = $app_favicon;
            } else {
                $update->favicon = $setting->favicon;
            }

            $update->save();

            return redirect()->back()->with('success', 'Setting updated successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'something went wrong.!');
        }
    }
}
