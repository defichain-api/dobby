<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class WebController extends Controller
{
	public function index(): RedirectResponse
	{
		return redirect('https://defichain-dobby.com');
	}
}
