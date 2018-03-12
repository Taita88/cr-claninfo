<html>

  
<?php
require __DIR__ . '/vendor/autoload.php';

spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.php';
});

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

header('Content-Type: text/html; charset=utf-8');

?>

  <body>
	<table border=1>
    
    <?php
    
    // $log = new Logger('name');
    // $log->pushHandler(new StreamHandler('cr.log', Logger::DEBUG));
    // $log->debug("Logging");
    
    
    // var_dump($cycle_dao->getAllCycleWithMembers());
    
    $clan_info_table_data = new ClanInfoTableData();
    
    echo "<tr>";
    echo "<td></td>";
    
    foreach ($clan_info_table_data->getCycles() as $cycle) 
    {
        echo "<td>" . $cycle->getYear() . "/" . $cycle->getWeekOfYear() . "</td>";
    }
    
    echo "</tr>";
        
    $i = 1;
    foreach ($clan_info_table_data->getMembers() as $member)
    {
        
        echo "<tr>";
        echo "<td>" . $i++ . " " . $member->getName() . "</td>";
            
        foreach ($member->getActionPerCycle() as $action_per_cycle)
        {
            echo "<td>" . $action_per_cycle->getDonation() . "</td>";
        }
        
        echo "</tr>";
    }
    
    
    
    // var_dump(new ClanInfoTableData());
    
    ?>
    </table>
    
    <?php 
//     $json = json_encode((array)($clan_info_table_data->toAssocArray()), JSON_PRETTY_PRINT);
//         echo "<br>" . $json;
    ?>


</body>
</html>