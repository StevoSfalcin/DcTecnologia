<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sales;
use Response;


class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $data['sales'] = Sales::orderBy('id','desc')->paginate(8);

        return view('dashboard',$data);
    }

    public function store(Request $request)
    {

        $saleID = $request->sale_id;
        $sale   =   Sales::updateOrCreate(['id' => $saleID],
                    [
                        'client_cod' => $request->client_cod,
                        'item_description' => $request->item_description,
                        'value' => $request->value,
                        'payment_method' => $request->payment_method,
                        'seller_id' => auth()->id(),
                    ]);

        return Response::json($sale);
    }

    public function edit($id)
    {

        $where = array('id' => $id);
        $sale  = Sales::where($where)->first();

        return Response::json($sale);
    }

    public function destroy($id)
    {

        $data = Sales::where('id',$id)->delete();

        return Response::json($data);
    }
}
