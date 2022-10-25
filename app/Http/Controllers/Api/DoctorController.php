<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::all();
        if (count($doctors) > 0) {
            return response()->json([
                'success' => true,
                'doctors' => DoctorResource::collection($doctors)
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no doctors yet!'
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'number' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 200);
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filepath = 'storage/images/doctors/' . date('Y') . '/' . date('m') . '/';
            $filename = $filepath . time() . '-' . $file->getClientOriginalName();
            $file->move($filepath, $filename);
            $data['image'] = $filename;
        }
        $doctor = Doctor::create($data);
        return response()->json([
            'success' => true,
            'doctor' => new DoctorResource($doctor)
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::find($id);

        if ($doctor) {
            # code...

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'number' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 200);
            }
            $data = $request->all();
            if ($request->hasfile('image')) {
                $file = $request->file('image');
                $filepath = 'storage/images/doctors/' . date('Y') . '/' . date('m') . '/';
                $filename = $filepath . time() . '-' . $file->getClientOriginalName();
                $file->move($filepath, $filename);
                if(request('old-image')){
                    $oldpath=request('old-image');
                    if(File::exists($oldpath)){
                        unlink($oldpath);
                    }
                }
                $data['image'] = $filename;
            }
            $doctor->update($data);
            return response()->json([
                'success' => true,
                'doctor' => new DoctorResource($doctor)
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'there is no such doctor!'
            ], 200);
        }
    }

    public function destroy($id)
    {
        //
    }
}
