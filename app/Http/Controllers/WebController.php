<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Redirector;

class WebController extends Controller
{
	public function index(): Redirector
	{
		return redirect('https://defichain-dobby.com');
	}
}
