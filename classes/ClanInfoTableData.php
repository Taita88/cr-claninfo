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
        
        foreach ($member_tags_from_last_cycle as $member_tag_from_last_cycle) {
            $info_table_member_data = new InfoTableMemeberData();
            $info_table_member_data->setTag($member_tag_from_last_cycle);
            
            //TODO: filling missing cycles with empty values is a QoL improvement for generating table via PHP
            //In case we decide to generate table via JS so we will send JSON to FE probably we will no longer need this step
            $member_datas = $this->fillMemberMissingCycles($member_dao->getMemberDataByTag($member_tag_from_last_cycle));
            
            foreach ($member_datas as $member_data) {
                if (null == ($info_table_member_data->getName())) {
                    $info_table_member_data->setName($member_data['name']);
                }
                
                $info_table_member_data->addAction($member_data['donations'], $member_data['clan_chest_crowns'], $member_data['year'], $member_data['week_of_year']);
            }
            
            $index = sizeof($this->members);
            $this->members[$index] = $info_table_member_data;
        }
    }

    private function fillMemberMissingCycles($member_datas)
    {
        $member_datas_prefilled = array();
        foreach ($this->cycles as $cycle) {
            $member_datas_prefilled[] = array(
                "donations" => "",
                "clan_chest_crowns" => "",
                "year" => $cycle->getYear(),
                "week_of_year" => $cycle->getWeekOfYear(),
                "name" => ""
            );
        }       
        
        for ($i = 0; $i < count($member_datas_prefilled); $i++) {
            foreach ($member_datas as $member_data){
                if($member_datas_prefilled[$i]['year'] == $member_data['year'] && $member_datas_prefilled[$i]['week_of_year'] == $member_data['week_of_year']){
                    $member_datas_prefilled[$i]['donations'] = $member_data['donations'];
                    $member_datas_prefilled[$i]['clan_chest_crowns'] = $member_data['clan_chest_crowns'];
                    $member_datas_prefilled[$i]['name'] = $member_data['name'];
                }
            }
        }
        
        return $member_datas_prefilled;
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function getCycles()
    {
        return $this->cycles;
    }

    public function toAssocArray()
    {
        $cycles = array();
        foreach ($this->getCycles() as $cycle_element) {
            $cycle = $cycle_element->toAssocArray();
            $cycles[] = $cycle;
        }
        
        $members = array();
        foreach ($this->getMembers() as $member_element) {
            $member = $member_element->toAssocArray();
            $members[] = $member;
        }
        
        $clan_info_table_data[] = array(
            "cycles" => $cycles,
            "members" => $members
        );
        
        return $clan_info_table_data;
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

    public function toAssocArray()
    {
        $actions_per_cycle = array();
        foreach ($this->action_per_cycle as $action_per_cycle_element) {
            $action_per_cycle = $action_per_cycle_element->toAssocArray();
            $actions_per_cycle[] = $action_per_cycle;
        }
        return array(
            "tag" => $this->tag,
            "name" => $this->name,
            "actions_per_cycle" => $actions_per_cycle
        );
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

    public function getWeekOfYear()
    {
        return $this->week_of_year;
    }

    public function toAssocArray()
    {
        return array(
            "year" => $this->year,
            "week_of_year" => $this->week_of_year,
            "donation" => $this->donation,
            "crown" => $this->crown
        );
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

    public function getWeekOfYear()
    {
        return $this->week_of_year;
    }

    public function toAssocArray()
    {
        return array(
            "year" => $this->year,
            "week_of_year" => $this->week_of_year
        );
    }
}

?>