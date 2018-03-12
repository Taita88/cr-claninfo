<?php

class MemberDao
{

    private $connection;

    public function __construct()
    {
        $database = new Database();
        $this->connection = $database->getConnection();
    }

    public function getAllMemberByCycleId($cycle_id)
    {
        $query = "SELECT * FROM member WHERE member.cycle_id = " . $cycle_id;
        
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        
        $members = array();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            
            $member = new Member();
            $member->setId($id);
            $member->setCycleId($cycle_id);
            $member->setTag($tag);
            $member->setName($name);
            $member->setDonations($donations);
            $member->setClanChestCrowns($clan_chest_crowns);
            
            $members[] = $member;
        }
        
        return $members;
    }

    public function createMember($member)
    {
        $query = "INSERT INTO member (cycle_id, tag, name, donations, clan_chest_crowns) " . "VALUES (" . $member->getCycleId() . ", " . "'" . $member->getTag() . "'," . "'" . $member->getName() . "'," . "" . $member->getDonations() . "," . "" . $member->getClanChestCrowns() . ")";

        $this->connection->exec($query);
        return $this->connection->lastInsertId();
    }

    public function updateMember($member)
    {
        $query = "UPDATE member SET " . "donations=" . $member->getDonations() . " , " . "clan_chest_crowns=" . $member->getClanChestCrowns() . " , " . "name='" . $member->getName() . "' " . "WHERE id='" . $member->getId() . "' ";

        try {
            $this->connection->query($query);
            // echo "Record updated successfully";
        } catch (PDOException $e) {
            // echo "Error: ".$e;
        }
    }

    public function getMemberByCycleIdAndTag($cycle_id, $tag)
    {
        $query = "SELECT * FROM member WHERE member.cycle_id = " . $cycle_id . " " . "AND member.tag = '" . $tag . "'";
        
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        
        if ($stmt->rowCount() == 1) {
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            extract($row);
            
            $member = new Member();
            $member->setId($id);
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

    public function getMemberTagsFromLastCycle()
    {
        $query = "SELECT member.tag FROM member " .
                 "WHERE member.cycle_id = ( " .
        					"SELECT cycle.id FROM cycle " .
        					"JOIN member ON cycle.id = member.cycle_id " .
        					"ORDER BY  cycle.year DESC, cycle.week_of_year DESC " .
        					"limit 1 " .
                            " )";
        
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        
        $tags = array();
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
        {
            extract($row);
            $tags[] = $tag;
        }

        return $tags;
    }
    
    public function getMemberDataByTag($tag){
        $query = "SELECT cycle.year, cycle.week_of_year, member.donations, member.clan_chest_crowns, member.name FROM member " .
                 "JOIN cycle ON cycle.id = member.cycle_id " .
                 "WHERE member.tag = '" . $tag . "' " .
                 "ORDER BY cycle.year ASC, cycle.week_of_year ASC";
        
        $stmt = $this->connection->prepare($query);
        $stmt->execute();        
        
        $members = array();        
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            
            $member["year"] = $year;
            $member["week_of_year"] = $week_of_year;
            $member["donations"] = $donations;
            $member["clan_chest_crowns"] = $clan_chest_crowns;
            $member["name"] = $name;
            
            $members[] = $member;
        } 
        
        return $members;
    }
}

?>