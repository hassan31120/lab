<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TypeResource;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::all();
        if (count($types) > 0) {
            return response()->json([
                'success' => true,
                'types' => TypeResource::collection($types)
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no types yet!'
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 200);
        }
        $type = Type::create($data);
        return response()->json([
            'success' => true,
            'type' => new TypeResource($type)
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $type = Type::find($id);

        if ($type) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 200);
            }
            $data = $request->all();
            $type->update($data);
            return response()->json([
                'success' => true,
                'type' => new TypeResource($type)
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no such type!'
            ], 200);
        }
    }

    public function destroy($id)
    {
        $type = Type::find($id);
        if ($type) {
            $type->delete();
            return response()->json([
                'success' => true,
                'message' => 'type has been deleted successfully'
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no such type!'
            ], 200);
        }
    }
}
