<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Doctor;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        if (count($orders) > 0) {
            return response()->json([
                'success' => true,
                'orders' => OrderResource::collection($orders)
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no orders yet!'
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        $data['user_id'] = $user->id;
        $validator = Validator::make($data, [
            'doctor_id' => 'required',
            'type_id' => 'required',
            'color_id' => 'required',
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 200);
        }
        $order = Order::create($data);
        return response()->json([
            'success' => true,
            'order' => new OrderResource($order)
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $data = $request->all();
        $user = Auth::user();
        $data['edited_by'] = $user->id;

        if ($order) {
            $validator = Validator::make($data, [
                'doctor_id' => 'required',
                'type_id' => 'required',
                'color_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 200);
            }
            $order->update($data);
            return response()->json([
                'success' => true,
                'order' => new OrderResource($order)
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no such order!'
            ], 200);
        }
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->delete();
            return response()->json([
                'success' => true,
                'message' => 'order has been deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no such order!'
            ], 200);
        }
    }

    public function doctorOrders(Request $request, $id){
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
        $doctor = Doctor::find($id);

    }
}
