<html>
<head>
    <link rel="stylesheet" type="text/css" href="js/Datatables-1.10.16/datatables.min.css" />
    <script type="text/javascript" src="js/jquery-1.12.4.js"></script>
    <script type="text/javascript" src="js/Datatables-1.10.16/datatables.min.js"></script>    
    <script type="text/javascript" src="js/jquery.dataTables.min.js"></script> 
    
    <style type="text/css">
        div.container {
            width: 40%;  
        }
        
        #donation {
            display: none;
        }              
        
        .btn{
            height: 28px;
            text-align: center;
            background-color: #F8F8F8;
            border-radius: 3px;
            border: 2px solid #E8E8E8;
        }       
    </style>
</head>
<body onLoad="defaultTableShow()">
	
	<script type="text/javascript">			 	
     	$(document).ready(function() {
    	    $('#donation').DataTable({
    	    	"paging":   false,
    	    	"columnDefs": [
    	    	    { "width": "100px",  "targets": 0}
    	    	  ],
    	    	"fixedColumns": true,
    	    	"info": false,
    	    	"searching": false
    	    });
    	} );
    
     </script>

  
<?php
require __DIR__ . '/vendor/autoload.php';

spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.php';
});

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

header('Content-Type: text/html; charset=utf-8');

?>


    	<button id="donation_btn" class="btn">Donation</button>
		<button id="crown_btn" class="btn">Crown</button>
	
    
    <?php
    $clan_info_table_data = new ClanInfoTableData();
    
    //donation table
    echo "<div class=\"container\" >";
    echo "<table id=\"donation\" class=\"display\" cellspacing=\"0\" width=\"100%\">";   
    echo "<thead>";
    echo "<tr>";
    echo "<th></th>";
    
    foreach ($clan_info_table_data->getCycles() as $cycle) {
        echo "<th>" . $cycle->getYear() . "/" . $cycle->getWeekOfYear() . "</th>";
        echo "<th>" . $cycle->getYear() . "/" . $cycle->getWeekOfYear() . "</th>";
    }       
    
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    foreach ($clan_info_table_data->getMembers() as $member) {
        
        echo "<tr>";
        echo "<td>" . $member->getName() . "</td>";
        
        foreach ($member->getActionPerCycle() as $action_per_cycle) {
            echo "<td>" . $action_per_cycle->getDonation() . "</td>";
            echo "<td>" . $action_per_cycle->getCrown() . "</td>";
        }
        
        echo "</tr>";
    }
    
    
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
    
    
    
    ?>

    
    <?php
    // $json = json_encode((array)($clan_info_table_data->toAssocArray()), JSON_PRETTY_PRINT);
    // echo "<br>" . $json;
    ?>
    
   
	<script type="text/javascript">	

	function defaultTableShow(){
		
		
    	document.getElementById('donation').style.display = 'block'; 
    	document.getElementById("donation_btn").style.borderColor = 'blue';
    	document.getElementById("crown_btn").style.borderColor = '';

    	hideColumn("CROWN");    	
	}	

	$('#donation_btn').click(function(e) {
		hideColumn("CROWN");
		
// 		e.preventDefault();
		document.getElementById("donation_btn").style.borderColor = 'blue';
		document.getElementById("crown_btn").style.borderColor = '';			
	});
	
	$('#crown_btn').click(function(e) {
		hideColumn("DONATION");
		
//     	e.preventDefault();
		document.getElementById("crown_btn").style.borderColor = 'blue'; 
		document.getElementById("donation_btn").style.borderColor = '' ; 			
	});	

	function hideColumn(columnType){
		var table = $('#donation').DataTable();
    	var colCount = table.columns().header().length;

    	for(var i = 1; i < colCount; i++){
    		if(columnType === "DONATION"){
        		(i % 2 != 1) ? table.column(i).visible(true) : table.column(i).visible(false);
    		}

    		if(columnType === "CROWN"){
    			(i % 2 == 1) ? table.column(i).visible(true) : table.column(i).visible(false);
    		}
    	}
    	table.columns.adjust().draw( true );

	}		
		 
	</script>

</body>
</html>