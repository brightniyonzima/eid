<?php

// session_start();
// $labss=1; // $_SESSION['lab'];
// include('/php/lib/header.php');

// define('IN_CB',true);

// define('VERSION', '2.1.0');

// if(version_compare(phpversion(),'5.0.0','>=')!==true)
// 	exit('Sorry, but you have to run this script with PHP5... You currently have the version <b>'.phpversion().'</b>.');

// if(!function_exists('imagecreate'))
// 	exit('Sorry, make sure you have the GD extension installed before running this script.');

// include('/php/lib/html.config.php');

// include('/php/lib/function.php');
include_once('/php/lib/connection.config.php');
// include_once('/php/lib/tc_calendar.php');

			
			
// //$i=mysql_num_rows($result);
// $userid=11;// $_SESSION['uid'] ;	
// $initials='Y.T';// strtoupper($_SESSION['initials']);	
// $leo=date('Ymd');	
// $worksheetrunno=$leo.$initials;
// $creator=GetUserFullnames($userid);
// 		//Array to store validation errors
// 	$errmsg_arr = array();
	
// 	//Validation error flag
// 	$errflag = false;
// 	$currentday =date('Y-m-d') +1 ;	

// if($_REQUEST['SaveWorksheet'])
// {	
// 	$worksheetno= $_POST['worksheetno'];
// 	$worksheetrunno= $_POST['worksheetrunno'];
// 	$lotno= $_POST['lotno'];
// 	$hiqcap= $_POST['hiqcap'];
// 	$rackno= $_POST['rackno'];
// 	$spekkitno= $_POST['spekkitno'];
// 	$labcode= $_POST['labcode'];
// 	$sample= $_POST['sample'];
// 	$datecreated =date('d-m-Y');
// 	$kitexp = $_POST['kitexp'];
// 	$kitexp =date("Y-m-d",strtotime($kitexp)); //convert to yy-mm-dd
// 	$datecut = $_POST['datecut'];
// 	$datecut =date("Y-m-d",strtotime($datecut)); //convert to yy-mm-dd


// 	//save worksheet details
// 	$worksheetdetailsrec ="INSERT INTO worksheets
// 								(ID, runbatchno, datecreated, HIQCAPNO, spekkitno, createdby,
// 									Lotno, Rackno, kitexpirydate, datecut, lab)
// 							VALUES
// 								('$worksheetno','$worksheetrunno','$datecreated','$hiqcap',
// 									'$spekkitno','$userid','$lotno','$rackno','$kitexp','$datecut','$labss')";
				
// 	$worksheetdetail = @mysql_query($worksheetdetailsrec) or die(mysql_error());

// 	foreach($labcode as $t => $b)
// 	{
	
	
// 	// update sample record
// 	$samplerec = mysql_query(	"UPDATE samples
// 				  					SET Inworksheet = 1,  worksheet='$worksheetno'
// 										WHERE (accessionno = '$labcode[$t]')")
// 	 				or die(mysql_error());
	
// 	// update pending tasks
// 	$repeatresults = mysql_query("UPDATE pendingtasks SET status = 1 
// 									WHERE (sample='$labcode[$t]' AND task=3)")
// 					or die(mysql_error());
// 	}

// 	$activity = "INSERT INTO pendingtasks(task,worksheet,status,lab)
// 					VALUES (9,'$worksheetno',0,'$labss')";
	
// 	$pendingactivity = @mysql_query($activity) or die(mysql_error());
				
// 	if ($worksheetdetail && $samplerec && $pendingactivity) //check if all records entered
// 	{
// 		$tasktime= date("h:i:s a");
// 		$todaysdate=date("Y-m-d");
		
// 		//save activity of user
// 		$task = 5; //create worksheet
// 		$activity = SaveUserActivity($userid,$task,$tasktime,$worksheetno,$todaysdate);

// 		$disable="Sample: ";
// 		echo '<script type="text/javascript">' ;
// 		echo "window.open('downloadworksheet.php?ID=$worksheetno','_blank')";
// 		echo '</script>';
// 	}
// 	else
// 	{
// 			$st="Worksheet Save Failed, try again ";
// 	}
// }

?>
<style type="text/css">
select {
width: 250;}
</style>	
<script type="text/javascript" src="lib/js/validation2.js"></script>
<link rel="stylesheet" href="lib/css/validation.css" type="text/css" media="screen" />

