<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SertifikatResource;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SertifikatController extends Controller
{
    /**
     * GET /api/sertifikat?nis={NIS}
     *
     * Returns all certificates belonging to the given NIS.
     * Responds with an empty data array when the NIS has no records.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $request->validate([
            'nis' => ['required', 'string'],
        ]);

        $sertifikats = Sertifikat::where('nis', $request->input('nis'))->get();

        return SertifikatResource::collection($sertifikats);
    }
}
