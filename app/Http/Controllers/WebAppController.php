<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class WebAppController extends Controller
{
	public function index(): View
	{
		return view('spa');
	}
}
