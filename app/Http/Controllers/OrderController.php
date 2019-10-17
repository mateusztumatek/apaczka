<?php

namespace App\Http\Controllers;
use App\Error;
use App\Order;
use App\Setting;
use App\User;
use Carbon\Carbon;
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
            die('validateAuthData ERROR'."\n");
        }
        $last_id = Setting::where('key', 'last_id')->first();
        if($last_id){
            $orders = Order::with('shipping_relation', 'address')->where('id', '>', $last_id->value)->get();
        }else{
            $orders = Order::with('shipping_relation', 'address')->where('id', '>', '11229')->get();
        }
        if($request->order_id){
            $orders = Order::where('id', $request->order_id)->get();
        }
        $sender = User::first();
        if(!$sender) return back()->withErrors('Brak ustawionych danych użytkownika');
        $fails = [];
        foreach ($orders as $o){
            if($o->shipping_relation->shippment_maps && $o->shipping_relation->shippment_maps->apaczka_codename){
                $order = new ApaczkaOrder();

                $order->notificationDelivered = $order->createNotification(false, false, true, false);
                $order->notificationException = $order->createNotification(false, false, true, false);
                $order->notificationNew = $order->createNotification(false, false, true, false);
                $order->notificationSent = $order->createNotification(false, false, true, false);

                $order->setServiceCode($o->shipping_relation->shippment_maps->apaczka_codename);
                $order->referenceNumber = "ID=".$o->id;
                if($o->shipping_relation->shippment_maps->is_domestic){
                    $order->isDomestic = true;
                }

                $order->contents = "Zamówienie nr. #".$o->id;
                $order->setPickup($o->shipping_relation->shippment_maps->pickup, $sender->opened_from, $sender->opened_to, Carbon::now()->format('Y-m-d'));

                $order->setReceiverAddress($o->address->name, $o->address->name, $o->address->street, '.', $o->address->city, '0', $o->address->postal, '', $o->address->email, $o->address->phone);
                $order->setSenderAddress($sender->name, $sender->name, $sender->street, '.', $sender->city, '0', $sender->postal, '', $sender->email, $sender->phone);

//$order->setPobranie('11444400000000300000418888','100');

                $order_shipment = new ApaczkaOrderShippment('PACZ', 40, 30, 5, 5);
                if($o->shipping_relation->shippment_maps->is_paczkomat){
                    $temp = explode(' ', $o->notes);
                    if(array_key_exists(1, $temp)){
                        /*$order_shipment->setPaczkomatOptions($temp[1]);*/
                        $order_shipment->setPaczkomatOptions($sender->inpost, $temp[1]);
                    }
/*                    dd($order_shipment);*/
                }
// wartosc przesylki do ubezpieczenia
// $order_shipment->setShipmentValue('123');

                $order->addShipment($order_shipment);
/*                dd($order);*/
// Zamowienie kuriera
                $resp = $apaczka->placeOrder($order);

                if($resp !== false && $resp->return->order){
                    $orderId = $resp->return->order->id;
                }else{
//	print_r($order);
                    array_push($fails, $o);
                    if($error = Error::where('order_id', $o->id)->first()){
                        $error->update([
                            'code' => $resp->return->result->messages->Message->code,
                            'description' => $resp->return->result->messages->Message->description
                        ]);
                    }else{
                        Error::create([
                            'order_id' => $o->id,
                            'code' => $resp->return->result->messages->Message->code,
                            'description' => $resp->return->result->messages->Message->description
                        ]);
                    }
                }
                if($last_id = Setting::where('key', 'last_id')->first()) $last_id->update(['value' => $o->id]);
                else Setting::create(['key' => 'last_id', 'value' => $o->id]);
            }
        }
        return back()->withErrors('Wysłano zamówienia, nieudanych: '.count($fails).' Więcej informacji w zakładce: błędy');
    }
}
