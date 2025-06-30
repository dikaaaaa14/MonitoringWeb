<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RawDataController extends Controller
{
    public function index()
    {
        return view('rawdata.index', ['title' => 'rawdata']);
    }
}