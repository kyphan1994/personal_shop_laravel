<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login(Request $request) {
        return view('admin.admin_login');
    }
}
