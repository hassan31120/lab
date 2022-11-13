<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PurchaseResource;
use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    public function index()
    {
        Carbon::setLocale('ar');
        $purchases = Purchase::all();
        if (count($purchases) > 0) {
            return response()->json([
                'success' => true,
                'purchases' => PurchaseResource::collection($purchases)
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no purchases yet!'
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'price' => 'required|numeric',
            'amount' => 'required|numeric',
            'provider_id' => 'required|exists:providers,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 200);
        }
        $data['total_price'] = $data['amount'] * $data['price'];
        $purchase = Purchase::create($data);
        return response()->json([
            'success' => true,
            'purchase' => new PurchaseResource($purchase)
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $purchase = Purchase::find($id);
        $data = $request->all();
        if ($purchase) {
            $validator = Validator::make($data, [
                'name' => 'required',
                'price' => 'required|numeric',
                'amount' => 'required|numeric',
                'provider_id' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 200);
            }
            $data['total_price'] = $data['amount'] * $data['price'];
            $purchase->update($data);
            return response()->json([
                'success' => true,
                'purchase' => new PurchaseResource($purchase)
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no such purchase!'
            ], 200);
        }
    }

    public function destroy($id)
    {
        $purchase = Purchase::find($id);
        if ($purchase) {
            $purchase->delete();
            return response()->json([
                'success' => true,
                'message' => 'purchase has been deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no such purchase!'
            ], 200);
        }
    }
}
