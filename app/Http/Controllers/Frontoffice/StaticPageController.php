<?php

namespace App\Http\Controllers\Frontoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaticPageController extends Controller
{
    public function aide()
    {
        $layout = Auth::check() ? 'FrontOffice.layout1.app' : 'FrontOffice.layout.app';
        return view('FrontOffice.pages.aide', compact('layout'));
    }

    public function contact()
    {
        $layout = Auth::check() ? 'FrontOffice.layout1.app' : 'FrontOffice.layout.app';
        return view('FrontOffice.pages.contact', compact('layout'));
    }

    public function support()
    {
        $layout = Auth::check() ? 'FrontOffice.layout1.app' : 'FrontOffice.layout.app';
        return view('FrontOffice.pages.support', compact('layout'));
    }

    public function mobileApp()
    {
        $layout = Auth::check() ? 'FrontOffice.layout1.app' : 'FrontOffice.layout.app';
        return view('FrontOffice.pages.mobile-app', compact('layout'));
    }

    public function confidentialite()
    {
        $layout = Auth::check() ? 'FrontOffice.layout1.app' : 'FrontOffice.layout.app';
        return view('FrontOffice.pages.confidentialite', compact('layout'));
    }

    public function cgu()
    {
        $layout = Auth::check() ? 'FrontOffice.layout1.app' : 'FrontOffice.layout.app';
        return view('FrontOffice.pages.cgu', compact('layout'));
    }
}
