<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\CplRequest;
use App\Http\Resources\v1\CplCollection;
use App\Http\Resources\v1\CplResource;
use App\Models\Cpl;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CplController extends Controller
{
    
    public function get(Request $request): JsonResponse {
        $cpl = Cpl::get();

        return response()->json([
            'cpl' => CplResource::collection($cpl)
        ], 201); 

    }

    public function add(CplRequest $request): JsonResponse{

        $data = $request->validated();
        $rights = json_decode(Auth::user()->rights);
        

        if(!in_array("buatCPL", $rights)){
            return response()->json([
                "status" => "Gagal"
            ], 403);
        }


        $cpl = new Cpl($data);
        $cpl->kode_cpl = $data['kodecpl'];
        $cpl->save();

        return response()->json([
            "status" => "OK"
        ], 201);

    }

    public function removeCpl(Request $request): JsonResponse{
        $request->validate([
            "cpl" => "required"
        ]);
        

        $rights = json_decode(Auth::user()->rights);

        if(!in_array("hapusCPL", $rights)){
            return response()->json([
                "status" => "Gagal"
            ], 403);
        }

        Cpl::query()->where('kode_cpl', $request->cpl['kodecpl'])->delete();
        // dd($cpl);

        return response()->json([
            "status" => "OK"
        ], 201);
    }

    public function updateCpl(Request $request): JsonResponse {

        $validatedData = $request->validate([
            'cpl.kodecpl' => "required",
            'cpl.deskripsi' => "required|string"
        ]);

        $kodecpl = $validatedData['cpl']['kodecpl'];
        $deskripsi = $validatedData['cpl']['deskripsi'];

        $cpl = Cpl::query()->where('kode_cpl', $kodecpl)->first();
        if(!$cpl){
            return response()->json([
                "status" => "Gagal"
            ], 404);
        }

        $cpl->deskripsi = $deskripsi;
        $cpl->save();
        
        return response()->json([
            "status" => "OK"
        ], 201);

    }

}
