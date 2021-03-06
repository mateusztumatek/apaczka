<?php

namespace App\Http\Controllers;
use App\Error;
use App\Order;
use App\Setting;
use App\User;
use Carbon\Carbon;
use Doctrine\DBAL\Exception\DatabaseObjectExistsException;
use Illuminate\Http\Request;
use App\Services\ApaczkaApi;
use App\Services\ApaczkaOrder;
use App\Services\ApaczkaOrderShippment;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request){
        $apaczka = new ApaczkaApi(config('app.apaczka_username'), config('app.apaczka_password'), config('app.apaczka_api'));
        $apaczka->setVerboseMode();
/*        $apaczka->setTestMode();*/
        $apaczka->setProductionMode();
        if($request->method() == 'POST'){
            $request->validate([
                'start' => ['required','integer',function($field, $data, $fail){
                    $last_order = Order::orderBy('id', 'desc')->take(1)->first();
                    if($last_order){
                        if($last_order->id < $data) $fail('Nie ma zamowien wiekszych niz podana wartosc');
                    }
                }]
            ]);
        }else{
            $request->validate([
                'order_id' => 'required'
            ]);
        }
        /***************************
         *	validateAuthData
         */

        if(!$resp = $apaczka->validateAuthData()){
            die('validateAuthData ERROR'."\n");
        }
        /*$last_id = Setting::where('key', 'last_id')->first();
        if($last_id){
            $orders = Order::with('shipping_relation', 'address')->where('status', 2)->where('id', '>', $last_id->value)->get();
        }else{
            $orders = Order::with('shipping_relation', 'address')->where('status', 2)->where('id', '>', '11229')->get();
        }*/
        if($request->start){
            $orders = Order::with('shipping_relation', 'address', 'orderInfo')->where('status', 2)->where('id', '>', $request->start)->get();
            foreach ($orders as $key => $o){
                if($o->order_info && $o->order_info->is_send){
                    $orders->forget($key);
                }
            }
        }
        if($request->order_id){
            $orders = Order::where('id', $request->order_id)->get();
        }
        $sender = Auth::user();
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
                    $temp = substr($o->notes, strpos($o->notes, '...INPOST:'), strlen($o->notes));
                    $temp = explode(' ', $temp);
                    if(array_key_exists(1, $temp)){
                        /*$order_shipment->setPaczkomatOptions($temp[1]);*/
                        if($o->shipping_relation->shippment_maps->pickup == 'COURIER'){
                            $order_shipment->setPaczkomatOptions(null, $temp[1]);
                        }else{
                            $order_shipment->setPaczkomatOptions($sender->inpost, $temp[1]);
                        }
                    }
                }



// wartosc przesylki do ubezpieczenia
// $order_shipment->setShipmentValue('123');
                $order->addShipment($order_shipment);
// Zamowienie kuriera
                $resp = $apaczka->placeOrder($order);

                if($resp !== false && $resp->return->order){

                    $orderId = $resp->return->order->id;
                    if(!$o->orderInfo){
                        $o->orderInfo()->create([
                            'order_id' => $o->id,
                            'is_send' => true
                        ]);
                    }
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
    public function ordersList(Request $request){
        $orders = Order::with('address', 'shipping_relation', 'orderInfo')->orderBy('creation_date', 'desc')->where('status', 2)->filter()->paginate(50);
        return response()->json($orders);
    }
}
