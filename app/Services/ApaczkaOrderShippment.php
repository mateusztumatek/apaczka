<?php
namespace App\Services;
class ApaczkaOrderShippment {

    var $dimension1 = '';
    var $dimension2 = '';
    var $dimension3 = '';
    var $weight = '';
    private $shipmentTypeCode = '';
    private $shipmentValue = '';
    private $options = '';
    private $position = 0;
    private static $dictShipmentOptions = array('UBEZP', 'PRZES_NIETYP', 'DUZA_PACZKA');
    private static $dictShipmentTypeCode = array('LIST', 'PACZ', 'PALETA');

    public function __construct($shipmentTypeCode = '', $dim1 = '', $dim2 = '', $dim3 = '', $weight = '') {
        if ($shipmentTypeCode == 'LIST') {
            $this->createShipment($shipmentTypeCode, 0, 0, 0, 0);
        } else {
            if ($dim1 != '' && $dim2 != '' && $dim3 != '' && $weight != '' && $shipmentTypeCode != '') {
                $this->createShipment($shipmentTypeCode, $dim1, $dim2, $dim3, $weight);
            }
        }
    }
    function setPaczkomatOptions($sender_paczkomat = null, $recivier_paczkomat){
        $this->options = array();
        if($sender_paczkomat){
            $this->options[0] = $sender_paczkomat;
            $this->options[1] = $recivier_paczkomat;
        }else{
            $this->options[0] = $recivier_paczkomat;
        }
    }
    function getShipmentTypeCode() {
        return $this->shipmentTypeCode;
    }

    function setShipmentTypeCode($shipmentTypeCode) {
        if (!in_array($shipmentTypeCode, self::$dictShipmentTypeCode)) {
            throw new \Exception('UNSUPPORTED shipment type code: [' . $shipmentTypeCode . '] must be one of: ' . print_r(self::$dictShipmentTypeCode, 1));
        }

        $this->shipmentTypeCode = $shipmentTypeCode;
    }

    function getOptions() {
        return $this->options;
    }

    function addOrderOption($option) {
        if (!in_array($option, self::$dictShipmentOptions)) {
            throw new \Exception('UNSUPPORTED order option: [' . $option . '] must be one of: ' . print_r(self::$dictShipmentOptions, 1));
        }

        if ($this->options == "") {
            $this->options = array('string' => $option);
        } else if (!is_array($this->options['string'])) {
            $tmp_option = $this->options['string'];

            if ($tmp_option != $option) {
                $this->options['string'] = array($tmp_option, $option);
            }
        } else {
            $this->options['string'][] = $option;
        }
    }

    function getShipmentValue() {
        return $this->shipmentValue;
    }

    function setShipmentValue($value) {
        if (!$value > 0) {
            throw new Exception('UNSUPPORTED ShipmentValue: [' . $value . '] ShipmentValue must be greater then 0');
        }

        $this->shipmentValue = $value;
        $this->addOrderOption('UBEZP');
    }

    function createShipment($shipmentTypeCode, $dim1 = '', $dim2 = '', $dim3 = '', $weight = '') {

        $this->setShipmentTypeCode($shipmentTypeCode);

        $this->dimension1 = $dim1;
        $this->dimension2 = $dim2;
        $this->dimension3 = $dim3;

        $this->weight = $weight;

    }

}
