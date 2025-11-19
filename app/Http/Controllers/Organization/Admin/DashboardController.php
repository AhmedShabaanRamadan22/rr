<?php

namespace App\Http\Controllers\Organization\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // $user = User::findOrFail(auth()->user()->id);
        // $organization = $user->organization;

        // return view('organization.index',compact('user','organization'));
    }
    
    public function profile(){
        // $user = User::findOrFail(auth()->user()->id);
        // $organization = $user->organization;
    
        // return view('organization.profile',compact('user','organization'));

    }
}
