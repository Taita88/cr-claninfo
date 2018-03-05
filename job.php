<?php

  spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.php';
  });
  
  if( !isset($_GET['order']) || $_GET['order'] == null){
    echo 'Access forbidden!';
  } else {
    $order = $_GET['order'];
    
    switch($order){
    
      case "crown_job":
        echo "running crown job";
        runJobForCrowns();
        break;
        
      case "donation_job":
      echo "running donation job";
        runJobForDonations();
        break;
    }  
 
  }
  
  function runJobForDonations(){
    $cycleDb = getCycleFromDBOrCreate((int)date("Y"), (int)date("W"));
    
    $cr_api_communicator = new CrApiCommunicator();
    $members = $cr_api_communicator->getClanMembers();
   
    $member_dao = new MemberDao();
    
    foreach($members as $member){
    
      $memberDb = getMemberFromDBOrCreate($cycleDb->getId(), $member->tag);    
      $memberDb->setName($member->name); 
      $memberDb->setDonations($member->donations);
      //$memberDb->setClanChestCrowns($member->clanChestCrowns);
      
      $member_dao->updateMember($memberDb);
    }
                                                
    //var_dump($cycle);  
  
  }
  
  function runJobForCrowns(){
    //posunieme sa o jeden den spat v case aby sme na zaklade roka a tyzdna v roku dokazali naparovat
    //job na druhy (donation) job
    $date = new DateTime();
    $date->add(DateInterval::createFromDateString('yesterday'));
    
    $cycleDb = getCycleFromDBOrCreate((int)$date->format("Y"), (int)$date->format("W"));
    
    $cr_api_communicator = new CrApiCommunicator();
    $members = $cr_api_communicator->getClanMembers();
    
    $member_dao = new MemberDao();
    
    foreach($members as $member){
    
      $memberDb = getMemberFromDBOrCreate($cycleDb->getId(), $member->tag);
      echo "memberDb:" . $memberDb->getClanChestCrowns();    
      $memberDb->setName($member->name); 
      //$memberDb->setDonations($member->donations);
      $memberDb->setClanChestCrowns($member->clanChestCrowns);
      
      $member_dao->updateMember($memberDb);
    }
  }
  
  function getCycleFromDBOrCreate($year, $week_of_year){
    $cycle_dao = new CycleDao();
    $cycle = $cycle_dao->getCycleByYearWeek($year, $week_of_year);
    
    if($cycle == null){
      $cycle = new Cycle();
      $cycle->setYear($year);
      $cycle->setWeekOfYear($week_of_year); 
           
      $cycle->setId($cycle_dao->createCycle($cycle));
    }
 
    return $cycle;
  }
  
  function getMemberFromDBOrCreate($cycle_id, $tag){
    $member_dao = new MemberDao();
    $member = $member_dao->getMemberByCycleIdAndTag($cycle_id, $tag);
    
    echo ($member == null) ? "member is null" : "member is not null";
    if($member == null){
      $member = new Member();
      $member->setCycleId($cycle_id);
      $member->setTag($tag);
      
      $member_dao->createMember($member);  
    }
    
    return $member;
  }
  
  

?>