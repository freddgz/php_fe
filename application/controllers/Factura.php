<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Factura extends CI_Controller {

public function index(){
	echo  "hola mundo";
}
public function sunat(){
	$method = $_SERVER['REQUEST_METHOD'];

		if($method!='POST'){
			json_output(400,array('status'=>400, 'message'=>'Bad request.'));
		}else {
            $cab = json_decode(file_get_contents('php://input'), true);

            $emp_tipo_documento = $cab['emp_tipo_documento'];
            $emp_ruc = $cab['emp_ruc'];
            $emp_razonsocial = $cab['emp_razonsocial'];
            $emp_nombrecomercial = $cab['emp_nombrecomercial'];
            $emp_direccion = $cab['emp_direccion'];
            $emp_distrito = $cab['emp_distrito'];
            $emp_provincia = $cab['emp_provincia'];
            $emp_departamento = $cab['emp_departamento'];
            $emp_ubigeo = $cab['emp_ubigeo'];
            $emp_pais = $cab['emp_pais'];
            $doc_enviaws = $cab['doc_enviaws'];

            $cli_tipo_documento = $cab['cli_tipo_documento'];
            $cli_numero = $cab['cli_numero'];
            $cli_nombre = $cab['cli_nombre'];

            $doc_tipo_documento = $cab['doc_tipo_documento'];
            $doc_numero = $cab['doc_numero'];
            $doc_fecha = $cab['doc_fecha'];
            $doc_gravada = $cab['doc_gravada'];
            $doc_igv = $cab['doc_igv'];
            $doc_descuento = $cab['doc_descuento'];
            $doc_exonerada = $cab['doc_exonerada'];
            $doc_gratuita = $cab['doc_gratuita'];
            $doc_inafecta = $cab['doc_inafecta'];
            $doc_isc = $cab['doc_isc'];
            $doc_moneda = $cab['doc_moneda'];
            $doc_otros_cargos = $cab['doc_otros_cargos'];
            $doc_otros_tributos = $cab['doc_otros_tributos'];
            $doc_total = $cab['doc_total'];


            $file = $emp_ruc . '-' . $doc_tipo_documento . '-' . $doc_numero . '.xml';

            $dom = new DomDocument("1.0", "ISO-8859-1");
            $dom->xmlStandalone = false;
            //$dom->formatOutput = true;
            $dom->preserveWhiteSpace = false;

            $Invoice = $dom->createElement('Invoice');
            $dom->appendChild($Invoice);

            $Invoice->setAttribute('xmlns', 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2');
            $Invoice->setAttribute('xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
            $Invoice->setAttribute('xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
            $Invoice->setAttribute('xmlns:ccts', 'urn:un:unece:uncefact:documentation:2');
            $Invoice->setAttribute('xmlns:ds', 'http://www.w3.org/2000/09/xmldsig#');
            $Invoice->setAttribute('xmlns:ext', 'urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2');
            $Invoice->setAttribute('xmlns:qdt', 'urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2');
            $Invoice->setAttribute('xmlns:sac', 'urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1');
            $Invoice->setAttribute('xmlns:udt', 'urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2');
            $Invoice->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');

            $UBLExtensions = $dom->createElement('ext:UBLExtensions');
            $Invoice->appendChild($UBLExtensions);

            $UBLExtension1 = $dom->createElement('ext:UBLExtension');
            $UBLExtensions->appendChild($UBLExtension1);
            $ExtensionContent1 = $dom->createElement('ext:ExtensionContent');
            $UBLExtension1->appendChild($ExtensionContent1);

            $AdditionalInformation = $dom->createElement('sac:AdditionalInformation');
            $ExtensionContent1->appendChild($AdditionalInformation);

            if ($doc_gravada != '0.00') {
                //agrupa1 getDocu_gravada
                $AdditionalMonetaryTotal1 = $dom->createElement('sac:AdditionalMonetaryTotal');
                $AdditionalInformation->appendChild($AdditionalMonetaryTotal1);
                $AdditionalMonetaryTotal1->appendChild($dom->createElement('cbc:ID', "1001"));
                $PayableAmount1 = $dom->createElement('cbc:PayableAmount', "100.00");
                $PayableAmount1->setAttribute('currencyID', $doc_moneda);
                $AdditionalMonetaryTotal1->appendChild($PayableAmount1);
                $anticipoCero1001 = "1";
            }
            if ($doc_inafecta != '0.00') {
                //agrupa2 getDocu_inafecta
                $AdditionalMonetaryTotal2 = $dom->createElement('sac:AdditionalMonetaryTotal');
                $AdditionalInformation->appendChild($AdditionalMonetaryTotal2);
                $AdditionalMonetaryTotal2->appendChild($dom->createElement('cbc:ID', "1002"));
                $PayableAmount2 = $dom->createElement('cbc:PayableAmount', $doc_inafecta);
                $PayableAmount2->setAttribute('currencyID', $doc_moneda);
                $AdditionalMonetaryTotal2->appendChild($PayableAmount2);
                $anticipoCero1002 = "1";
            }
            if ($doc_exonerada) {
                //agrupa3 getDocu_exonerada
                $AdditionalMonetaryTotal3 = $dom->createElement('sac:AdditionalMonetaryTotal');
                $AdditionalInformation->appendChild($AdditionalMonetaryTotal3);
                $AdditionalMonetaryTotal3->appendChild($dom->createElement('cbc:ID', "1003"));
                $PayableAmount3 = $dom->createElement('cbc:PayableAmount', $doc_exonerada);
                $PayableAmount3->setAttribute('currencyID', $doc_moneda);
                $AdditionalMonetaryTotal3->appendChild($PayableAmount3);
                $anticipoCero1003 = "1";
            }
            if ($doc_gratuita) {
                //agrupa4 getDocu_gratuita
                $AdditionalMonetaryTotal4 = $dom->createElement('sac:AdditionalMonetaryTotal');
                $AdditionalInformation->appendChild($AdditionalMonetaryTotal4);
                $AdditionalMonetaryTotal4->appendChild($dom->createElement('cbc:ID', "1004"));
                $PayableAmount4 = $dom->createElement('cbc:PayableAmount', $doc_gratuita);
                $PayableAmount4->setAttribute('currencyID', $doc_moneda);
                $AdditionalMonetaryTotal4->appendChild($PayableAmount4);
            }
            if ($doc_descuento) {
                //agrupa5 getDocu_descuento
                $AdditionalMonetaryTotal5 = $dom->createElement('sac:AdditionalMonetaryTotal');
                $AdditionalInformation->appendChild($AdditionalMonetaryTotal5);
                $AdditionalMonetaryTotal5->appendChild($dom->createElement('cbc:ID', "2005"));
                $PayableAmount5 = $dom->createElement('cbc:PayableAmount', $doc_descuento);
                $PayableAmount5->setAttribute('currencyID', $doc_moneda);
                $AdditionalMonetaryTotal5->appendChild($PayableAmount5);
            }

            foreach ($cab['leyenda'] as $item) {
                $AdditionalProperty = $dom->createElement('sac:AdditionalProperty');
                $AdditionalInformation->appendChild($AdditionalProperty);
                $AdditionalProperty->appendChild($dom->createElement('cbc:ID', $item['codigo']));
                $Value = $dom->createElement('cbc:Value', $item['descripcion']);
                $AdditionalProperty->appendChild($Value);
                //$Value->appendChild($dom->createCDATASection("CIEN Y 00/100"));
            }

            //signature
            $UBLExtension2 = $dom->createElement('ext:UBLExtension');
            $UBLExtensions->appendChild($UBLExtension2);
            $ExtensionContent2 = $dom->createElement('ext:ExtensionContent', ' ');
            $UBLExtension2->appendChild($ExtensionContent2);


            //bloque 1
            $Invoice->appendChild($dom->createElement('cbc:UBLVersionID', '2.0'));
            $Invoice->appendChild($dom->createElement('cbc:CustomizationID', '1.0'));
            $Invoice->appendChild($dom->createElement('cbc:ID', $doc_numero));
            $Invoice->appendChild($dom->createElement('cbc:IssueDate', $doc_fecha));
            $Invoice->appendChild($dom->createElement('cbc:InvoiceTypeCode', $doc_tipo_documento));
            $Invoice->appendChild($dom->createElement('cbc:DocumentCurrencyCode', $doc_moneda));

            //bloque2 cac:Signature
            $Signature = $dom->createElement('cac:Signature');
            $Invoice->appendChild($Signature);
            $Signature->appendChild($dom->createElement('cbc:ID', $emp_ruc));
            $SignatoryParty = $dom->createElement('cac:SignatoryParty');
            $Signature->appendChild($SignatoryParty);
            $PartyIdentification = $dom->createElement('cac:PartyIdentification');
            $SignatoryParty->appendChild($PartyIdentification);
            $PartyIdentification->appendChild($dom->createElement('cbc:ID', $emp_ruc));
            $PartyName = $dom->createElement('cac:PartyName');
            $SignatoryParty->appendChild($PartyName);
            $Name = $dom->createElement('cbc:Name', $emp_razonsocial);
            $PartyName->appendChild($Name);
            //$Name->appendChild($dom->createCDATASection("NOMBRE"));


            $DigitalSignatureAttachment = $dom->createElement('cac:DigitalSignatureAttachment');
            $Signature->appendChild($DigitalSignatureAttachment);
            $ExternalReference = $dom->createElement('cac:ExternalReference');
            $DigitalSignatureAttachment->appendChild($ExternalReference);
            $ExternalReference->appendChild($dom->createElement('cbc:URI', $emp_ruc));

            //bloque3 cac:AccountingSupplierParty
            $AccountingSupplierParty = $dom->createElement('cac:AccountingSupplierParty');
            $Invoice->appendChild($AccountingSupplierParty);
            $AccountingSupplierParty->appendChild($dom->createElement('cbc:CustomerAssignedAccountID', $emp_ruc));
            $AccountingSupplierParty->appendChild($dom->createElement('cbc:AdditionalAccountID', $emp_tipo_documento));
            $Party = $dom->createElement('cac:Party');
            $AccountingSupplierParty->appendChild($Party);
            $PartyName = $dom->createElement('cac:PartyName');
            $Party->appendChild($PartyName);
            $Name = $dom->createElement('cbc:Name', $emp_razonsocial);
            $PartyName->appendChild($Name);
            //$Name->appendChild($dom->createCDATASection("NOMBRE"));


            $PostalAddress = $dom->createElement('cac:PostalAddress');
            $Party->appendChild($PostalAddress);
            $PostalAddress->appendChild($dom->createElement('cbc:ID', $emp_ubigeo));
            $PostalAddress->appendChild($dom->createElement('cbc:StreetName', $emp_direccion));
            $PostalAddress->appendChild($dom->createElement('cbc:CitySubdivisionName', ''));
            $PostalAddress->appendChild($dom->createElement('cbc:CityName', $emp_provincia));
            $PostalAddress->appendChild($dom->createElement('cbc:CountrySubentity', $emp_departamento));
            $PostalAddress->appendChild($dom->createElement('cbc:District', $emp_distrito));
            $Country = $dom->createElement('cac:Country');
            $PostalAddress->appendChild($Country);
            $Country->appendChild($dom->createElement('cbc:IdentificationCode', $emp_pais));
            $PartyLegalEntity = $dom->createElement('cac:PartyLegalEntity');
            $Party->appendChild($PartyLegalEntity);
            $RegistrationName = $dom->createElement('cbc:RegistrationName', $emp_razonsocial);
            $PartyLegalEntity->appendChild($RegistrationName);
            //$RegistrationName->appendChild($dom->createCDATASection("NOMBRE"));
            //bloque 4
            $AccountingCustomerParty = $dom->createElement('cac:AccountingCustomerParty');
            $Invoice->appendChild($AccountingCustomerParty);
            $AccountingCustomerParty->appendChild($dom->createElement('cbc:CustomerAssignedAccountID', $cli_numero));
            $AccountingCustomerParty->appendChild($dom->createElement('cbc:AdditionalAccountID', $cli_tipo_documento));
            $Party2 = $dom->createElement('cac:Party');
            $AccountingCustomerParty->appendChild($Party2);
            $PartyLegalEntity2 = $dom->createElement('cac:PartyLegalEntity');
            $Party2->appendChild($PartyLegalEntity2);
            $PartyLegalEntity2->appendChild($dom->createElement('cbc:RegistrationName', $cli_nombre));

            //bloque 5 getDocu_igv
            $TaxTotal = $dom->createElement('cac:TaxTotal');
            $Invoice->appendChild($TaxTotal);
            $TaxAmount = $dom->createElement('cbc:TaxAmount', $doc_igv);
            $TaxTotal->appendChild($TaxAmount);
            $TaxAmount->setAttribute('currencyID', $doc_moneda);
            $TaxSubtotal = $dom->createElement('cac:TaxSubtotal');
            $TaxTotal->appendChild($TaxSubtotal);
            $TaxAmount2 = $dom->createElement('cbc:TaxAmount', $doc_igv);
            $TaxSubtotal->appendChild($TaxAmount2);
            $TaxAmount2->setAttribute('currencyID', $doc_moneda);
            $TaxCategory = $dom->createElement('cac:TaxCategory');
            $TaxSubtotal->appendChild($TaxCategory);
            $TaxScheme = $dom->createElement('cac:TaxScheme');
            $TaxCategory->appendChild($TaxScheme);
            $TaxScheme->appendChild($dom->createElement('cbc:ID', '1000'));
            $TaxScheme->appendChild($dom->createElement('cbc:Name', 'IGV'));
            $TaxScheme->appendChild($dom->createElement('cbc:TaxTypeCode', 'VAT'));

            //bloque 6
            $LegalMonetaryTotal = $dom->createElement('cac:LegalMonetaryTotal');
            $Invoice->appendChild($LegalMonetaryTotal);

            if ($doc_descuento != '0.00') {
                $AllowanceTotalAmount = $dom->createElement('cbc:AllowanceTotalAmount', $doc_descuento);
                $LegalMonetaryTotal->appendChild($AllowanceTotalAmount);
                $AllowanceTotalAmount->setAttribute('currencyID', $doc_moneda);
            }

            $PayableAmount = $dom->createElement('cbc:PayableAmount', $doc_total);
            $LegalMonetaryTotal->appendChild($PayableAmount);
            $PayableAmount->setAttribute('currencyID', $doc_moneda);

            //detalle factura
            foreach ($cab['detalle'] as $item) {

                $InvoiceLine = $dom->createElement('cac:InvoiceLine');
                $Invoice->appendChild($InvoiceLine);
                $InvoiceLine->appendChild($dom->createElement('cbc:ID', $item['orden']));
                $InvoicedQuantity = $dom->createElement('cbc:InvoicedQuantity', $item['cantidad']);
                $InvoiceLine->appendChild($InvoicedQuantity);
                $InvoicedQuantity->setAttribute('unitCode', $item['unidad']);

                $LineExtensionAmount = $dom->createElement('cbc:LineExtensionAmount', $item['subtotal']);
                $InvoiceLine->appendChild($LineExtensionAmount);
                $LineExtensionAmount->setAttribute('currencyID', $doc_moneda);

                $PricingReference = $dom->createElement('cac:PricingReference');
                $InvoiceLine->appendChild($PricingReference);
                $AlternativeConditionPrice = $dom->createElement('cac:AlternativeConditionPrice');
                $PricingReference->appendChild($AlternativeConditionPrice);
                $PriceAmount = $dom->createElement('cbc:PriceAmount', $item['precio']);
                $AlternativeConditionPrice->appendChild($PriceAmount);
                $PriceAmount->setAttribute('currencyID', $doc_moneda);
                $AlternativeConditionPrice->appendChild($dom->createElement('cbc:PriceTypeCode', '01'));

                if ($item['precio_no_onerosa'] != '0.00') {
                    $AlternativeConditionPrice2 = $dom->createElement('cac:AlternativeConditionPrice');
                    $PricingReference->appendChild($AlternativeConditionPrice2);
                    $PriceAmount2 = $dom->createElement('cbc:PriceAmount', $item['precio_no_onerosa']);
                    $AlternativeConditionPrice2->appendChild($PriceAmount2);
                    $PriceAmount2->setAttribute('currencyID', $doc_moneda);
                    $AlternativeConditionPrice2->appendChild($dom->createElement('cbc:PriceTypeCode', '02'));
                }

                $TaxTotal = $dom->createElement('cac:TaxTotal');
                $InvoiceLine->appendChild($TaxTotal);
                $TaxAmount = $dom->createElement('cbc:TaxAmount', $item['igv']);
                $TaxTotal->appendChild($TaxAmount);
                $TaxAmount->setAttribute('currencyID', $doc_moneda);
                $TaxSubtotal = $dom->createElement('cac:TaxSubtotal');
                $TaxTotal->appendChild($TaxSubtotal);
                $TaxableAmount = $dom->createElement('cbc:TaxableAmount', $item['igv']);
                $TaxSubtotal->appendChild($TaxableAmount);
                $TaxableAmount->setAttribute('currencyID', $doc_moneda);
                $TaxAmount2 = $dom->createElement('cbc:TaxAmount', $item['igv']);
                $TaxSubtotal->appendChild($TaxAmount2);
                $TaxAmount2->setAttribute('currencyID', $doc_moneda);
                $TaxSubtotal->appendChild($dom->createElement('cbc:Percent', '0.0'));

                $TaxCategory = $dom->createElement('cac:TaxCategory');
                $TaxSubtotal->appendChild($TaxCategory);
                $TaxCategory->appendChild($dom->createElement('cbc:ID', 'VAT'));
                $TaxCategory->appendChild($dom->createElement('cbc:TaxExemptionReasonCode', $item['afectacion']));
                $TaxCategory->appendChild($dom->createElement('cbc:TierRange', '10'));
                $TaxScheme = $dom->createElement('cac:TaxScheme');
                $TaxCategory->appendChild($TaxScheme);
                $TaxScheme->appendChild($dom->createElement('cbc:ID', '1000'));
                $TaxScheme->appendChild($dom->createElement('cbc:Name', 'IGV'));
                $TaxScheme->appendChild($dom->createElement('cbc:TaxTypeCode', 'VAT'));

                $Item = $dom->createElement('cac:Item');
                $InvoiceLine->appendChild($Item);
                $Item->appendChild($dom->createElement('cbc:Description', $item['descripcion']));
                $SellersItemIdentification = $dom->createElement('cac:SellersItemIdentification');
                $Item->appendChild($SellersItemIdentification);
                $SellersItemIdentification->appendChild($dom->createElement('cbc:ID', $item['codigo']));

                $Price = $dom->createElement('cac:Price');
                $InvoiceLine->appendChild($Price);
                $PriceAmount = $dom->createElement('cbc:PriceAmount', $item['precio']);
                $Price->appendChild($PriceAmount);
                $PriceAmount->setAttribute('currencyID', $doc_moneda);
            }


            $dom->formatOutput = true;

            $dom->save('xml/' . $file);

        }

//*************************************FIRMAR
			$filename =$emp_ruc.'-'.$doc_tipo_documento.'-'.$doc_numero;

			$this->load->library('XMLSecurityDSig');
		$this->load->library('XMLSecurityKey');
		$doc = new DOMDocument();
		$doc->load('xml/'.$filename.'.xml');
		//$doc->xmlStandalone = false;
		//$doc->formatOutput = true;
		//$doc->preserveWhiteSpace = false;
		
		// Crear un nuevo objeto de seguridad
		$objDSig = new XMLSecurityDSig();
		// Utilizar la canonización exclusiva de c14n
		$objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
		// Firmar con SHA-256
		$objDSig->addReference(
		    $doc,
		    XMLSecurityDSig::SHA1,
		    array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'),
		    array('force_uri' => true)
		);
		//Crear una nueva clave de seguridad (privada)
		$objKey = new XMLSecurityKey;
		$objKey->init(XMLSecurityKey::RSA_SHA1, array('type' => 'private'));
		//Cargamos la clave privada
		
		$objKey->loadKey('dubau.key', true);
		$objDSig->sign($objKey);
		// Agregue la clave pública asociada a la firma
		$objDSig->add509Cert(file_get_contents('dubau.cer'), true, false, array('subjectName' => true)); // array('issuerSerial' => true, 'subjectName' => true));
		// Anexar la firma al XML
		$objDSig->appendSignature($doc->getElementsByTagName('ExtensionContent')->item(1));
		
		//$doc->formatOutput = true;

		// Guardar el XML firmado
		$doc->save('xml_firmado/'.$filename.'.xml');

//var_dump(expression)
		$Hash=$doc->getElementsByTagName('DigestValue')->item(0)->nodeValue;

//******************************ENVIAR A SUNAT
		//$filename="20380456444-03-F001-666";//'20380456444-03-F002-00000026';// 

		$folder='xml_firmado/';
		$pathXmlfile=$folder.$filename.'.xml';
		$pathZipfile=$folder.$filename.'.zip';
	
		$zip = new ZipArchive;
		$zip->open($pathZipfile, ZipArchive::CREATE); 
		$localfile = basename($pathXmlfile);
		$zip->addFile($pathXmlfile,$localfile);
		$zip->close();

		//Username>20380456444MODDATOS
		//Password>moddatos
		$wsdlURL = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';

		$XMLString = '<?xml version="1.0" encoding="UTF-8"?>
		<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
		 <soapenv:Header>
		     <wsse:Security>
		         <wsse:UsernameToken>
		             <wsse:Username>20566229774LVENEEDO</wsse:Username>
		             <wsse:Password>oialimull</wsse:Password>
		         </wsse:UsernameToken>
		     </wsse:Security>
		 </soapenv:Header>
		 <soapenv:Body>
		     <ser:sendBill>
		        <fileName>'.$filename.'.zip</fileName>
		        <contentFile>' . base64_encode(file_get_contents($pathZipfile)) . '</contentFile>
		     </ser:sendBill>
		 </soapenv:Body>
		</soapenv:Envelope>';
		//echo base64_encode(file_get_contents($pathZipfile));

		$this->load->library('Feedsoap');
		$feedsoap = new Feedsoap();
		$feedsoap->SoapClientCall($XMLString); 
		$feedsoap->__call("sendBill", array(), array());
		$result = $feedsoap->__getLastResponse();
		//Descargamos el Archivo Response
		$archivo = fopen($folder.'C'.$filename.'.xml','w+');
		fputs($archivo,$result);		
		fclose($archivo);

		//LEEMOS EL ARCHIVO XML
		$xml = simplexml_load_file($folder.'/C'.$filename.'.xml'); 
		foreach ($xml->xpath('//applicationResponse') as $response){ }
		//AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)
		$cdr=base64_decode($response);
		$archivo = fopen($folder.'R-'.$filename.'.zip','w+');
		fputs($archivo,$cdr);
		fclose($archivo);

		//DESCOMPRIMIR ARCHIVO
		$zip = new ZipArchive;
		$res = $zip->open($folder.'R-'.$filename.'.zip');

		if ($res === TRUE) {
		$zip->extractTo($folder);
		$zip->close();
			//echo 'ok';



			$r_doc = new DOMDocument();
			$r_doc->load($folder.'R-'.$filename.'.xml');
			$respuesta = $r_doc->getElementsByTagName('ResponseCode')->item(0)->nodeValue.'|'.$r_doc->getElementsByTagName('Description')->item(0)->nodeValue;


			$arr=array(
				'hash'=>$Hash,
				'respuesta'=>$respuesta,
				'zip-xml'=>base64_encode(file_get_contents($pathZipfile)),
				'zip-cdr'=>base64_encode(file_get_contents($folder.'R-'.$filename.'.zip')));
			header('Content-Type: application/json');
	    	echo json_encode( $arr );


		} else {
			echo 'failed';
		}
		//Eliminamos el Archivo Response
		unlink($folder.'C'.$filename.'.xml');


}

public function enviar($filename){
		

		//$filename="20380456444-03-F001-666";//'20380456444-03-F002-00000026';// 
		$folder='xml_firmado/';
		$pathXmlfile=$folder.$filename.'.xml';
		$pathZipfile=$folder.$filename.'.zip';
	
		$zip = new ZipArchive;
		$zip->open($pathZipfile, ZipArchive::CREATE); 
		$localfile = basename($pathXmlfile);
		$zip->addFile($pathXmlfile,$localfile);
		$zip->close();

		//Username>20380456444MODDATOS
		//Password>moddatos
		$wsdlURL = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';

		$XMLString = '<?xml version="1.0" encoding="UTF-8"?>
		<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
		 <soapenv:Header>
		     <wsse:Security>
		         <wsse:UsernameToken>
		             <wsse:Username>20566229774LVENEEDO</wsse:Username>
		             <wsse:Password>oialimull</wsse:Password>
		         </wsse:UsernameToken>
		     </wsse:Security>
		 </soapenv:Header>
		 <soapenv:Body>
		     <ser:sendBill>
		        <fileName>'.$filename.'.zip</fileName>
		        <contentFile>' . base64_encode(file_get_contents($pathZipfile)) . '</contentFile>
		     </ser:sendBill>
		 </soapenv:Body>
		</soapenv:Envelope>';
		//echo base64_encode(file_get_contents($pathZipfile));

		$this->load->library('Feedsoap');
		$feedsoap = new Feedsoap();
		$feedsoap->SoapClientCall($XMLString); 
		$feedsoap->__call("sendBill", array(), array());
		$result = $feedsoap->__getLastResponse();
		//Descargamos el Archivo Response
		$archivo = fopen($folder.'C'.$filename.'.xml','w+');
		fputs($archivo,$result);		
		fclose($archivo);

		//LEEMOS EL ARCHIVO XML
		$xml = simplexml_load_file($folder.'/C'.$filename.'.xml'); 
		foreach ($xml->xpath('//applicationResponse') as $response){ }
		//AQUI DESCARGAMOS EL ARCHIVO CDR(CONSTANCIA DE RECEPCIÓN)
		$cdr=base64_decode($response);
		$archivo = fopen($folder.'R-'.$filename.'.zip','w+');
		fputs($archivo,$cdr);
		fclose($archivo);

		//DESCOMPRIMIR ARCHIVO
		$zip = new ZipArchive;
		$res = $zip->open($folder.'R-'.$filename.'.zip');
		if ($res === TRUE) {
		$zip->extractTo($folder);
		$zip->close();
			echo 'ok';
		} else {
			echo 'failed';
		}
		//Eliminamos el Archivo Response
		unlink($folder.'C'.$filename.'.xml');
}

public function firmar($filename){
		//$filename='20380456444-03-F001-666';// '20380456444-03-F002-00000026';//

		$this->load->library('XMLSecurityDSig');
		$this->load->library('XMLSecurityKey');
		$doc = new DOMDocument();
		$doc->load('xml/'.$filename.'.xml');
		//$doc->xmlStandalone = false;
		//$doc->formatOutput = true;
		//$doc->preserveWhiteSpace = false;
		
		// Crear un nuevo objeto de seguridad
		$objDSig = new XMLSecurityDSig();
		// Utilizar la canonización exclusiva de c14n
		$objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
		// Firmar con SHA-256
		$objDSig->addReference(
		    $doc,
		    XMLSecurityDSig::SHA1,
		    array('http://www.w3.org/2000/09/xmldsig#enveloped-signature'),
		    array('force_uri' => true)
		);
		//Crear una nueva clave de seguridad (privada)
		$objKey = new XMLSecurityKey;
		$objKey->init(XMLSecurityKey::RSA_SHA1, array('type' => 'private'));
		//Cargamos la clave privada
		
		$objKey->loadKey('dubau.key', true);
		$objDSig->sign($objKey);
		// Agregue la clave pública asociada a la firma
		$objDSig->add509Cert(file_get_contents('dubau.cer'), true, false, array('subjectName' => true)); // array('issuerSerial' => true, 'subjectName' => true));
		// Anexar la firma al XML
		$objDSig->appendSignature($doc->getElementsByTagName('ExtensionContent')->item(1));
		
		//$doc->formatOutput = true;

		// Guardar el XML firmado
		$doc->save('xml_firmado/'.$filename.'.xml');
	}
	public function generar(){
		$method = $_SERVER['REQUEST_METHOD'];

		if($method!='POST'){
			json_output(400,array('status'=>400, 'message'=>'Bad request.'));
		}else{
			$cab = json_decode(file_get_contents('php://input'), true);
			
			$emp_tipo_documento=$cab['emp_tipo_documento'];
			$emp_ruc=$cab['emp_ruc'];
			$emp_razonsocial=$cab['emp_razonsocial'];
			$emp_nombrecomercial=$cab['emp_nombrecomercial'];
			$emp_direccion=$cab['emp_direccion'];
			$emp_distrito=$cab['emp_distrito'];
			$emp_provincia=$cab['emp_provincia'];
			$emp_departamento=$cab['emp_departamento'];
			$emp_ubigeo=$cab['emp_ubigeo'];
			$emp_pais=$cab['emp_pais'];
			$doc_enviaws=$cab['doc_enviaws'];

			$cli_tipo_documento=$cab['cli_tipo_documento'];
			$cli_numero=$cab['cli_numero'];
			$cli_nombre=$cab['cli_nombre'];

			$doc_tipo_documento=$cab['doc_tipo_documento'];
			$doc_numero=$cab['doc_numero'];
			$doc_fecha=$cab['doc_fecha'];
			$doc_gravada=$cab['doc_gravada'];
			$doc_igv=$cab['doc_igv'];
			$doc_descuento=$cab['doc_descuento'];
			$doc_exonerada=$cab['doc_exonerada'];
			$doc_gratuita=$cab['doc_gratuita'];
			$doc_inafecta=$cab['doc_inafecta'];
			$doc_isc=$cab['doc_isc'];
			$doc_moneda=$cab['doc_moneda'];
			$doc_otros_cargos=$cab['doc_otros_cargos'];
			$doc_otros_tributos=$cab['doc_otros_tributos'];
			$doc_total=$cab['doc_total'];
			

			$file =$emp_ruc.'-'.$doc_tipo_documento.'-'.$doc_numero.'.xml';

			$dom = new DomDocument("1.0","ISO-8859-1");
			$dom->xmlStandalone = false;
			//$dom->formatOutput = true;
			$dom->preserveWhiteSpace = false;

			$Invoice = $dom->createElement('Invoice');
			$dom->appendChild($Invoice);
			
			$Invoice->setAttribute('xmlns','urn:oasis:names:specification:ubl:schema:xsd:Invoice-2');
			$Invoice->setAttribute('xmlns:cac','urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
			$Invoice->setAttribute('xmlns:cbc','urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
			$Invoice->setAttribute('xmlns:ccts','urn:un:unece:uncefact:documentation:2');
			$Invoice->setAttribute('xmlns:ds','http://www.w3.org/2000/09/xmldsig#');
			$Invoice->setAttribute('xmlns:ext','urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2');
			$Invoice->setAttribute('xmlns:qdt','urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2');
			$Invoice->setAttribute('xmlns:sac','urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1');
			$Invoice->setAttribute('xmlns:udt','urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2');
			$Invoice->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');

			$UBLExtensions = $dom->createElement('ext:UBLExtensions');
			$Invoice->appendChild($UBLExtensions);

			$UBLExtension1 = $dom->createElement('ext:UBLExtension');
			$UBLExtensions->appendChild($UBLExtension1);
			$ExtensionContent1 = $dom->createElement('ext:ExtensionContent');
			$UBLExtension1->appendChild($ExtensionContent1);

			$AdditionalInformation = $dom->createElement('sac:AdditionalInformation');
			$ExtensionContent1->appendChild($AdditionalInformation);

			if($doc_gravada != '0.00'){
				//agrupa1 getDocu_gravada
				$AdditionalMonetaryTotal1 = $dom->createElement('sac:AdditionalMonetaryTotal');
				$AdditionalInformation->appendChild($AdditionalMonetaryTotal1);
				$AdditionalMonetaryTotal1->appendChild($dom->createElement('cbc:ID',"1001"));
				$PayableAmount1 = $dom->createElement('cbc:PayableAmount',"100.00");
				$PayableAmount1->setAttribute('currencyID',$doc_moneda);
				$AdditionalMonetaryTotal1->appendChild($PayableAmount1);
				$anticipoCero1001="1";
			}
			if($doc_inafecta != '0.00'){
				//agrupa2 getDocu_inafecta
				$AdditionalMonetaryTotal2 = $dom->createElement('sac:AdditionalMonetaryTotal');
				$AdditionalInformation->appendChild($AdditionalMonetaryTotal2);
				$AdditionalMonetaryTotal2->appendChild($dom->createElement('cbc:ID',"1002"));
				$PayableAmount2 = $dom->createElement('cbc:PayableAmount',$doc_inafecta);
				$PayableAmount2->setAttribute('currencyID',$doc_moneda);
				$AdditionalMonetaryTotal2->appendChild($PayableAmount2);
				$anticipoCero1002="1";
			}
			if($doc_exonerada){
				//agrupa3 getDocu_exonerada
				$AdditionalMonetaryTotal3 = $dom->createElement('sac:AdditionalMonetaryTotal');
				$AdditionalInformation->appendChild($AdditionalMonetaryTotal3);
				$AdditionalMonetaryTotal3->appendChild($dom->createElement('cbc:ID',"1003"));
				$PayableAmount3 = $dom->createElement('cbc:PayableAmount',$doc_exonerada);
				$PayableAmount3->setAttribute('currencyID',$doc_moneda);
				$AdditionalMonetaryTotal3->appendChild($PayableAmount3);
				$anticipoCero1003="1";
			}
			if($doc_gratuita){
				//agrupa4 getDocu_gratuita
				$AdditionalMonetaryTotal4 = $dom->createElement('sac:AdditionalMonetaryTotal');
				$AdditionalInformation->appendChild($AdditionalMonetaryTotal4);
				$AdditionalMonetaryTotal4->appendChild($dom->createElement('cbc:ID',"1004"));
				$PayableAmount4 = $dom->createElement('cbc:PayableAmount',$doc_gratuita);
				$PayableAmount4->setAttribute('currencyID',$doc_moneda);
				$AdditionalMonetaryTotal4->appendChild($PayableAmount4);
			}
			if($doc_descuento){
				//agrupa5 getDocu_descuento
				$AdditionalMonetaryTotal5 = $dom->createElement('sac:AdditionalMonetaryTotal');
				$AdditionalInformation->appendChild($AdditionalMonetaryTotal5);
				$AdditionalMonetaryTotal5->appendChild($dom->createElement('cbc:ID',"2005"));
				$PayableAmount5 = $dom->createElement('cbc:PayableAmount',$doc_descuento);
				$PayableAmount5->setAttribute('currencyID',$doc_moneda);
				$AdditionalMonetaryTotal5->appendChild($PayableAmount5);
			}

			foreach ($cab['leyenda'] as $item) { 
				$AdditionalProperty = $dom->createElement('sac:AdditionalProperty');
				$AdditionalInformation->appendChild($AdditionalProperty);
				$AdditionalProperty->appendChild($dom->createElement('cbc:ID',$item['codigo']));
				$Value=$dom->createElement('cbc:Value',$item['descripcion']);
				$AdditionalProperty->appendChild($Value);
				//$Value->appendChild($dom->createCDATASection("CIEN Y 00/100"));
			}

	//signature 
			$UBLExtension2 = $dom->createElement('ext:UBLExtension');
			$UBLExtensions->appendChild($UBLExtension2);
			$ExtensionContent2 = $dom->createElement('ext:ExtensionContent',' ');
			$UBLExtension2->appendChild($ExtensionContent2);		


			//bloque 1
			$Invoice->appendChild($dom->createElement('cbc:UBLVersionID','2.0'));
			$Invoice->appendChild($dom->createElement('cbc:CustomizationID','1.0'));
			$Invoice->appendChild($dom->createElement('cbc:ID',$doc_numero));
			$Invoice->appendChild($dom->createElement('cbc:IssueDate',$doc_fecha));
			$Invoice->appendChild($dom->createElement('cbc:InvoiceTypeCode',$doc_tipo_documento));
			$Invoice->appendChild($dom->createElement('cbc:DocumentCurrencyCode',$doc_moneda));

			//bloque2 cac:Signature
			$Signature = $dom->createElement('cac:Signature');
			$Invoice->appendChild($Signature);
			$Signature->appendChild($dom->createElement('cbc:ID',$emp_ruc));
			$SignatoryParty = $dom->createElement('cac:SignatoryParty');
			$Signature->appendChild($SignatoryParty);
			$PartyIdentification = $dom->createElement('cac:PartyIdentification');
			$SignatoryParty->appendChild($PartyIdentification);
			$PartyIdentification->appendChild($dom->createElement('cbc:ID',$emp_ruc));
			$PartyName = $dom->createElement('cac:PartyName');
			$SignatoryParty->appendChild($PartyName);
			$Name = $dom->createElement('cbc:Name',$emp_razonsocial);
			$PartyName->appendChild($Name);
			//$Name->appendChild($dom->createCDATASection("NOMBRE"));


			$DigitalSignatureAttachment = $dom->createElement('cac:DigitalSignatureAttachment');
			$Signature->appendChild($DigitalSignatureAttachment);
			$ExternalReference = $dom->createElement('cac:ExternalReference');
			$DigitalSignatureAttachment->appendChild($ExternalReference);
			$ExternalReference->appendChild($dom->createElement('cbc:URI',$emp_ruc));

			//bloque3 cac:AccountingSupplierParty
			$AccountingSupplierParty = $dom->createElement('cac:AccountingSupplierParty');
			$Invoice->appendChild($AccountingSupplierParty);
			$AccountingSupplierParty->appendChild($dom->createElement('cbc:CustomerAssignedAccountID',$emp_ruc));
			$AccountingSupplierParty->appendChild($dom->createElement('cbc:AdditionalAccountID',$emp_tipo_documento));
			$Party = $dom->createElement('cac:Party');
			$AccountingSupplierParty->appendChild($Party);
			$PartyName = $dom->createElement('cac:PartyName');
			$Party->appendChild($PartyName);
			$Name = $dom->createElement('cbc:Name',$emp_razonsocial);
			$PartyName->appendChild($Name);
			//$Name->appendChild($dom->createCDATASection("NOMBRE"));


			$PostalAddress = $dom->createElement('cac:PostalAddress');
			$Party->appendChild($PostalAddress);
			$PostalAddress->appendChild($dom->createElement('cbc:ID',$emp_ubigeo));
			$PostalAddress->appendChild($dom->createElement('cbc:StreetName',$emp_direccion));
			$PostalAddress->appendChild($dom->createElement('cbc:CitySubdivisionName',''));
			$PostalAddress->appendChild($dom->createElement('cbc:CityName',$emp_provincia));
			$PostalAddress->appendChild($dom->createElement('cbc:CountrySubentity',$emp_departamento));
			$PostalAddress->appendChild($dom->createElement('cbc:District',$emp_distrito));
			$Country = $dom->createElement('cac:Country');
			$PostalAddress->appendChild($Country);
			$Country->appendChild($dom->createElement('cbc:IdentificationCode',$emp_pais));
			$PartyLegalEntity = $dom->createElement('cac:PartyLegalEntity');
			$Party->appendChild($PartyLegalEntity);
			$RegistrationName = $dom->createElement('cbc:RegistrationName',$emp_razonsocial);
			$PartyLegalEntity->appendChild($RegistrationName);
			//$RegistrationName->appendChild($dom->createCDATASection("NOMBRE"));
			//bloque 4
			$AccountingCustomerParty = $dom->createElement('cac:AccountingCustomerParty');
			$Invoice->appendChild($AccountingCustomerParty);
			$AccountingCustomerParty->appendChild($dom->createElement('cbc:CustomerAssignedAccountID',$cli_numero));
			$AccountingCustomerParty->appendChild($dom->createElement('cbc:AdditionalAccountID',$cli_tipo_documento));
			$Party2 = $dom->createElement('cac:Party');
			$AccountingCustomerParty->appendChild($Party2);
			$PartyLegalEntity2 = $dom->createElement('cac:PartyLegalEntity');
			$Party2->appendChild($PartyLegalEntity2);
			$PartyLegalEntity2->appendChild($dom->createElement('cbc:RegistrationName',$cli_nombre));

			//bloque 5 getDocu_igv
			$TaxTotal = $dom->createElement('cac:TaxTotal');
			$Invoice->appendChild($TaxTotal);
			$TaxAmount = $dom->createElement('cbc:TaxAmount',$doc_igv);
			$TaxTotal->appendChild($TaxAmount);
			$TaxAmount->setAttribute('currencyID',$doc_moneda);
			$TaxSubtotal = $dom->createElement('cac:TaxSubtotal');
			$TaxTotal->appendChild($TaxSubtotal);
			$TaxAmount2 = $dom->createElement('cbc:TaxAmount',$doc_igv);
			$TaxSubtotal->appendChild($TaxAmount2);
			$TaxAmount2->setAttribute('currencyID',$doc_moneda);
			$TaxCategory = $dom->createElement('cac:TaxCategory');
			$TaxSubtotal->appendChild($TaxCategory);
			$TaxScheme = $dom->createElement('cac:TaxScheme');
			$TaxCategory->appendChild($TaxScheme);
			$TaxScheme->appendChild($dom->createElement('cbc:ID','1000'));
			$TaxScheme->appendChild($dom->createElement('cbc:Name','IGV'));
			$TaxScheme->appendChild($dom->createElement('cbc:TaxTypeCode','VAT'));

			//bloque 6
			$LegalMonetaryTotal = $dom->createElement('cac:LegalMonetaryTotal');
			$Invoice->appendChild($LegalMonetaryTotal);
			
			if($doc_descuento != '0.00'){
				$AllowanceTotalAmount = $dom->createElement('cbc:AllowanceTotalAmount',$doc_descuento);
				$LegalMonetaryTotal->appendChild($AllowanceTotalAmount);
				$AllowanceTotalAmount->setAttribute('currencyID',$doc_moneda);
			}
			
			$PayableAmount = $dom->createElement('cbc:PayableAmount',$doc_total);
			$LegalMonetaryTotal->appendChild($PayableAmount);
			$PayableAmount->setAttribute('currencyID',$doc_moneda);

			//detalle factura
			foreach ($cab['detalle'] as $item) {
			
				$InvoiceLine = $dom->createElement('cac:InvoiceLine');
				$Invoice->appendChild($InvoiceLine);
				$InvoiceLine->appendChild($dom->createElement('cbc:ID',$item['orden']));
				$InvoicedQuantity = $dom->createElement('cbc:InvoicedQuantity',$item['cantidad']);
				$InvoiceLine->appendChild($InvoicedQuantity);
				$InvoicedQuantity->setAttribute('unitCode',$item['unidad']);

				$LineExtensionAmount = $dom->createElement('cbc:LineExtensionAmount',$item['subtotal']);
				$InvoiceLine->appendChild($LineExtensionAmount);
				$LineExtensionAmount->setAttribute('currencyID',$doc_moneda);

				$PricingReference = $dom->createElement('cac:PricingReference');
				$InvoiceLine->appendChild($PricingReference);
				$AlternativeConditionPrice = $dom->createElement('cac:AlternativeConditionPrice');
				$PricingReference->appendChild($AlternativeConditionPrice);
				$PriceAmount = $dom->createElement('cbc:PriceAmount',$item['precio']);
				$AlternativeConditionPrice->appendChild($PriceAmount);
				$PriceAmount->setAttribute('currencyID',$doc_moneda);
				$AlternativeConditionPrice->appendChild($dom->createElement('cbc:PriceTypeCode','01'));

				if($item['precio_no_onerosa'] != '0.00'){
					$AlternativeConditionPrice2 = $dom->createElement('cac:AlternativeConditionPrice');
					$PricingReference->appendChild($AlternativeConditionPrice2);
					$PriceAmount2 = $dom->createElement('cbc:PriceAmount',$item['precio_no_onerosa']);
					$AlternativeConditionPrice2->appendChild($PriceAmount2);
					$PriceAmount2->setAttribute('currencyID',$doc_moneda);
					$AlternativeConditionPrice2->appendChild($dom->createElement('cbc:PriceTypeCode','02'));
				}

				$TaxTotal = $dom->createElement('cac:TaxTotal');
				$InvoiceLine->appendChild($TaxTotal);
				$TaxAmount = $dom->createElement('cbc:TaxAmount',$item['igv']);
				$TaxTotal->appendChild($TaxAmount);
				$TaxAmount->setAttribute('currencyID',$doc_moneda);
				$TaxSubtotal = $dom->createElement('cac:TaxSubtotal');
				$TaxTotal->appendChild($TaxSubtotal);
				$TaxableAmount = $dom->createElement('cbc:TaxableAmount',$item['igv']);
				$TaxSubtotal->appendChild($TaxableAmount);
				$TaxableAmount->setAttribute('currencyID',$doc_moneda);
				$TaxAmount2 = $dom->createElement('cbc:TaxAmount',$item['igv']);
				$TaxSubtotal->appendChild($TaxAmount2);
				$TaxAmount2->setAttribute('currencyID',$doc_moneda);
				$TaxSubtotal->appendChild($dom->createElement('cbc:Percent','0.0'));

				$TaxCategory = $dom->createElement('cac:TaxCategory');
				$TaxSubtotal->appendChild($TaxCategory);
				$TaxCategory->appendChild($dom->createElement('cbc:ID','VAT'));
				$TaxCategory->appendChild($dom->createElement('cbc:TaxExemptionReasonCode',$item['afectacion']));
				$TaxCategory->appendChild($dom->createElement('cbc:TierRange','10'));
				$TaxScheme = $dom->createElement('cac:TaxScheme');
				$TaxCategory->appendChild($TaxScheme);
				$TaxScheme->appendChild($dom->createElement('cbc:ID','1000'));
				$TaxScheme->appendChild($dom->createElement('cbc:Name','IGV'));
				$TaxScheme->appendChild($dom->createElement('cbc:TaxTypeCode','VAT'));

				$Item = $dom->createElement('cac:Item');
				$InvoiceLine->appendChild($Item);
				$Item->appendChild($dom->createElement('cbc:Description',$item['descripcion']));
				$SellersItemIdentification = $dom->createElement('cac:SellersItemIdentification');
				$Item->appendChild($SellersItemIdentification);
				$SellersItemIdentification->appendChild($dom->createElement('cbc:ID',$item['codigo']));

				$Price = $dom->createElement('cac:Price');
				$InvoiceLine->appendChild($Price);
				$PriceAmount = $dom->createElement('cbc:PriceAmount',$item['precio']);
				$Price->appendChild($PriceAmount);
				$PriceAmount->setAttribute('currencyID',$doc_moneda);
			}

			
			$dom->formatOutput = true;
			
			$dom->save( 'xml/'.$file);

		}
	}


	public function generar_old(){

		$ruc='20380456444';
$filename='xml/'.$ruc.'-03-F002-00000026.xml';
		$xml = new DomDocument('1.0', 'ISO-8859-1');
$xml->standalone         = false;
$xml->preserveWhiteSpace = false;
$Invoice = $xml->createElement('Invoice');
$Invoice = $xml->appendChild($Invoice);
// Set the attributes.
$Invoice->setAttribute('xmlns', 'urn:oasis:names:specification:ubl:schema:xsd:Invoice-2');
$Invoice->setAttribute('xmlns:cac', 'urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2');
$Invoice->setAttribute('xmlns:cbc', 'urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2');
$Invoice->setAttribute('xmlns:ccts', "urn:un:unece:uncefact:documentation:2");
$Invoice->setAttribute('xmlns:ds', "http://www.w3.org/2000/09/xmldsig#");
$Invoice->setAttribute('xmlns:ext', "urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2");
$Invoice->setAttribute('xmlns:qdt', "urn:oasis:names:specification:ubl:schema:xsd:QualifiedDatatypes-2");
$Invoice->setAttribute('xmlns:sac', "urn:sunat:names:specification:ubl:peru:schema:xsd:SunatAggregateComponents-1");
$Invoice->setAttribute('xmlns:udt', "urn:un:unece:uncefact:data:specification:UnqualifiedDataTypesSchemaModule:2");
$UBLExtension = $xml->createElement('ext:UBLExtensions');
$UBLExtension = $Invoice->appendChild($UBLExtension);
$ext = $xml->createElement('ext:UBLExtension');
$ext = $UBLExtension->appendChild($ext);
$contents = $xml->createElement('ext:ExtensionContent');
$contents = $ext->appendChild($contents);
$sac = $xml->createElement('sac:AdditionalInformation');
$sac = $contents->appendChild($sac);
$monetary = $xml->createElement('sac:AdditionalMonetaryTotal');
$monetary = $sac->appendChild($monetary);
$cbc = $xml->createElement('cbc:ID', '2005');
$cbc = $monetary->appendChild($cbc);
$cbc = $xml->createElement('cbc:PayableAmount', '0.00');
$cbc = $monetary->appendChild($cbc);
$cbc->setAttribute('currencyID', "PEN");
$monetary = $xml->createElement('sac:AdditionalMonetaryTotal');
$monetary = $sac->appendChild($monetary);
$cbc = $xml->createElement('cbc:ID', '1001');
$cbc = $monetary->appendChild($cbc);
$cbc = $xml->createElement('cbc:PayableAmount', '100.00');
$cbc = $monetary->appendChild($cbc);
$cbc->setAttribute('currencyID', "PEN");
$monetary = $xml->createElement('sac:AdditionalMonetaryTotal');
$monetary = $sac->appendChild($monetary);
$cbc = $xml->createElement('cbc:ID', '1002');
$cbc = $monetary->appendChild($cbc);
$cbc = $xml->createElement('cbc:PayableAmount', '0.00');
$cbc = $monetary->appendChild($cbc);
$cbc->setAttribute('currencyID', "PEN");
$monetary = $xml->createElement('sac:AdditionalMonetaryTotal');
$monetary = $sac->appendChild($monetary);
$cbc = $xml->createElement('cbc:ID', '1003');
$cbc = $monetary->appendChild($cbc);
$cbc = $xml->createElement('cbc:PayableAmount', '0.00');
$cbc = $monetary->appendChild($cbc);
$cbc->setAttribute('currencyID', "PEN");
$aditional = $xml->createElement('sac:AdditionalProperty');
$aditional = $sac->appendChild($aditional);
$cbc = $xml->createElement('cbc:ID', '1000');
$cbc = $aditional->appendChild($cbc);
$cbc = $xml->createElement('cbc:Value', '1000 AV BOLIVIA');
$cbc = $aditional->appendChild($cbc);
$aditional = $xml->createElement('sac:AdditionalProperty');
$aditional = $sac->appendChild($aditional);
$cbc = $xml->createElement('cbc:ID', '1002');
$cbc = $aditional->appendChild($cbc);
$cbc = $xml->createElement('cbc:Value', '1002 AAAAA');
$cbc = $aditional->appendChild($cbc);
$aditional = $xml->createElement('sac:AdditionalProperty');
$aditional = $sac->appendChild($aditional);
$cbc = $xml->createElement('cbc:ID', '2000');
$cbc = $aditional->appendChild($cbc);
$cbc = $xml->createElement('cbc:Value', '2000 BBBB');
$cbc = $aditional->appendChild($cbc);
$aditional = $xml->createElement('sac:AdditionalProperty');
$aditional = $sac->appendChild($aditional);
$cbc = $xml->createElement('cbc:ID', '2002');
$cbc = $aditional->appendChild($cbc);
$cbc = $xml->createElement('cbc:Value', '2002 CCC');
$cbc = $aditional->appendChild($cbc);
$aditional = $xml->createElement('sac:AdditionalProperty');
$aditional = $sac->appendChild($aditional);
$cbc = $xml->createElement('cbc:ID', '2003');
$cbc = $aditional->appendChild($cbc);
$cbc = $xml->createElement('cbc:Value', '2003 DDD');
$cbc = $aditional->appendChild($cbc);
$aditional = $xml->createElement('sac:AdditionalProperty');
$aditional = $sac->appendChild($aditional);
$cbc = $xml->createElement('cbc:ID', '2005');
$cbc = $aditional->appendChild($cbc);
$cbc = $xml->createElement('cbc:Value', '2005 EEE');
$cbc = $aditional->appendChild($cbc);
$aditional = $xml->createElement('sac:AdditionalProperty');
$aditional = $sac->appendChild($aditional);
$cbc = $xml->createElement('cbc:ID', '2006');
$cbc = $aditional->appendChild($cbc);
$cbc = $xml->createElement('cbc:Value', '2006 AAAAA');
$cbc = $aditional->appendChild($cbc);
$aditional = $xml->createElement('sac:AdditionalProperty');
$aditional = $sac->appendChild($aditional);
$cbc = $xml->createElement('cbc:ID', '2007');
$cbc = $aditional->appendChild($cbc);
$cbc = $xml->createElement('cbc:Value', '2007 GGG');
$cbc = $aditional->appendChild($cbc);
$aditional = $xml->createElement('sac:AdditionalProperty');
$aditional = $sac->appendChild($aditional);
$cbc = $xml->createElement('cbc:ID', '3000');
$cbc = $aditional->appendChild($cbc);
$cbc = $xml->createElement('cbc:Value', '3000 FOB');
$cbc = $aditional->appendChild($cbc);
$sunat = $xml->createElement('sac:SUNATTransaction');
$sunat = $sac->appendChild($sunat);
$cbc = $xml->createElement('cbc:ID', '1');
$cbc = $sunat->appendChild($cbc);
$ext = $xml->createElement('ext:UBLExtension');
$ext = $UBLExtension->appendChild($ext);
$contents = $xml->createElement('ext:ExtensionContent', ' ');
$contents = $ext->appendChild($contents);
$cbc = $xml->createElement('cbc:UBLVersionID', '2.0');
$cbc = $Invoice->appendChild($cbc);
$cbc = $xml->createElement('cbc:CustomizationID', '1.0');
$cbc = $Invoice->appendChild($cbc);
$cbc = $xml->createElement('cbc:ID', 'F002-00000026');
$cbc = $Invoice->appendChild($cbc);
$cbc = $xml->createElement('cbc:IssueDate', '2016-12-12');
$cbc = $Invoice->appendChild($cbc);
$cbc = $xml->createElement('cbc:InvoiceTypeCode', '03');
$cbc = $Invoice->appendChild($cbc);
$cbc = $xml->createElement('cbc:DocumentCurrencyCode', 'PEN');
$cbc = $Invoice->appendChild($cbc);
$cac_signature = $xml->createElement('cac:Signature');
$cac_signature = $Invoice->appendChild($cac_signature);
$cbc = $xml->createElement('cbc:ID', $ruc);
$cbc = $cac_signature->appendChild($cbc);
$cbc = $xml->createElement('cbc:Note', 'Elaborado por Sistema de Emision Electronica Facturador SUNAT (SEE-SFS) 1.0.0');
$cbc = $cac_signature->appendChild($cbc);
$cbc = $xml->createElement('cbc:ValidatorID', '780086');
$cbc = $cac_signature->appendChild($cbc);
$cac_signatory = $xml->createElement('cac:SignatoryParty');
$cac_signatory = $cac_signature->appendChild($cac_signatory);
$cac = $xml->createElement('cac:PartyIdentification');
$cac = $cac_signatory->appendChild($cac);
$cbc = $xml->createElement('cbc:ID', $ruc);
$cbc = $cac->appendChild($cbc);
$cac = $xml->createElement('cac:PartyName');
$cac = $cac_signatory->appendChild($cac);
$cbc = $xml->createElement('cbc:Name', 'DESARROLLO DE SISTEMAS INTEGRADOS DE GESTIÓN');
$cbc = $cac->appendChild($cbc);
$agent = $xml->createElement('cac:AgentParty');
$agent = $cac_signatory->appendChild($agent);
$cac = $xml->createElement('cac:PartyIdentification');
$cac = $agent->appendChild($cac);
$cbc = $xml->createElement('cbc:ID', $ruc);
$cbc = $cac->appendChild($cbc);
$cac = $xml->createElement('cac:PartyName');
$cac = $agent->appendChild($cac);
$cbc = $xml->createElement('cbc:Name', 'FERNANDO CARMELO MAMANI BLAS ');
$cbc = $cac->appendChild($cbc);
$cac = $xml->createElement('cac:PartyLegalEntity');
$cac = $agent->appendChild($cac);
$cbc = $xml->createElement('cbc:RegistrationName', 'FERNANDO CARMELO MAMANI BLAS ');
$cbc = $cac->appendChild($cbc);
$cac_digital = $xml->createElement('cac:DigitalSignatureAttachment');
$cac_digital = $cac_signature->appendChild($cac_digital);
$cac = $xml->createElement('cac:ExternalReference');
$cac = $cac_digital->appendChild($cac);
$cbc = $xml->createElement('cbc:URI', 'SIGN');
$cbc = $cac->appendChild($cbc);
$cac_accounting = $xml->createElement('cac:AccountingSupplierParty');
$cac_accounting = $Invoice->appendChild($cac_accounting);
$cbc = $xml->createElement('cbc:CustomerAssignedAccountID', $ruc);
$cbc = $cac_accounting->appendChild($cbc);
$cbc = $xml->createElement('cbc:AdditionalAccountID', '6');
$cbc = $cac_accounting->appendChild($cbc);
$cac_party = $xml->createElement('cac:Party');
$cac_party = $cac_accounting->appendChild($cac_party);
$cac = $xml->createElement('cac:PartyName');
$cac = $cac_party->appendChild($cac);
$cbc = $xml->createElement('cbc:Name', 'DESARROLLO DE SISTEMAS INTEGRADOS DE GESTIÓN');
$cbc = $cac->appendChild($cbc);
$address = $xml->createElement('cac:PostalAddress');
$address = $cac_party->appendChild($address);
$cbc = $xml->createElement('cbc:ID', '040101');
$cbc = $address->appendChild($cbc);
$cbc = $xml->createElement('cbc:StreetName', 'Mercado 3 de Octubre Av-Brasil, B4, Comité 1 - JLB y Rivero');
$cbc = $address->appendChild($cbc);
$country = $xml->createElement('cac:Country');
$country = $address->appendChild($country);
$cbc = $xml->createElement('cbc:IdentificationCode', 'PER');
$cbc = $country->appendChild($cbc);
$legal = $xml->createElement('cac:PartyLegalEntity');
$legal = $cac_party->appendChild($legal);
$cbc = $xml->createElement('cbc:RegistrationName', 'FERNANDO CARMELO MAMANI BLAS ');
$cbc = $legal->appendChild($cbc);
$cac_accounting = $xml->createElement('cac:AccountingCustomerParty');
$cac_accounting = $Invoice->appendChild($cac_accounting);
$cbc = $xml->createElement('cbc:CustomerAssignedAccountID', '20455108757');
$cbc = $cac_accounting->appendChild($cbc);
$cbc = $xml->createElement('cbc:AdditionalAccountID', '6');
$cbc = $cac_accounting->appendChild($cbc);
$cac_party = $xml->createElement('cac:Party');
$cac_party = $cac_accounting->appendChild($cac_party);
$legal = $xml->createElement('cac:PartyLegalEntity');
$legal = $cac_party->appendChild($legal);
$cbc = $xml->createElement('cbc:RegistrationName', 'VIP');
$cbc = $legal->appendChild($cbc);
$seller = $xml->createElement('cac:SellerSupplierParty');
$seller = $Invoice->appendChild($seller);
$cac_party = $xml->createElement('cac:Party');
$cac_party = $seller->appendChild($cac_party);
$address = $xml->createElement('cac:PostalAddress');
$address = $cac_party->appendChild($address);
$cbc = $xml->createElement('cbc:AddressTypeCode', '0');
$cbc = $address->appendChild($cbc);
$taxtotal = $xml->createElement('cac:TaxTotal');
$taxtotal = $Invoice->appendChild($taxtotal);
$cbc = $xml->createElement('cbc:TaxAmount', '18.00');
$cbc = $taxtotal->appendChild($cbc);
$cbc->setAttribute('currencyID', "PEN");
$taxtsubtotal = $xml->createElement('cac:TaxSubtotal');
$taxtsubtotal = $taxtotal->appendChild($taxtsubtotal);
$cbc = $xml->createElement('cbc:TaxAmount', '18.00');
$cbc = $taxtsubtotal->appendChild($cbc);
$cbc->setAttribute('currencyID', "PEN");
$taxtcategory = $xml->createElement('cac:TaxCategory');
$taxtcategory = $taxtsubtotal->appendChild($taxtcategory);
$taxscheme = $xml->createElement('cac:TaxScheme');
$taxscheme = $taxtcategory->appendChild($taxscheme);
$cbc = $xml->createElement('cbc:ID', '1000');
$cbc = $taxscheme->appendChild($cbc);
$cbc = $xml->createElement('cbc:Name', 'IGV');
$cbc = $taxscheme->appendChild($cbc);
$cbc = $xml->createElement('cbc:TaxTypeCode', 'VAT');
$cbc = $taxscheme->appendChild($cbc);
$legal = $xml->createElement('cac:LegalMonetaryTotal');
$legal = $Invoice->appendChild($legal);
$cbc = $xml->createElement('cbc:AllowanceTotalAmount', '0.00');
$cbc = $legal->appendChild($cbc);
$cbc->setAttribute('currencyID', "PEN");
$cbc = $xml->createElement('cbc:ChargeTotalAmount', '0.00');
$cbc = $legal->appendChild($cbc);
$cbc->setAttribute('currencyID', "PEN");
$cbc = $xml->createElement('cbc:PayableAmount', '118.00');
$cbc = $legal->appendChild($cbc);
$cbc->setAttribute('currencyID', "PEN");
$InvoiceLine = $xml->createElement('cac:InvoiceLine');
$InvoiceLine = $Invoice->appendChild($InvoiceLine);
$cbc = $xml->createElement('cbc:ID', '1');
$cbc = $InvoiceLine->appendChild($cbc);
$cbc = $xml->createElement('cbc:InvoicedQuantity', '100.00');
$cbc = $InvoiceLine->appendChild($cbc);
$cbc->setAttribute('unitCode', "ZZ");
$cbc = $xml->createElement('cbc:LineExtensionAmount', '100.00');
$cbc = $InvoiceLine->appendChild($cbc);
$cbc->setAttribute('currencyID', "PEN");
$pricing = $xml->createElement('cac:PricingReference');
$pricing = $InvoiceLine->appendChild($pricing);
$cac = $xml->createElement('cac:AlternativeConditionPrice');
$cac = $pricing->appendChild($cac);
$cbc = $xml->createElement('cbc:PriceAmount', '118.00');
$cbc = $cac->appendChild($cbc);
$cbc->setAttribute('currencyID', "PEN");
$cbc = $xml->createElement('cbc:PriceTypeCode', '01');
$cbc = $cac->appendChild($cbc);
$allowance = $xml->createElement('cac:AllowanceCharge');
$allowance = $InvoiceLine->appendChild($allowance);
$cbc = $xml->createElement('cbc:ChargeIndicator', 'false');
$cbc = $allowance->appendChild($cbc);
$cbc = $xml->createElement('cbc:Amount', '0.00');
$cbc = $allowance->appendChild($cbc);
$cbc->setAttribute('currencyID', "PEN");
$taxtotal = $xml->createElement('cac:TaxTotal');
$taxtotal = $InvoiceLine->appendChild($taxtotal);
$cbc = $xml->createElement('cbc:TaxAmount', '18.00');
$cbc = $taxtotal->appendChild($cbc);
$cbc->setAttribute('currencyID', "PEN");
$taxtsubtotal = $xml->createElement('cac:TaxSubtotal');
$taxtsubtotal = $taxtotal->appendChild($taxtsubtotal);
$cbc = $xml->createElement('cbc:TaxableAmount', '18.00');
$cbc = $taxtsubtotal->appendChild($cbc);
$cbc->setAttribute('currencyID', "PEN");
$cbc = $xml->createElement('cbc:TaxAmount', '18.00');
$cbc = $taxtsubtotal->appendChild($cbc);
$cbc->setAttribute('currencyID', "PEN");
$taxtcategory = $xml->createElement('cac:TaxCategory');
$taxtcategory = $taxtsubtotal->appendChild($taxtcategory);
$cbc = $xml->createElement('cbc:TaxExemptionReasonCode', '10');
$cbc = $taxtcategory->appendChild($cbc);
$taxscheme = $xml->createElement('cac:TaxScheme');
$taxscheme = $taxtcategory->appendChild($taxscheme);
$cbc = $xml->createElement('cbc:ID', '1000');
$cbc = $taxscheme->appendChild($cbc);
$cbc = $xml->createElement('cbc:Name', 'IGV');
$cbc = $taxscheme->appendChild($cbc);
$cbc = $xml->createElement('cbc:TaxTypeCode', 'VAT');
$cbc = $taxscheme->appendChild($cbc);
$item = $xml->createElement('cac:Item');
$item = $InvoiceLine->appendChild($item);
$cbc = $xml->createElement('cbc:Description', 'CLAVO PARA CONCRETO DE  2"');
$cbc = $item->appendChild($cbc);
$sellers = $xml->createElement('cac:SellersItemIdentification');
$sellers = $item->appendChild($sellers);
$cbc = $xml->createElement('cbc:ID', 'ALM');
$cbc = $sellers->appendChild($cbc);
$additional = $xml->createElement('cac:AdditionalItemIdentification');
$additional = $item->appendChild($additional);
$cbc = $xml->createElement('cbc:ID', 'A');
$cbc = $additional->appendChild($cbc);
$price = $xml->createElement('cac:Price');
$price = $InvoiceLine->appendChild($price);
$cbc = $xml->createElement('cbc:PriceAmount', '1.00');
$cbc = $price->appendChild($cbc);
$cbc->setAttribute('currencyID', "PEN");
$xml->formatOutput = true;
$strings_xml       = $xml->saveXML();
$xml->save($filename);

	}

}
