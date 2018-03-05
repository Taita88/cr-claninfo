<?php

class Cycle{

  private $id;
  private $year;
  private $week_of_year;
  private $members;
    
  public function __construct(){
    $this->setYear((int)date("Y"));
    $this->setWeekOfYear((int)date("W"));
  }
  
  public function getId(){
    return $this->id;
  }
  
  public function setId($id){
    $this->id = (int)$id;
  }
  
  public function getYear(){
    return $this->year;
  }
  
  public function setYear($year){
    $this->year = (int)$year;
  }
  
  public function getWeekOfYear(){
    return $this->week_of_year;
  }
  
  public function setWeekOfYear($week_of_year){
    $this->week_of_year = (int)$week_of_year;
  }
  
  public function getMembers(){
    return $this->members;
  }
  
  public function setMembers($members){
    $this->members = $members;
  }
  
  public function addMember($member){
    $this->members[] = $member;
  }

}

?>