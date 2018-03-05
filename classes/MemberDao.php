<?php

  class MemberDao{
  
    private $connection;
    
    public function __construct(){
      $database = new Database();
      $this->connection = $database->getConnection();      
    }
    
    public function getAllMemberByCycleId($cycle_id){
      $query = "SELECT * FROM member WHERE member.cycle_id = " . $cycle_id; 
      
      $stmt = $this->connection->prepare($query);
      $stmt->execute();
      
      $members = array();
      
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        
        
        $member = new Member();
        $member->setCycleId($cycle_id);
        $member->setTag($tag);
        $member->setName($name); 
        $member->setDonations($donations);
        $member->setClanChestCrowns($clan_chest_crowns);
        
        $members[] = $member;
      }  
      
      return $members;
    }
    
    public function createMember($member){
      $query = "INSERT INTO member (cycle_id, tag, name, donations, clan_chest_crowns) " .
               "VALUES (" . $member->getCycleId() . ", " .
                       "'" . $member->getTag() . "'," .
                       "'" . $member->getName() . "'," .
                       "" . $member->getDonations() . "," .
                       "" . $member->getClanChestCrowns() . ")";
                       
      $this->connection->exec($query);                  
    } 
    
    public function updateMember($member){
      $query = "UPDATE member SET " . 
               "donations=" . $member->getDonations() . " , " . 
               "clan_chest_crowns=" . $member->getClanChestCrowns() ." , " .
               "name='" . $member->getName() . "' " .
               "WHERE tag='" . $member->getTag() . "' ";        
     
      try{
        $this->connection->query($query);
       // echo "Record updated successfully";
      } catch(PDOException  $e ){
       // echo "Error: ".$e;
      }         
    } 
    
    public function getMemberByCycleIdAndTag($cycle_id, $tag){
      $query = "SELECT * FROM member WHERE member.cycle_id = " . $cycle_id . " " . 
               "AND member.tag = '" . $tag . "'";
            
      $stmt = $this->connection->prepare($query);
      $stmt->execute();   
      
      if($stmt->rowCount() == 1){
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        extract($row);
        
        $member = new Member();
        $member->setCycleId($cycle_id);
        $member->setTag($tag);
        $member->setName($name);
        $member->setDonations($donations);
        $member->setClanChestCrowns($clan_chest_crowns);
        
        return $member;
      } else {
        return null;
      }         
    }   
  
  }

?>