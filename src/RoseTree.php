<?php

namespace Collection;

use Collection\RoseIterable;

class RoseTree{

    public $element = null;
    public $children=[];

    public function addItem( RoseIterable $element ){
        if($this->element == null){
            $this->element = $element;
            $this->children = [];
        }else if($this->element->getId() == $element->getParentId()){
            $tmpElement = new RoseTree();
            $tmpElement->element = $element;
            $this->children[] = $tmpElement;
        }else if( $element->getId() == $this->element->getParentId() ){
            $tmpElement = new RoseTree();
            $tmpElement->element = $this->element;
            $tmpElement->children = $this->children;
            $this->element = $element;
            $this->children[] = $tmpElement;
        }else{
            foreach( $this->children as $child ){
                $child->addItem($element);
            }
        }
    }

    public function getNodeById( $id = 0 ){
        if($this->element->getId() == $id){
            return $this;
        }else{
            $niddle = null;
            foreach( $this->children as $child ){
                if($child->element->getId() == $id){
                   $niddle = new RoseTree();
                   $niddle->element = $child->element;
                   $niddle->children = $child->children;
                   break;
                }else{
                    $node = $child->getNodeById( $id );
                    if($niddle != null){
                       $niddle = new RoseTree();
                       $niddle->element = $node->element;
                       $niddle = $node->children;
                       break;
                    }
                }
            }
            return $niddle;
        }
    }

    public function reduce( $field = 'id', $accumulatorFieldName = 'id_sum'){

        foreach( $this->children as $child ){
            $child->reduce($field,$accumulatorFieldName);
        }

        $accumulator = 0;
        foreach( $this->children as $child ){
            $accumulator += $child->$accumulatorFieldName + $child->element->$field;
        }
        $this->$accumulatorFieldName = $accumulator;

    }

    public function runRecursion($function,&$opts=0){
        foreach ( $this->children as $key => $child ) {
            $child->runRecursion($function,$opts);
        }
        $function($this->children,$this->element,$this,$opts);
    }
}

?>