<script language="javascript" src="lib/js/calendar.js"></script>
<link type="text/css" href="lib/css/calendar.css" rel="stylesheet" />	
		<link href="lib/css/jquery-ui.css" rel="stylesheet" type="text/css"/>
 
  <script src="lib/js/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="lib/css/demos.css">
  <script>
  $(document).ready(function() {
   // $("#dob").datepicker();
	$( "#kitexp" ).datepicker({ minDate: "-5D", maxDate: "+5Y" });
	});


//  });
  </script>
  <script>
  $(document).ready(function() {
   // $("#datecollected").datepicker();
$( "#datecut" ).datepicker({ minDate: "-7D", maxDate: "+0D" });

  });
  </script>

<script language="JavaScript">
function submitPressed() {
document.worksheetform.SaveWorksheet.disabled = true;
//stuff goes here
document.worksheetform.submit();
}
</script> 
		<style type="text/css">
<!--
.style1 {font-weight: bold}
-->
        </style>
		<div  class="section">
		<div class="section-title">CREATE WORKLIST / WORKSHEET </div>
		<div class="xtop">
			<table><tr><td>
		 <p><font color="#FF0000">Please enter the batch serial number after the initials in the Worklist Run Batch No Section</font>
		</td></tr>
		</table>
<?php

	echo "here we go";

	if(gethostbyname($REMOTE_ADDR)=="127.0.0.1") {
		$objConnect = mysql_connect("localhost", "root", ""); 
	} else {
		$objConnect = mysql_connect("localhost", "root", "");// production passwd = cphl##45 
	}
	
	$objDB=mysql_select_db("zl4") or die("Could not select database: zl4");
	echo "db selected";


			if ($st !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="error"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$st.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php } ?><?php //select 22 samples for testing
