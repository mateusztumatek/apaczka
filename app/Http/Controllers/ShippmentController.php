<?php

namespace App\Http\Controllers;

use App\Shippment;
use App\ShippmentMap;
use Illuminate\Http\Request;

class ShippmentController extends Controller
{
    protected $apazcka_shippments = [
        'UPS_K_STANDARD' => 'Ups Standard Kraj',
        'UPS_K_EX_SAV' => 'Ups Express Saver Kraj',
        'UPS_K_EX' => 'Ups Express Kraj',
        'UPS_K_EXP_PLUS' => 'Ups Express Plus Kraj',
        'UPS_Z_STANDARD' => 'Ups Standard Zagranica',
        'UPS_Z_EX_SAV' => 'Ups Express Saver Zagranica',
        'UPS_Z_EX' => 'Ups Express Zagranica',
        'UPS_Z_EXPEDITED' => 'Ups Expedited Zagranica',
        'DPD_CLASSIC' => 'Dpd Kraj',
        'DPD_CLASSIC_FOREIGN' => 'Dpd Zagranica',
        'DHLSTD' => 'DHL Standard',
        'DHL12' => 'DHL Express do godz. 12',
        'DHL09' => 'DHL Express do godz. 9',
        'DHL1722' => 'DHL Express w godz. 17-22',
        'KEX_EXPRESS ' => 'K-EX Express',
        'FEDEX' => 'FEDEX',
        'POCZTA_POLSKA_E24' => 'Pocztex24',
        'TNT' => 'TNT Kraj',
        'TNT_Z' => ' TNT Zagranica',
        'INPOST' => 'Inpost Kurier',
        'APACZKA_DE' => 'Apaczka Niemcy',
        'PACZKOMAT' => 'Paczkomaty',
        'GLS' => 'GLS zagranica'
    ],
    $pickup_types = [
        'COURIER' => 'zamówienie odbioru przesyłek',
        'SELF' => 'dostarczenie samodzielnie do kuriera',
        'BOX_MACHINE' => 'dostarczenie samodzielnie do paczkomatu'
    ];
    public function index(){
        $shippments = Shippment::with('shippment_maps')->get();
        $apaczka_shippments = $this->apazcka_shippments;
        $pickup_types = $this->pickup_types;
        return view('shippment', compact('shippments', 'apaczka_shippments', 'pickup_types'));
    }
    public function save(Request $request){
        foreach ($request->shippment as $key => $s){
            if($s['code']){
                ShippmentMap::where('order_type', $key)->delete();
                ShippmentMap::create([
                    'apaczka_codename' => $s['code'],
                    'is_domestic' => (array_key_exists('isDomestic', $s) && $s['isDomestic'] == 'on')? true : false,
                    'is_paczkomat' => (array_key_exists('is_paczkomat', $s) && $s['is_paczkomat'] == 'on')? true : false,
                    'pickup' => $s['pickup_type'],
                    'order_type' => $key
                ]);
            }else{
                ShippmentMap::where('order_type', $key)->delete();
            }
        }
        return back()->with(['message' => 'Zapisano poprawnie']);
    }
}
