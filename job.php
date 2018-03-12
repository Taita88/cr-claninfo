<?php
require __DIR__ . '/vendor/autoload.php';

spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.php';
});

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

//TODO: put configured logger to another class and call it from there
$log = new Logger('name');
$handler = new StreamHandler('cr.log', Logger::INFO);
$formatter = new LineFormatter(null, null, false, true);
$handler->setFormatter($formatter);
$log->pushHandler($handler);

//TODO: better security  maybe HTTPS? Tokens in header?
if (! isset($_GET['order']) || $_GET['order'] == null) {
    echo 'Access forbidden!';
} else {
    $order = $_GET['order'];
    
    switch ($order) {
        
        case "crown_job":
       //     echo "running crown job";
            runJobForCrowns();
            break;
        
        case "donation_job":
       //     echo "running donation job";
            runJobForDonations();
            break;
    }
}

function runJobForDonations()
{
    $date = new DateTime();
    $date->add(DateInterval::createFromDateString('yesterday'));
    
    global $log;
    $log->info("Job for donations has started with year number " . $date->format('Y') . " with week number " . $date->format("W"));
    
    $cycleDb = getCycleFromDBOrCreate((int) $date->format("Y"), (int) $date->format("W"));
    
    $cr_api_communicator = new CrApiCommunicator();
    $members = $cr_api_communicator->getClanMembers();   
    
    $member_dao = new MemberDao();
    
    foreach ($members as $member) {
        $memberDb = getMemberFromDBOrCreate($cycleDb->getId(), $member->tag);
        $memberDb->setName($member->name);
        $memberDb->setDonations($member->donations);
        
        $member_dao->updateMember($memberDb);
    }
    
    $log->info("Job for donations ended");
}

function runJobForCrowns()
{
    $date = new DateTime();
    $date->add(DateInterval::createFromDateString('yesterday'));
    
    global $log;
    $log->info("Job for crowns has started with year number " . $date->format('Y') . " with week number " . $date->format("W"));
    
    $cycleDb = getCycleFromDBOrCreate((int) $date->format("Y"), (int) $date->format("W"));
    
    $cr_api_communicator = new CrApiCommunicator();
    $members = $cr_api_communicator->getClanMembers();
    
    $member_dao = new MemberDao();
    
    foreach ($members as $member) {
        $memberDb = getMemberFromDBOrCreate($cycleDb->getId(), $member->tag);
        $memberDb->setName($member->name);
        $memberDb->setClanChestCrowns($member->clanChestCrowns);
        
        $member_dao->updateMember($memberDb);
    }
    
    $log->info("Job for crowns ended");
}

//TODO: move to cycleDao
function getCycleFromDBOrCreate($year, $week_of_year)
{
    $cycle_dao = new CycleDao();
    $cycle = $cycle_dao->getCycleByYearWeek($year, $week_of_year);
    
    if ($cycle == null) {
        $cycle = new Cycle();
        $cycle->setYear($year);
        $cycle->setWeekOfYear($week_of_year);
        
        $cycle->setId($cycle_dao->createCycle($cycle));
    }
    
    return $cycle;
}

//TODO: move to memberDao
function getMemberFromDBOrCreate($cycle_id, $tag)
{
    $member_dao = new MemberDao();
    $member = $member_dao->getMemberByCycleIdAndTag($cycle_id, $tag);
    
    if ($member == null) {
        $member = new Member();
        $member->setCycleId($cycle_id);
        $member->setTag($tag);
        
        $member->setId($member_dao->createMember($member));
    }
    
    return $member;
}

?>