<?php
/**
  * wechat php test
  */

require_once("Model.class.php");
class WxAction
{
    public function __construct(){
        $this->writer = new XMLWriter();
        $this->writer->openURI('php://output');
        $this->writer->openMemory();

        $this->writer->startDocument('1.0','utf-8');
        $this->writer->setIndent(4);
        $this->writer->writeAttribute('version', '2.0');

    }

    public function __destruct(){
        
    }

    public function execute(){
        if (!self::checkSignature()){
            return false;
        }
        $kws = self::getKeywords();
        $kws[0] = strtolower($kws[0]);
        $content = "<empty>";
        switch ($kws[0]) {
            case 'morse':
            case 'pi':
            $class_name = ucfirst($kws[0]);
            require_once("$class_name.class.php");
            $t = new $class_name;
            if (!isset($kws[1])){
                $content = $t->showAll();
            }
            else{
                if (!method_exists($t, $kws[1])){
                    $content = $t->help();
                }
                else{
                    $content = $t->$kws[1]($kws[2], $kws[3]);
                }
            }

            break;
            default:
            $content = "try to send 'morse' or 'pi'";
            break;
        }
        self::responseMsg($content);
        $this->writer->endDocument();
        $xml_contents = $this->writer->outputMemory();
        echo $xml_contents;
        return true;
    }

    public function responseMsg($content = "<empty>")
    {
		//get post data, May be due to the different environments
      $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
      if (!empty($postStr)){

         $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
         $fromUsername = $postObj->FromUserName;
         $toUsername = $postObj->ToUserName;
         $time = time();
         $writer = $this->writer;
         $writer->startElement('xml');

         $writer->startElement('ToUserName');
         $writer->writeCData($fromUsername);
         $writer->endElement();

         $writer->startElement('FromUserName');
         $writer->writeCData($toUsername);
         $writer->endElement();

         $writer->writeElement('CreateTime', $time);

         $writer->startElement('MsgType');
         $writer->writeCData('text');
         $writer->endElement();

         $writer->startElement('Content');
         $writer->writeCData($content);
         $writer->endElement();

         $writer->writeElement('FuncFlag', '0');
                // end xml element
         $writer->endElement();
     }else {
       echo "";
       exit;
   }
}


private function getKeywords(){
        //get post data, May be due to the different environments
    $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

    $keyword = "";
        //extract post data
    if (!empty($postStr)){
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $keyword = explode(" ",trim($postObj->Content));
    }
    return $keyword;
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

private $writer = NULL;
}

?>