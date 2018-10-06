<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedsoap extends SoapClient{
    public $XMLStr = "";
    public function __construct()
    {
        parent::__construct("https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl",array('trace' => true));
    }
    public function setXMLStr($value)
    {
        $this->XMLStr = $value;
    }
    public function getXMLStr()
    {
        return $this->XMLStr;
    }
    public function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
        $request = $this->XMLStr;
        $dom = new DOMDocument('1.0');
        try
        {
            $dom->loadXML($request);
        } catch (DOMException $e) {
            die($e->code);
        }
        $request = $dom->saveXML();
        //Solicitud
        return parent::__doRequest($request, $location, $action, $version, $one_way = 0);
    }
    public function SoapClientCall($SOAPXML)
    {
    	//var_dump($SOAPXML);
        return $this->setXMLStr($SOAPXML);
    }
    public function msg(){
    	echo "hola!!";
    }
}