<html>
  
<?php
require __DIR__ . '/vendor/autoload.php';

spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.php';
});

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

?>

  <body>
	<table border=1>
    
    <?php
    
//     $log = new Logger('name');
//     $log->pushHandler(new StreamHandler('cr.log', Logger::DEBUG));
//     $log->debug("Logging");
    
//     $date1 = new DateTime();
//     echo "rok = " . (int) $date1->format("Y") . " tyzden v roku= " . (int) $date1->format("W");
//     echo "<br>";
    
//     $date = new DateTime("2019-01-01");
//     $date->add(DateInterval::createFromDateString('yesterday'));
    
//     echo "rok = " . (int) $date->format("Y") . " tyzden v roku= " . (int) $date->format("W");
    
//     $cycle_dao = new CycleDao();
//     $member_dao = new MemberDao();
//     var_dump($cycle_dao->getAllCycleWithMembers());

//     $clan_info_table_data = new

//     var_dump(new ClanInfoTableData());
    
    ?>
    </table>


</body>
</html>