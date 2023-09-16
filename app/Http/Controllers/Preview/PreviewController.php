<?php

namespace App\Http\Controllers\Preview;

use App\Http\Controllers\Controller;
use App\Models\Containers\Demurrage;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class PreviewController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $cartDataParam = request()->query('cartData');
        $cartData = json_decode(urldecode($cartDataParam), true);
        $total = 0;
        foreach ($cartData as $key => $data) {
            $total += $data['calculationData']['grandTotal'];
            $triff = Demurrage::with('tarriffType')->find($data['triffValue']);
            $data['triffValue'] = $triff->tarriffType->id;
            if (in_array($data['triffValue'], ["1", "3"])) {
                $cartData[$key]['triffValue'] = "Detention BreakDown";
            } elseif (in_array($data['triffValue'], ["7", "8"])) {
                $cartData[$key]['triffValue'] = "PowerCharges BreakDown";
            } else {
                $cartData[$key]['triffValue'] = "Storage BreakDown";
            }
        }
        return view('preview.index', compact('cartData','total'));
    }
}
