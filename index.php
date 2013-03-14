<?php
/**
  * wechat php test
  */

require_once("wx_action.php");
define("TOKEN", "freeze");

var_export($GLOBALS["HTTP_RAW_POST_DATA"],true);
if (isset($GLOBALS["HTTP_RAW_POST_DATA"])){
    $wx = new WxAction();
    return $wx->execute();
}
else{
    $wechatObj = new wechatCallbackapiTest();
    $wechatObj->valid();
}

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];


        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }
		
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}

?>