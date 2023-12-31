<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderRequest;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $orders = OrderRequest::where('status', 0)->paginate(6);
        return view('admin.index', get_defined_vars());
    }


    public function orderJson($id)
    {
        $order = OrderRequest::find($id);

        return response()->json($order);
    }
}
