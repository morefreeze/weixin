<?php

abstract class Model{
    abstract public function showAll();

    public function help(){
        $content = "function list:\n";
        $cls = get_class($this);
        $methods = get_class_methods($cls);
        $cls_name = strtolower($cls);
        foreach($methods as $m){
            $content .= "$cls_name $m\n";
        }
        return $content;
    }
}