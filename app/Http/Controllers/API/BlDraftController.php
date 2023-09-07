<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bl\BlDraft;

class BlDraftController extends Controller
{
    //
    public function containers(BlDraft $bldraft)
    {
        $containers = $bldraft->blDetails->map(function($detail) {
            return optional($detail->container)->code;
         });

        return response()->json([
             'containers' => $containers,
             'bl_no' => $bldraft->ref_no
             ]);
    }
}
