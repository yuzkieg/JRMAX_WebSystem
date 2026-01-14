<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index()
    {
        // Logic to retrieve and display audit logs
        return view('admin.audit.index');
    }
}
