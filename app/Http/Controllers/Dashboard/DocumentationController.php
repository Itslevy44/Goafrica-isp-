<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentationController extends Controller
{
    public function index()
    {
        $tenant = app('currentTenant');
        return view('dashboard.docs', compact('tenant'));
    }
}
