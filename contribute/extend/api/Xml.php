<?php
namespace api;

use DOMDocument;

class Xml {

	public function arrayToXml($arr,$dom=0,$item=0){

		if (!$dom){
		    $dom = new DOMDocument("1.0","utf-8");
		}

		if(!$item){
		    $item = $dom->createElement("root");
		    $dom->appendChild($item);
		}

		foreach ($arr as $key=>$val){
		    $itemx = $dom->createElement(is_string($key)?$key:"item");
		    $item->appendChild($itemx);
		    if (!is_array($val)){
		      $text = $dom->createTextNode($val);
		      $itemx->appendChild($text);
		    }else {
		      $this->arrayToXml($val,$dom,$itemx);
		    }
		}
		return $dom->saveXML();

	}




}

?>