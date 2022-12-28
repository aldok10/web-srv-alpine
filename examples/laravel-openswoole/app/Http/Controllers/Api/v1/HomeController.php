<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\RouteDiscovery\Attributes\Route;
use App\Libraries\RouteDiscovery\Attributes\Prefix;

#[Prefix(prefix: 'api/v1/beranda')]
class HomeController extends Controller
{

    #[Route(method: 'post', uri: 'dashboard')]
    public function index(Request $request)
    {
        return response($request->all());
    }
}
