<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PurchaseResource;
use App\Models\Doctor;
use App\Models\Order;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportsController extends Controller
{
    // public function doctorOrders(Request $request, $id){
    //     $data = $request->all();
    //     $validator = Validator::make($data, [
    //         'start_date' => 'required',
    //         'end_date' => 'required'
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'errors' => $validator->errors()
    //         ], 200);
    //     }
    //     $doctor = Doctor::find($id);
    // }

    // public function dailyReport(){
    //     $start = Carbon::now()->subWeek()->startOfWeek();
    //     $end = Carbon::now()->subWeek()->endOfWeek();
    //     $orders = Order::whereMonth('created_at', date('m'))
    //     ->whereYear('created_at', date('Y'))->get();
    //     return $orders;
    // }

    public function reports(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 200);
        }
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];

        $orders = Order::whereBetween('created_at', [$start_date, $end_date])->get();
        $ordersPrice = 0;
        foreach ($orders as $order) {
            $ordersPrice +=  $order->type->price;
        }

        $purchases = Purchase::whereBetween('created_at', [$start_date, $end_date])->get();
        $purchasesPrice = 0;
        foreach ($purchases as $purchase) {
            $purchasesPrice +=  $purchase->price;
        }

        return response()->json([
            'success' => true,
            'ordersPrice' => $ordersPrice,
            'purchasesPrice' => $purchasesPrice,
            'orders' => OrderResource::collection($orders),
            'purchases' => PurchaseResource::collection($purchases)
        ], 200);
    }
}