$qury = "select ID, accessionno, pcr, patient, batchno, parentid, datereceived, 
			IF(parentid > '0' OR parentid IS NULL, 0, 1) AS isnull  
		from samples  
		WHERE Inworksheet=0 AND ((receivedstatus !=2) and (receivedstatus !=4))   AND ((result IS NULL ) OR (result =0 )) AND status =1 AND Flag=1
			ORDER BY isnull ASC, datereceived ASC, parentid ASC, ID ASC
			LIMIT 0,22";			
			$result = mysql_query($qury) or die(mysql_error());
			$no=mysql_num_rows($result); //no of samples
		
		echo "no = " . $no;

		if ($no <= 22)
		{ 
			$worksheetno=55567263; // GetNewWorksheetNo(); 
$waksheetdate=date('Y-m-d');
$worksheerrunbatchno=$waksheetdate;

echo "xxx-yyy";
		?>
		<form  method="post" action="" id="customForm">
		<table  border="0" class="data-table">
		<tr class="even">
		<th  >
		Worksheet / Template No		</th>
		<td >
		  <span class="style5"><?php echo $worksheetno; ?>
		  	<input name="worksheetno" type="hidden" id="worksheetno" value="<?php echo $worksheetno; ?>"   readonly="" style = 'background:#F6F6F6;'  />
		  </span>
		</td>
</tr>
			<tr class="even">
		<th >
		Worklist Run Batch No		</th>
		<td class="comment">
		  <span class="style5"><input name="worksheetrunno" type="text" id="worksheetrunno" value="<?php echo $worksheetrunno; ?>"    style="width:124px" class="text"/></span></td>
		<th class="comment style1 style4">
		Date Created		</th>
		<td class="comment" ><?php $currentdate=date('d-M-Y'); echo  $currentdate ; //get current date ?></td>
		<th >HIQCAP Kit Lot No</th>
		  <td><div>
		    <input name="hiqcap" type="text" id="hiqcap" value=""  style="width:129px" class="text" />
		    <br />
		  <span id="hiqcapInfo"></span></div></td>	
		</tr>
		
<tr class="even">
		<th>
		Created By	    </th>
		<td>
	    <?php  echo $creator ?>		</td><th>
	  	  Spex Kit No		</th>
		<td  colspan="">
		 <div> <input name="spekkitno" type="text" id="spekkitno" value=""  style="width:124px"  class="text"  /> <span id="spekkitnoInfo"></span></div></td>
		<th>KIT EXP</th>
		<td><div>
			<p> <input id="kitexp" type="text" name="kitexp" class="text"  style="width:129px" ><span id="kitexpInfo"></span></div></p>


<div type="text" id="kitexp">
</div></td>
  </tr><tr >
		
		</tr>
		
			<tr class="even">
		<td colspan="7" >&nbsp;		</td>
		</tr>
<!--<tr><td><div align="center"><strong>1</strong></div></td>
<td><div align="center"><strong>2</strong></div></td>
<td><div align="center"><strong>3</strong></div></td>
<td><div align="center"><strong>4</strong></div></td>
<td><div align="center"><strong>5</strong></div></td>
<td><div align="center"><strong>6</strong></div></td>
</tr> -->
<tr> 
<?php

	echo "<p>para....</p>";

	 $count = 1;
$colcount=1;
for($i = 1; $i <= 2; $i++) 
{
		if ($count==1)
		{
		$pc="<div align='right'>
	 	<table><tr><td><small>1</small></td></tr></table></div><div align='center'>Negative Control<br><strong>NC</strong></div>";
		}
		elseif ($count==2)
		{
		$pc="<div align='right'><table></div><tr><td><small>2</small></td></tr></table><div align='center'>Positive Control<br><strong>PC</strong></div>";
		}
		
				$RE= $colcount%6;
?>
             <td height="50" > 

<?php echo $pc; ?> </td><?php	 


       $count ++;         
	$colcount ++;
			
}
$scount = 2;
 while(list($ID,$accessionno,$pcr,$patient,$batchno,$parentid,$datereceived) = mysql_fetch_array($result))
	{  

			echo "abcdef";	


	$scount = $scount + 1;
	$paroid=7; // cx: was getParentID($accessionno,$labss);//get parent id

	echo "asdfghjkl;";	
	
if ($paroid =="0")
{
$paroid="";
$previouswsheet="";
$labnodesc="Lab no:";
$rerundetails="";

	echo "asdfghjkl;";	

}
else
{
$previouswsheet=1412; // getWorksheetnoforParentID($paroid,$labss);
$paroid=" <b>".$paroid."</b> " ;
$labnodesc="New Lab no:";
$rerundetails= '<br> Parent Lab No:  '. $paroid .'<br> Previous Run Worksheet #: '.   " <b>". $previouswsheet ."</b> ";
}
		
		$RE= $colcount%6;
		
		 ?>
		
	

  
     
     <td width='178px'>
	 <div align="right">
	 <table><tr><td><small>
	 <?php echo $scount;?></small>
	 </td></tr></table></div>
	 Patient ID:  <input name='patient[]' type='text' id='patient' value='<?php echo $patient; ?>' size='14' readonly='' style = 'background:#F6F6F6;'> <br>  Batch no:  <input name='batchno[]' type='text' id='batchno' value='<?php echo $batchno; ?>' size='6' readonly='' style = 'background:#F6F6F6;'> <br>PCR: <?php echo $pcr; ?><br> <?php echo $labnodesc; ?>  <input name='labcode[]' type='text' id='labcode' value='<?php echo $accessionno; ?>' size='10' readonly='' style = 'background:#F6F6F6;'> <?php echo $rerundetails; ?>
	<?php echo" <img src='../html/image.php?code=code128&o=2&dpi=50&t=50&r=1&rot=0&text=$accessionno&f1=Arial.ttf&f2=0&a1=&a2=B&a3='/>";?>   </td>
<?php  $colcount ++;
		 
		
             if ($RE==0)
			 { 
			?>
	
       
   
     </tr>
<?php
	echo "abcdef";	


		 }//end if modulus is 0
	 }//end while

?>



<tr >
            <th  colspan="7" ><center>
			
			    <input type="submit" name="SaveWorksheet" value="Save & Print Worksheet" class="button"  />
				
            </center></th	>
          </tr>
</table>
	
   
	</tr> 

		    
		</form>
		<?php }
		else
		{?>
		<table   >
  <tr>
    <td style="width:auto" ><div class="notice"><?php 
		
echo  '<strong>'.' <font color="#666600">'. 'No Enough Samples to run a test['. $no. ']'.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
		
	<?php	}
		?>
		
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>