<?php
 
use Collection\RoseTree;
use Collection\RoseIterable;

class RoseTest extends PHPUnit_Framework_TestCase {
    
  private $rose;
  
  public function testRoseHasChildren(){
    $rose = new RoseTree();
    $rose->addItem( new X(1,0) );
    $rose->addItem( new X(2,1) );
    $rose->addItem( new X(3,1) );
    $this->assertTrue( sizeof($rose->children) == 2 );
  }
  
  public function testElementCaptureByIdOnRose(){
    $rose = new RoseTree();
    $rose->addItem( new X(1,0) );
    $rose->addItem( new X(2,1) );
    $rose->addItem( new X(3,1) );
    $element = $rose->getNodeById(2);
    $this->assertNotNull($element);
  }
  
  public function testNullElementIdOnRose(){
    $rose = new RoseTree();
    $rose->addItem( new X(1,0) );
    $rose->addItem( new X(2,1) );
    $rose->addItem( new X(3,1) );
    $element = $rose->getNodeById(4);
    $this->assertNull($element);
  }
  
  public function testRoseChildrenCount(){
    $rose = new RoseTree();
    $rose->addItem( new X(1,0) );
    $rose->addItem( new X(2,1) );
    $rose->addItem( new X(3,1) );
    $rose->reduce('id','id_sum');
    $this->assertEquals($rose->id_sum,5 );
  }
  
  public function testRoseRecursionFunction(){
    $rose = new RoseTree();
    $rose->addItem( new X(1,0) );
    $rose->addItem( new X(2,1) );
    $rose->addItem( new X(3,1) );
    $totalNodes = 0;
    $rose->runRecursion(function($children,$element,$tree,&$totalNodes){
        $totalNodes++;
    },$totalNodes);

    $this->assertEquals( $totalNodes,3 );
  }
}


class X implements RoseIterable{
    
    public $parent_id = 0, $id = 0;
    
    public function __construct($id=0,$parent_id=0){
        $this->parent_id = $parent_id;
        $this->id = $id;
    }
    public function getParentId(){
        return $this->parent_id;
    }
    public function getId(){
        return $this->id;
    }
}