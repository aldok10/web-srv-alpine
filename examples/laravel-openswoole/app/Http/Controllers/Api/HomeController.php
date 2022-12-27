<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\RouteDiscovery\Attributes\Route;
use App\Libraries\RouteDiscovery\Attributes\Prefix;

class HomeController extends Controller
{

    #[Route(method: 'get', uri: 'dashboard')]
    public function index(Request $request)
    {
        return response($request->all());
    }
}
