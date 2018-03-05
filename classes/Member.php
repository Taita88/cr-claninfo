<?php

class Member {

  private $cycle_id;
  private $tag;
  private $name;
  private $donations = 0;
  private $clan_chest_crowns = 0;
  
  public function getCycleId(){
       return $this->cycle_id;
  }
  
  public function setCycleId($cycle_id){
    $this->cycle_id = (int)$cycle_id;
  }
  
  public function getTag(){
    return $this->tag;
  }
  
  public function setTag($tag){
    $this->tag = $tag;
  }
  
  public function getName(){
    return $this->name;
  }
  
  public function setName($name){
    $this->name = $name;
  }
  
  public function getDonations(){
    return $this->donations;
  }
  
  public function setDonations($donations){
    $this->donations = (int)$donations;
  }
  
  public function getClanChestCrowns(){
    return $this->clan_chest_crowns;
  }
  
  public function setClanChestCrowns($clan_chest_crowns){
    $this->clan_chest_crowns = (int)$clan_chest_crowns;
  }

}

?>