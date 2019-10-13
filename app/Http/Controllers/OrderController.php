<?php

namespace App\Http\Controllers;
use App\Order;
use App\Setting;
use Illuminate\Http\Request;
use App\Services\ApaczkaApi;
use App\Services\ApaczkaOrder;
use App\Services\ApaczkaOrderShippment;

class OrderController extends Controller
{
    public function index(Request $request){
        $apaczka = new ApaczkaApi(config('app.apaczka_username'), config('app.apaczka_password'), config('app.apaczka_api'));
        $apaczka->setVerboseMode();
/*        $apaczka->setTestMode();*/
        $apaczka->setProductionMode();

        /***************************
         *	validateAuthData
         */

        if(!$resp = $apaczka->validateAuthData()){
            var_dump($resp);
            die('validateAuthData ERROR'."\n");
        }
        $last_id = Setting::where('key', 'last_id')->first();
        if($last_id){
            $orders = Order::where('id', '>', $last_id)->get();
        }else{
            $orders = Order::where('id', '>', '1000')->get();
        }
        foreach ($orders as $o){
            $order = new ApaczkaOrder();

            $order->notificationDelivered = $order->createNotification(false, false, true, false);
            $order->notificationException = $order->createNotification(false, false, true, false);
            $order->notificationNew = $order->createNotification(false, false, true, false);
            $order->notificationSent = $order->createNotification(false, false, true, false);

            $order->setServiceCode('DHLSTD');
            $order->referenceNumber = "MojeID=997";
//$order->isDomestic = true;
            $order->contents = "Wazne dokumenty";

//set...Address($name = 'stefan', $contactName = '.', $addressLine1 = '', $addressLine2 = '', $city = '', $countryId = '', $postalCode = '', $stateCode = '', $email = '', $phone = '');

            $order->setReceiverAddress('stefan', 'stefan', 'Rostafińskich 4', '.', 'Warszawa', '0', '00-123', '', 'biuro@alepaczka.pl', '224131313');
            $order->setSenderAddress('Baśka ', 'Baśka', 'al. KEN 4', '.', 'Warszawa', '0', '01-123', '', 'basia@buziaczek.pl', '224131313');

//$order->setPobranie('11444400000000300000418888','100');

            $order_shipment = new ApaczkaOrderShippment('PACZ', 30, 30, 30, 4);
// wartosc przesylki do ubezpieczenia
// $order_shipment->setShipmentValue('123');

            $order->addShipment($order_shipment);

// Zamowienie kuriera
// $order->setPickup('COURIER', '08:00', '16:00', '2014-02-05');

            $resp = $apaczka->placeOrder($order);

            if($resp !== false && $resp->return->order){
                $orderId = $resp->return->order->id;
            }else{
//	print_r($order);
                var_dump($resp);
                die('Błąd podczas wysylania zamowienia'."\n");
            }
        }

    }
}
