<html>
  
<?php 
  spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.php';
  });
?>

  <body>
    <table border=1>
    
      <?php  
        $date1 = new DateTime("2018-03-05");
        echo "rok = " . (int)$date1->format("Y") . " tyzden v roku= " . (int)$date1->format("W");
        echo "<br>";
        
        $date = new DateTime("2018-03-05");
        $date->add(DateInterval::createFromDateString('yesterday'));
        
        echo "rok = " . (int)$date->format("Y") . " tyzden v roku= " . (int)$date->format("W"); 
        
      ?>
    </table>
    
    
  </body>
</html>