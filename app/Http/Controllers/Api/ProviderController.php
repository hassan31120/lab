<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
    public function index()
    {
        Carbon::setLocale('ar');
        $providers = Provider::all();
        if (count($providers) > 0) {
            return response()->json([
                'success' => true,
                'providers' => ProviderResource::collection($providers)
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no providers yet!'
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required',
            'number' => 'required|numeric'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 200);
        }
        $provider = Provider::create($data);
        return response()->json([
            'success' => true,
            'provider' => new ProviderResource($provider)
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $provider = Provider::find($id);
        $data = $request->all();
        if ($provider) {
            $validator = Validator::make($data, [
                'name' => 'required',
                'number' => 'required|numeric'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 200);
            }
            $provider->update($data);
            return response()->json([
                'success' => true,
                'provider' => new ProviderResource($provider)
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no such provider!'
            ], 200);
        }
    }

    public function destroy($id)
    {
        $provider = Provider::find($id);
        if ($provider) {
            $provider->delete();
            return response()->json([
                'success' => true,
                'message' => 'provider has been deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no such provider!'
            ], 200);
        }
    }
}
