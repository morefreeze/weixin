<?php
require_once("Model.class.php");

class Morse extends Model{
    public function showAll(){
        $content = file_get_contents('data/morse.txt');
        return $content;
    }

    public function l($ch){
        return self::showLetter($ch);

    }

    public function s($str){
        return self::transStr($str);
    }
    
    public function showLetter(){
        $content = explode("\n", self::showAll());
        $ch[0] = strtoupper($ch[0]);
        foreach ($content as $line){
            $word = explode(" ", $line);
            if ($ch[0] == $word[0]){
                return $line;
            }
        }
        return "Invalid char";
    }

    public function transStr($str){
        $content = explode("\n", self::showAll());
        foreach ($content as $line){
            $word = explode(" ", $line);
            $map[$word[0]] = $word[1];
        }
        $str = strtoupper($str);
        $str = substr($str, 0, 30);
        $len = strlen($str);
        for ($i = 0; $i < $len;++$i){
            $res .= $map[$str[$i]]. " ";
        }
        return $res;
    }

};