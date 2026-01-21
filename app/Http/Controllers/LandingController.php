<?php

namespace App\Http\Controllers;

use App\LandingSections;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $sections = LandingSections::all();
        $data = [];

        foreach ($sections as $key => $section) {
            $data[$section->title] = $section->is_enable;
        }

        return view('landing.index', compact('data'));
    }

    public function manage()
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('landing.manage')) {
            $role = $user->roles[0]->slug;
            $sections = LandingSections::all();
            return view('landing.manage', compact('user', 'role', 'sections'));
        }
    }

    public function section_enable($id)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('landing.manage')) {
            $section = LandingSections::find($id);
            $section->is_enable = 0;
            $section->save();

            return redirect(route('landing.manage'))->with('success', 'Section Enabled Successfully.');
        }
    }

    public function section_disable($id)
    {
        $user = Sentinel::getUser();
        if ($user->hasAccess('landing.manage')) {
            $section = LandingSections::find($id);
            $section->is_enable = 1;
            $section->save();

            return redirect(route('landing.manage'))->with('success', 'Section Disabled Successfully.');
        }
    }
}
