<?php

class ClanInfoTableData
{

    private $members;

    private $cycles;

    public function __construct()
    {
        $this->members = array();
        $this->cycles = array();
        
        $this->fillCycles();
        $this->fillMembers();
    }

    private function fillCycles()
    {
        $cycle_dao = new CycleDao();
        $cycles_from_DB = $cycle_dao->getAllCycle();
        foreach ($cycles_from_DB as $cycle) {
            $cyclePair = new CyclePair($cycle->getYear(), $cycle->getWeekOfYear());
            
            $index = sizeof($this->cycles);
            $this->cycles[$index] = $cyclePair;
        }
    }

    private function fillMembers()
    {
        $member_dao = new MemberDao();
        $member_tags_from_last_cycle = $member_dao->getMemberTagsFromLastCycle();        
        
        foreach ($member_tags_from_last_cycle as $member_tag_from_last_cycle) 
        {
            $info_table_member_data = new InfoTableMemeberData();
            $info_table_member_data->setTag($member_tag_from_last_cycle);
            
            $member_datas = $member_dao->getMemberDataByTag($member_tag_from_last_cycle);
            
            foreach ($member_datas as $member_data) 
            {
                if (null == ($info_table_member_data->getName())) 
                {
                    $info_table_member_data->setName($member_data['name']);
                }
                
                $info_table_member_data->addAction($member_data['donations'], $member_data['clan_chest_crowns'], $member_data['year'], $member_data['week_of_year']);
                              
            }
            
            $index = sizeof($this->members);
            $this->members[$index] = $info_table_member_data;
        }
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function getCycles()
    {
        return $this->cycles;
    }
}

class InfoTableMemeberData
{

    private $tag;

    private $name;

    private $action_per_cycle;

    public function __construct()
    {
        $this->action_per_cycle = array();
    }

    public function getTag()
    {
        return $this->tag;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getActionPerCycle()
    {
        return $this->action_per_cycle;
    }

    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function addAction($donation, $crown, $year, $week_of_year)
    {
        $cycle_with_action = new CycleWithAction($donation, $crown, $year, $week_of_year);
        $index = sizeof($this->action_per_cycle);
        
        $this->action_per_cycle[$index] = $cycle_with_action;
    }
}

class CycleWithAction
{

    private $year;
    
    private $week_of_year;

    private $donation;

    private $crown;

    public function __construct($donation, $crown, $year, $week_of_year)
    {
        $this->donation = $donation;
        $this->crown = $crown;
        $this->year = $year;
        $this->week_of_year = $week_of_year;
    }

    public function getDonation()
    {
        return $this->donation;
    }

    public function getCrown()
    {
        return $this->crown;
    }
    public function getYear()
    {
        return $this->year;
    }

    public function getWeek_of_year()
    {
        return $this->week_of_year;
    }
    
}

class CyclePair
{

    private $year;

    private $week_of_year;

    public function __construct($year, $week_of_year)
    {
        $this->year = $year;
        $this->week_of_year = $week_of_year;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getWeek_of_year()
    {
        return $this->week_of_year;
    }

    public function setYear($year)
    {
        $this->year = $year;
    }

    public function setWeek_of_year($week_of_year)
    {
        $this->week_of_year = $week_of_year;
    }
}

?>