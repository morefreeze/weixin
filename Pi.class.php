<?php
require_once("Model.class.php");

class Pi extends Model{

    public function showAll(){
        return self::showDigits();
    }
    // alias as showDigits
    public function d($start = 0, $len = 100){
        return self::showDigits($start, $len);
    }
    // return formatter pi digits
    public function showDigits($start = 0, $len = 100){
        $f = fopen("data/pi.txt", "r");
        if ($len > 100){
            $len = 100;
        }
        if ($len <= 0){
            $len = 10;
        }
        if ($start > 10000){
            $start = 10000;
        }
        if ($start < 0){
            $start = 0;
        }
        $content = "";
        $cur = 0;
        $cur_len = 0;
        while($line = fgets($f)){
            $line = trim($line);
            if (empty($line)){
                continue;
            }
            $words = explode(" ", $line);
            foreach ($words as $w){
                $word_len = 10; // each word length is 10
                $cur += $word_len;
                if ($cur >= $start){
                    $content .= "$w";
                    $cur_len += $word_len;
                }
                if ($cur > $start + $len){
                    break;
                }
            }
            if ($cur > $start + $len){
                break;
            }
        }
        $content = substr($content, ($start-1) % 10 + 1, $len);
        var_dump($content);
        $res = "";
        while(!empty($content)){
            $res .= substr($content,0, 10)."\n";
            $content = substr($content, 10);
        }
        return $res;
    }

}