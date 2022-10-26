<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ColorResource;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::all();
        if (count($colors) > 0) {
            return response()->json([
                'success' => true,
                'colors' => ColorResource::collection($colors)
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no colors yet!'
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 200);
        }
        $color = Color::create($data);
        return response()->json([
            'success' => true,
            'color' => new ColorResource($color)
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $color = Color::find($id);

        if ($color) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 200);
            }
            $data = $request->all();
            $color->update($data);
            return response()->json([
                'success' => true,
                'color' => new ColorResource($color)
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no such color!'
            ], 200);
        }
    }

    public function destroy($id)
    {
        $color = Color::find($id);
        if ($color) {
            $color->delete();
            return response()->json([
                'success' => true,
                'message' => 'color has been deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no such color!'
            ], 200);
        }
    }
}
