<?php

  class CycleDao{
  
    private $connection;
    
    public function __construct(){
      $database = new Database();
      $this->connection = $database->getConnection();      
    }
    
    public function getAllCycle(){
      $query = "SELECT * FROM cycle ORDER BY cycle.year ASC, cycle.week_of_year ASC";
      
      $stmt = $this->connection->prepare($query);
      $stmt->execute();
      
      $cycles = array();
  
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        
        $cycle = new Cycle();
        $cycle->setId($id);
        $cycle->setYear($year);
        $cycle->setWeekOfYear($week_of_year);        
        
        $cycles[] = $cycle;
      }
      
      return $cycles;
    }
    
    public function getAllCycleWithMembers(){
      
      $cycles = $this->getAllCycle();
      $cycles_clone = array_merge(array(), $cycles);
      
      $member_dao = new MemberDao();
      
      foreach($cycles_clone as $cycle){
        $members = $member_dao->getAllMemberByCycleId($cycle->getId());
        
        for($x = 0; $x < count($cycles); $x++){
          if($cycles[$x]->getId() == $cycle->getId()){
            $cycles[$x]->setMembers($members);  
          }
        }
      }
  
      return $cycles;
    }
    
    public function getCycleWithMembersByYearWeek($year, $week_of_year){
    
      $query = "SELECT * FROM cycle WHERE cycle.year=" . $year . " AND cycle.week_of_year=" . $week_of_year; 
      $stmt = $this->connection->prepare($query);
      $stmt->execute();
      
      if($stmt->rowCount() == 1){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        extract($row);
  
        $member_dao = new MemberDao();
        
        $cycle = new Cycle();
        $cycle->setId($id);
        $cycle->setYear($year);
        $cycle->setWeekOfYear($week_of_year);
        $cycle->setMembers($member_dao->getAllMemberByCycleId($id));
        
        return $cycle;
      } else {
        return null;
      }
    
    }
    
    public function getCycleById($id){
      $query = "SELECT * FROM cycle WHERE cycle.id = " . $id;
      $stmt = $this->connection->prepare($query);
      $stmt->execute();
      
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
      $member_dao = new MemberDao();
        
      $cycle = new Cycle();
      $cycle->setId($id);
      $cycle->setYear($year);
      $cycle->setWeekOfYear($week_of_year);
      $cycle->setMembers($member_dao->getAllMemberByCycleId($id));
      
      return $cycle;
    }
    
    public function getCycleByYearWeek($year, $week_of_year){
      $query = "SELECT * FROM cycle WHERE cycle.year=" . $year . " AND cycle.week_of_year=" . $week_of_year;
      
      $stmt = $this->connection->prepare($query);
      $stmt->execute();      
      
      if($stmt->rowCount() == 1){
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        extract($row);
        
        $cycle = new Cycle();
        $cycle->setId($id);
        $cycle->setYear($year);
        $cycle->setWeekOfYear($week_of_year);
        
        return $cycle;
      } else { 
        return null;
      }
    }
    
    public function createCycle($cycle){
      $query = "INSERT INTO cycle (year, week_of_year) " . 
               "VALUES (" . $cycle->getYear() . " ," .
                       "" . $cycle->getWeekOfYear() . ")";
                       
      $this->connection->exec($query);
      return $this->connection->lastInsertId();                 
    } 
  
  }

?>