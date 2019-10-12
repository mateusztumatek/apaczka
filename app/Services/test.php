<?php

include('ApaczkaApi.php');

date_default_timezone_set('Europe/Warsaw');

$apaczka = new apaczkaApi('username', 'password','api_key');
$apaczka->setVerboseMode();
//$apaczka->setTestMode();
$apaczka->setProductionMode();

/***************************
*	validateAuthData
*/

if(!$resp = $apaczka->validateAuthData()){
	var_dump($resp);
	die('validateAuthData ERROR'."\n");
}

/***************************
*	getCountries
*/

//$resp = $apaczka->getCountries();
//print_r($resp);

/***************************
*	placeOrder(ApaczkaOrder $order)
*/

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

$order_shipment = new ApaczkaOrderShipment('PACZ', 30, 30, 30, 4);
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

/***************************
 *	getWaybillDocument(orderId)
 */

if($resp = $apaczka->getWaybillDocument($orderId)){
	file_put_contents('waybill.pdf',$resp->return->waybillDocument);
}else{
	die('Błąd podczas pobierania etykiety'."\n");
}

/***************************
*	getCollectiveWaybillDocument(array(orderId,orderId,orderId))
*/

// $resp = $apaczka->getCollectiveWaybillDocument(array('329445376',329445375));



/***************************
*	getCollectiveWaybillDocument(array(orderId,orderId,orderId))
*/

$resp = $apaczka->getCollectiveTurnInCopyDocument($orderId);
file_put_contents('turnin.pdf',$resp->return->turnInCopyDocument);



