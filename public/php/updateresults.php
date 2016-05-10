<?php
session_start(); 
include('lib/header.php');
require_once('lib/tc_calendar.php');
$worksheetno=$_GET['ID'];
$worksheet = getWorksheetDetails($worksheetno);
extract($worksheet);			
$datecreated=date("d-M-Y",strtotime($datecreated));
$creator=GetUserFullnames($createdby);
	$userid=$_SESSION['uid'] ; //id of user who is updatin th record
?>

<style type="text/css">
select {
width: 250;}
</style>	<script language="javascript" src="/js/calendar.js"></script>

<link type="text/css" href="/css/calendar.css" rel="stylesheet" />	
		<SCRIPT language=JavaScript>
function reload(form)
{
	var val=form.cat.options[form.cat.options.selectedIndex].value;
	self.location='addsample.php?catt=' + val ;
}
</script>
<div  class="section">
		<div class="section-title">UPDATE TEST RESULTS  FOR WORKSHEET NO <?php echo $worksheetno; ?></div>
		<div class="xtop">
		

			<?php
			
if(isset($_POST['submit']))
{    $file1  = $_FILES['filename']['name'];
	 if  ($file1 =="" )
	{
		$error='<center>'."Please Select a Results CSV".'</center>';
	
		?>
		<table class='data-table'  >
  <tr class='even'>
    <td style="width:auto" ><div class="error"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$error.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php 

	print "<form action='' method='post' enctype='multipart/form-data'>";

	echo "<table border='0' class='data-table'>	
<tr class='even'>
		<th colspan='1'>
		<strong>Worksheet No</strong>		</th>
		<td colspan='2'><input name='work' type='text' id='work' value='$worksheetno'   readonly='' style = 'background:#FCFCFC;'  />	
		<input type='hidden' name='results' value='Save Results' />	</td>
		</tr>
		<tr class='even'>
		<td colspan='1'>
		Worklist Run Batch No		</td>
		<td colspan='2'><input name='runbatchno' type='text' id='runbatchno' value='$runbatchno'  style = 'background:#FCFCFC;' readonly=''  />	
		</td>
		</tr>	
<tr class='even'>
		<td colspan='1'>
		HIQCAP Kit No		</td>
		<td colspan='2'><input name='HIQCAP_KitNo' type='text' id='HIQCAP_KitNo'value='$HIQCAPNo'  style = 'background:#FCFCFC;' readonly=''  /></td>
		</tr>
		<tr class='even'>
		<td colspan='1'>
		  	Spek Kit No		</td>
		<td colspan='2'><input name='Spek_Kit_No' type='text' id='Spek_Kit_No' value= '$Spekkitno'  style = 'background:#FCFCFC;' readonly=''  /></td>
		</tr>
		<tr class='even'>
		<td colspan='1'>
		Date Created		</td>
		<td colspan='2'><input name='wdcreated' type='text' id='wdcreated' value='$datecreated'  style = 'background:#FCFCFC;' readonly=''  /></td>
		</tr>	
			<tr class='even'>
		<td colspan='1'>
		Created by		</td>
		<td colspan='2'><input name='createdby' type='text' id='wdcreated' value='$creator'  style = 'background:#FCFCFC;' readonly=''  /></td>
		</tr>
		<tr class='even'>
		<td colspan='1'>
		Locate file name to import:		</td>
     		<td colspan='2'><input type=file name=filename></td>
		</tr>	
<tr class='odd'>
		<td colspan='3'>
		<input type='submit' name='submit' value='submit' class='button'></td>
     		
		</tr>	
		</table>";

     

      print "</form>";

} 
	
else if ($file1 !="" )
{
  			  $work=$_POST['work'];
  				$imagename = $_FILES['filename']['name'];
			  $source = $_FILES['filename']['tmp_name'];
              $target = "ResultsCSVs/".$imagename;
              move_uploaded_file($source, $target);
			  
			//  echo  "waksheet" . $work;
 
              $imagepath = $imagename;
			  
			  $new_string = ereg_replace("[^0-9]", "", $imagepath); //extract the uploaded file name

		if ($new_string != $work) //if selected file matches actual worksheet to be updated
		{

		$error='<center>'."Please Select The correct CSV file titled " .$work.".CSV" .'</center>';
		?>
		
		
			<table class='data-table'  >
  <tr class='even'>
    <td style="width:auto" ><div class="error"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$error.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php 

      print "<form action='' method='post' enctype='multipart/form-data'>";

echo "<table border='0' class='data-table'>	
<tr class='even'>
		<td colspan='1'>
		Worksheet No		</td>
		<td colspan='2'><input name='work' type='text' id='work' value='$worksheetno'  style = 'background:#FCFCFC;' readonly=''  />	
		<input type='hidden' name='results' value='Save Results' />	</td>
		</tr>
	<tr class='even'>
		<td colspan='1'>
		Worklist Run Batch No		</td>
		<td colspan='2'><input name='runbatchno' type='text' id='runbatchno' value='$runbatchno'  style = 'background:#FCFCFC;' readonly=''  />	
		</td>
		</tr>	
<tr class='even'>
		<td colspan='1'>
		HIQCAP Kit No		</td>
		<td colspan='2'><input name='HIQCAP_KitNo' type='text' id='HIQCAP_KitNo'value='$HIQCAPNo'  style = 'background:#FCFCFC;' readonly=''  /></td>
		</tr>
		<tr class='even'>
		<td colspan='1'>
		  	Spek Kit No		</td>
		<td colspan='2'><input name='Spek_Kit_No' type='text' id='Spek_Kit_No' value= '$Spekkitno'  style = 'background:#FCFCFC;' readonly=''  /></td>
		</tr>
		<tr class='even'>
		<td colspan='1'>
		Date Created		</td>
		<td colspan='2'><input name='wdcreated' type='text' id='wdcreated' value='$datecreated'  style = 'background:#FCFCFC;' readonly=''  /></td>
		</tr>	
			<tr class='even'>
		<td colspan='1'>
		Created	by	</td>
		<td colspan='2'><input name='createdby' type='text' id='wdcreated' value='$creator'  style = 'background:#FCFCFC;' readonly=''  /></td>
		</tr>
		<tr class='even'>
		<td colspan='1'>
		Locate file name to import:		</td>
     		<td colspan='2'><input type=file name=filename></td>
		</tr>	
<tr class='odd'>
		<td colspan='3'>
		<input type='submit' name='submit' value='submit' class='button'></td>
     		
		</tr>	
		</table>";

     

      print "</form>";
		}
		else //work sheet match
		{

            $file = "ResultsCSVs/".$imagepath; //This is the original file 
			//echo  $file;
 			 $handle = fopen("$file", "r");

   			 while (($data = fgetcsv($handle, 1000, ",")) !== FALSE){

   				$currentdate=date('Y-m-d'); //get current date

				$datereviewed=date("Y-m-d");
       
				if (($data[8] == "Target Not Detected") || ($data[8] == "Not Detected DBS"))
				{ //negative
	 
						$d=1;
				}
				else if  (($data[8] == "Detected DBS") || ($data[8] == 1) ||  ($data[8] == ">1") || ($data[8] == "1.00E+00") || ($data[8] == ">1.00E+00")      ) 
				{//positive
						$d=2;
				} 
				else
				{//failed/ indeterinate
						$d=3;
				}
				$dateoftest=date("Y-m-d",strtotime($data[3]));
				
	 		 $import = mysql_query("UPDATE samples
              SET result = '$d' ,datemodified = '$currentdate', datetested='$dateoftest'
			  			  WHERE (accessionno = '$data[4]')"); //   WHERE (ID = '$data[0]')"); 


    	 } //end while

    		 fclose($handle);
 			//update status of worksheet
 			$updateworksheetrec = mysql_query("UPDATE worksheets
             SET Updatedby='$userid' ,daterun='$dateoftest' , Flag=1 , reviewedby='$userid',datereviewed='$datereviewed'
			   			   WHERE (ID = '$work' )")or die(mysql_error());
						   
			$repeatresults = mysql_query("UPDATE pendingtasks
             			 SET  status  	 =  1 
			 			WHERE (worksheet='$work' AND task=9)")or die(mysql_error());
				
				
				unlink($file);				   
						   
    			// print "Import done";
								
				if($handle && $updateworksheetrec )
				{
					$tasktime= date("h:i:s a");
					$todaysdate=date("Y-m-d");
					
					//save activity of user
					$task = 7; //update results
					$activity = SaveUserActivity($userid,$task,$tasktime,$work,$todaysdate);

					$st="Import done, Results Updated successfully, Please Confirm and approve the updated results below";
					echo '<script type="text/javascript">' ;
					echo "window.location.href='confirmresults.php?p=$st&q=$work'";
					echo '</script>';
				}							//window.location.href='worksheet_list.php?p=$st';
			} //end if file name matches worksheet
 } // end if filename not null
 
 }// end if submitted
   else //not submiited
   {





      print "<form action='' method='post' enctype='multipart/form-data'>";

echo "<table border='0' class='data-table'>	
<tr class='even'>
		<td colspan='1'>
		Worksheet No		</td>
		<td colspan='2'><input name='work' type='text' id='work' value='$worksheetno'  style = 'background:#FCFCFC;' readonly=''  />	
		<input type='hidden' name='results' value='Save Results' />	</td>
		</tr>
		
		<tr class='even'>
		<td colspan='1'>
		Worklist Run Batch No		</td>
		<td colspan='2'><input name='runbatchno' type='text' id='runbatchno' value='$runbatchno'  style = 'background:#FCFCFC;' readonly=''  />	
		</td>
		</tr>	
<tr class='even'>
		<td colspan='1'>
		HIQCAP Kit No		</td>
		<td colspan='2'><input name='HIQCAP_KitNo' type='text' id='HIQCAP_KitNo'value='$HIQCAPNo'  style = 'background:#FCFCFC;' readonly=''  /></td>
		</tr>
		<tr class='even'>
		<td colspan='1'>
		  	Spek Kit No		</td>
		<td colspan='2'><input name='Spek_Kit_No' type='text' id='Spek_Kit_No' value= '$Spekkitno'  style = 'background:#FCFCFC;' readonly=''  /></td>
		</tr>
		<tr class='even'>
		<td colspan='1'>
		Date Created		</td>
		<td colspan='2'><input name='wdcreated' type='text' id='wdcreated' value='$datecreated'  style = 'background:#FCFCFC;' readonly=''  /></td>
		</tr>	
			<tr class='even'>
		<td colspan='1'>
		 Created by		</td>
		<td colspan='2'><input name='createdby' type='text' id='wdcreated' value='$creator'  style = 'background:#FCFCFC;' readonly=''  /></td>
		</tr>
		<tr class='even'>
		<td colspan='1'>
		Locate file name to import:		</td>
     		<td colspan='2'><input type=file name=filename></td>
		</tr>	
<tr class='odd'>
		<td colspan='3'>
		<input type='submit' name='submit' value='submit' class='button'></td>
     		
		</tr>	
		</table>";

     

      print "</form>";

   }
 
   ?>	
       
	
	
		</div>
		</div>
		
 <?php include('../includes/footer.php');?>