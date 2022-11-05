<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PurchaseResource;
use App\Models\Doctor;
use App\Models\Order;
use App\Models\Provider;
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

    public function reports(Request $request)
    {
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

        if ($ordersPrice > 0 || $purchasesPrice > 0) {
            return response()->json([
                'success' => true,
                'ordersPrice' => $ordersPrice,
                'purchasesPrice' => $purchasesPrice,
                'orders' => OrderResource::collection($orders),
                'purchases' => PurchaseResource::collection($purchases)
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'sorry there is no orders or purchases in the selected date!'
            ], 200);
        }
    }

    public function orderReports(Request $request)
    {
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

        if ($ordersPrice > 0) {
            return response()->json([
                'success' => true,
                'ordersPrice' => $ordersPrice,
                'orders' => OrderResource::collection($orders),
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'sorry there is no orders in the selected date!'
            ], 200);
        }
    }

    public function purchaseReports(Request $request)
    {
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

        $purchases = Purchase::whereBetween('created_at', [$start_date, $end_date])->get();
        $purchasesPrice = 0;
        foreach ($purchases as $purchase) {
            $purchasesPrice +=  $purchase->price;
        }

        if ($purchasesPrice > 0) {
            return response()->json([
                'success' => true,
                'purchasesPrice' => $purchasesPrice,
                'purchases' => PurchaseResource::collection($purchases)
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'sorry there is no purchases in the selected date!'
            ], 200);
        }
    }

    public function doctorReports(Request $request, $id)
    {
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

        $doctor = Doctor::find($id);
        if ($doctor) {
            $orders = Order::where('doctor_id', $id)->whereBetween('created_at', [$start_date, $end_date])->get();
            $ordersPrice = 0;
            foreach ($orders as $order) {
                $ordersPrice +=  $order->type->price;
            }

            if ($ordersPrice > 0) {
                return response()->json([
                    'success' => true,
                    'doctor' => $doctor->name,
                    'ordersPrice' => $ordersPrice,
                    'orders' => OrderResource::collection($orders),
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'sorry there is no orders for this doctor in the selected date!'
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no such doctor!'
            ], 200);
        }
    }

    public function providerReports(Request $request, $id)
    {
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

        $provider = Provider::find($id);
        if ($provider) {
            $purchases = Purchase::where('provider_id', $id)->whereBetween('created_at', [$start_date, $end_date])->get();
            $purchasesPrice = 0;
            foreach ($purchases as $purchase) {
                $purchasesPrice +=  $purchase->price;
            }

            if ($purchasesPrice > 0) {
                return response()->json([
                    'success' => true,
                    'provider' => $provider->name,
                    'purchasesPrice' => $purchasesPrice,
                    'purchases' => PurchaseResource::collection($purchases)
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'sorry there is no purchases by this provider in the selected date!'
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no such provider!'
            ], 200);
        }
    }
}
