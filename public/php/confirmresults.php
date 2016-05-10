<?php 
	// session_start();
	// include('lib/header.php');

?>
<?php 
// require_once('lib/tc_calendar.php');
$worksheetno=$_GET['q'];
$success=$_GET['p'];
$ee=1;
$worksheet = $Lab->getWorksheetDetails($worksheetno);
extract($worksheet);
$datecreated=date("d-M-Y",strtotime($datecreated));
$datereviewed=date("Y-m-d");
$datereviewedd=date("d-M-Y",strtotime($datereviewed));
$samplesPerRow = 3;
$creator=$Lab->GetUserFullnames($createdby);
$userid=$_SESSION['uid'] ; //id of user who is updatin th record
$reviewedby=$Lab->GetUserFullnames($userid);
$labss=$_SESSION['lab'];
if ($kitexpirydate !="")
{
$kitexpirydate=date("d-M-Y",strtotime($kitexpirydate));
}
if ($datecut != "")
{
$datecut=date("d-M-Y",strtotime($datecut));
}
else
{
$datecut="";
}
if ($daterun !="")
{
$daterun=date("d-M-Y",strtotime($daterun));
$daterun2=date("Y-m-d",strtotime($daterun));
}
else
{
$daterun="";
}

//confirm results and final save
if($_REQUEST['SaveWorksheet'])
{
	$waksheetno= $_POST['waksheetno'];
	$labcode= $_POST['labcode'];
	$outcome= $_POST['testresult'];
	//$testresultID=$Lab->GetIDfromtableandname($outcome[$a],"results"); //th resuls 1-negative 2-positive
	$dateresultsupdated =date('Y-m-d');
	$datesampletested= $_POST['datesampletested'];
	$datereviewed=date("Y-m-d");
	$userid=$_SESSION['uid'] ; //id of user who is updatin th record
	//echo "waksheetno ". $waksheetno . " / "."datereviewed=". $datereviewed . " / user".$userid . " / result ".$outcome . " res id".$testresultID;
	foreach($labcode as $a => $b)
	{
						  
		$paroid=$Lab->getParentID($labcode[$a],$labss);//get parent id
		$testresultID=$Lab->GetIDfromtableandname($outcome[$a],"results"); //th resuls 1-negative 2-positive
		
		if  ($paroid != '0' ) // repeat samples
		{		$parentresult=$Lab->getparentsampleresult($paroid,$labss);  //determine if sample is repeat or not
				$noofretests= GetNoofRetests($paroid,$labss); //get the total no of retests for that samples
			//echo $paroid . " - ". $labcode[$a]. " - " .$testresultID ." - " .$noofretests. "<br>";
					
				
				if  (($noofretests ==1) && ($testresultID ==2) && ($parentresult ==2)) //2nd retest donen result positive..thats th final result
				{
								//update results
		 					$resultsrec = mysqli_query($db_conn, "UPDATE samples
             					 SET  result  	 =  '$testresultID' 
			 					WHERE (accessionno='$labcode[$a]')")or die(mysql_error());
								
									//set it not to repeat again [complete]
					$repeatresults = mysqli_query($db_conn, "UPDATE samples
             			 SET  repeatt  	 =  0 
			 			WHERE (accessionno='$labcode[$a]')")or die(mysql_error());
						
						//pdate pendind tasks
					$repeatresults = mysqli_query($db_conn, "UPDATE pendingtasks
             			 SET  status  	 =  1 
			 			WHERE (sample='$labcode[$a]' AND task=3)")or die(mysql_error());
						
							//check if batch complete and make it ready for dispatch
							//check if batch complete and make it ready for dispatch	   
				$checkcompletebatch = mysqli_query($db_conn, "SELECT batchno
           					 FROM samples
							WHERE accessionno='$labcode[$a]'")or die(mysql_error());
							 while(list($batchno) = mysql_fetch_array($checkcompletebatch))
							{ 	
								$numsamples=$Lab->GetSamplesPerBatch($batchno);
								$rej_samples=$Lab->GetRejectedSamplesPerBatch($batchno);
								$with_result_samples=$Lab->GetSamplesPerBatchwithResults($batchno);
								////no of saMPLES IN BATCH without results
								//count no. of samples per batch that are not received
								$notrec_samples=$Lab->GetNotReceivedSamplesPerBatch($batchno);
								////no of saMPLES IN BATCH without results
								$no_result_samples = (($numsamples - $with_result_samples) - ($rej_samples + $notrec_samples));
				
							
								if($no_result_samples == 0)
								{
								//update batch to be complete
					 				$ifcompleterec = mysqli_query($db_conn, "UPDATE samples
              						SET  BatchComplete=2
						 		WHERE (accessionno='$labcode[$a]')")or die(mysql_error());
							}
							
								
							}		//end 	//end 
						
				}
				else if (($noofretests ==1) && ($testresultID ==3) && ($parentresult ==2)) //2nd reetst doen n result n failed...soo have to do ana final test
				{
					//update results
					//update results for repeats
					
					$resultsrec = mysqli_query($db_conn, "UPDATE samples
             					 SET  result  	 =  '$testresultID' 
			 					WHERE (accessionno='$labcode[$a]')")or die(mysql_error());
					
			 	//update results for repeats
			 		$setsampleforrepeat = mysqli_query($db_conn, "UPDATE samples
             		 SET  repeatt  	 =  1 
			 		WHERE (accessionno='$labcode[$a]')")or die(mysql_error());	
				//update tht sample retest as done	
					$repeatresults = mysqli_query($db_conn, "UPDATE pendingtasks
             			 SET  status  	 =  1 
			 			WHERE (sample='$labcode[$a]' AND task=3)")or die(mysql_error());
				
					//get sample details to be resaved for final repeat test
						$repeatsampledetails = $Lab->getSampledetails($labcode[$a]);
						extract($repeatsampledetails);	
						$facility=$facility;
						//get id of last saved sample
						$lastid=$Lab->GetLastSampleSerialID($labss);
						//generate new accession number
						$labno=GenerateSampleAccessionNumber($lastid,$facility); 
						$sample=$Lab->GetSavedRepeatSamples($batchno,$envelopeno,$patient,$labno,$facility,$receivedstatus,$spots,$datecollected,$datedispatchedfromfacility, $datereceived,$comments,$labcomment,$paroid,$rejectedreason,$pcr);
						//save pendin task
					$task=3;
					$status=0;
					$repeat = SavePendingTasks($task,$batchno,$status,$labno,$labss,$datereceived);
					 
					
				//update status of worksheet
 				$updatedworksheetrecords = mysqli_query($db_conn, "UPDATE worksheets
              SET  Flag = 1, reviewedby='$userid',datereviewed='$datereviewed'
			   			   WHERE (ID = '$waksheetno' )")or die(mysql_error());
		
				
				}
				else if (($noofretests ==1) && ($testresultID ==1) && ($parentresult ==2)) //2nd reetst doen n result negative...soo have to do ana final test
				{
					//update results
					//update results for repeats
					
					
		 					$resultsrec = mysqli_query($db_conn, "UPDATE samples
             					 SET  result  	 =  '$testresultID' 
			 					WHERE (accessionno='$labcode[$a]')")or die(mysql_error());
					
			 	//update results for repeats
			 		$setsampleforrepeat = mysqli_query($db_conn, "UPDATE samples
             		 SET  repeatt  	 =  1 
			 		WHERE (accessionno='$labcode[$a]')")or die(mysql_error());	
					
				//update tht sample retest as done	
					$repeatresults = mysqli_query($db_conn, "UPDATE pendingtasks
             			 SET  status  	 =  1 
			 			WHERE (sample='$labcode[$a]' AND task=3)")or die(mysql_error());
			
			
					//get sample details to be resaved for final repeat test
						$repeatsampledetails = $Lab->getSampledetails($labcode[$a]);
						extract($repeatsampledetails);	
						$facility=$facility;
						//get id of last saved sample
						$lastid=$Lab->GetLastSampleSerialID($labss);
						//generate new accession number
						$labno=GenerateSampleAccessionNumber($lastid,$facility); 
						$sample=$Lab->GetSavedRepeatSamples($batchno,$envelopeno,$patient,$labno,$facility,$receivedstatus,$spots,$datecollected,$datedispatchedfromfacility, $datereceived,$comments,$labcomment,$paroid,$rejectedreason,$pcr);
						//save pendin task
					$task=3;
					$status=0;
					$repeat = SavePendingTasks($task,$batchno,$status,$labno,$labss,$datereceived);
		
				//update status of worksheet
 				$updatedworksheetrecords = mysqli_query($db_conn, "UPDATE worksheets
              SET  Flag = 1, reviewedby='$userid',datereviewed='$datereviewed'
			   			   WHERE (ID = '$waksheetno' )")or die(mysql_error());
		
				
				}
				else if (($noofretests ==1) && ($testresultID ==2) && ($parentresult ==3)) //initialy failed..snow positive anatha retest needed to confirm indeed positive
				{
					//update results
										
		 			$resultsrec = mysqli_query($db_conn, "UPDATE samples
             					 SET  result  	 =  '$testresultID' 
			 					WHERE (accessionno='$labcode[$a]')")or die(mysql_error());
					
			 	//update results for repeats
			 		$setsampleforrepeat = mysqli_query($db_conn, "UPDATE samples
             		 SET  repeatt  	 =  1 
			 		WHERE (accessionno='$labcode[$a]')")or die(mysql_error());	
					
				//update tht sample retest as done	
					$repeatresults = mysqli_query($db_conn, "UPDATE pendingtasks
             			 SET  status  	 =  1 
			 			WHERE (sample='$labcode[$a]' AND task=3)")or die(mysql_error());
			
					//get sample details to be resaved for final repeat test
						$repeatsampledetails = $Lab->getSampledetails($labcode[$a]);
						extract($repeatsampledetails);	
						$facility=$facility;
						//get id of last saved sample
						$lastid=$Lab->GetLastSampleSerialID($labss);
						//generate new accession number
						$labno=GenerateSampleAccessionNumber($lastid,$facility); 
						$sample=$Lab->GetSavedRepeatSamples($batchno,$envelopeno,$patient,$labno,$facility,$receivedstatus,$spots,$datecollected,$datedispatchedfromfacility, $datereceived,$comments,$labcomment,$paroid,$rejectedreason,$pcr);
						//save pendin task
					$task=3;
					$status=0;
					$repeat = SavePendingTasks($task,$batchno,$status,$labno,$labss,$datereceived);
		
				//update status of worksheet
 				$updatedworksheetrecords = mysqli_query($db_conn, "UPDATE worksheets
    				          SET  Flag = 1, reviewedby='$userid',datereviewed='$datereviewed'
				   			   WHERE (ID = '$waksheetno' )")or die(mysql_error());
				}
				else if  (($noofretests ==1) && ($testresultID ==1) && ($parentresult ==3)) //initally failed and after retsete turned negative, tis complete
				{
							
								//update results
		 					$resultsrec = mysqli_query($db_conn, "UPDATE samples
             					 SET  result  	 =  '$testresultID' , repeatt  	 =  0
			 					WHERE (accessionno='$labcode[$a]')")or die(mysql_error());
								
									
						//pdate pendind tasks
					$repeatresults = mysqli_query($db_conn, "UPDATE pendingtasks
             			 SET  status  	 =  1 
			 			WHERE (sample='$labcode[$a]' AND task=3)")or die(mysql_error());
						
							//check if batch complete and make it ready for dispatch
							//check if batch complete and make it ready for dispatch	   
				$checkcompletebatch = mysqli_query($db_conn, "SELECT batchno
           					 FROM samples
							WHERE accessionno='$labcode[$a]'")or die(mysql_error());
							 while(list($batchno) = mysql_fetch_array($checkcompletebatch))
							{ 	
								$numsamples=$Lab->GetSamplesPerBatch($batchno);
								$rej_samples=$Lab->GetRejectedSamplesPerBatch($batchno);
								$with_result_samples=$Lab->GetSamplesPerBatchwithResults($batchno);
								//count no. of samples per batch that are not received
								$notrec_samples=$Lab->GetNotReceivedSamplesPerBatch($batchno);
								////no of saMPLES IN BATCH without results
								$no_result_samples = (($numsamples - $with_result_samples) - ($rej_samples + $notrec_samples));
				
								if($no_result_samples == 0)
								{
								//update batch to be complete
					 				$ifcompleterec = mysqli_query($db_conn, "UPDATE samples
              						SET  BatchComplete=2
						 		WHERE (accessionno='$labcode[$a]')")or die(mysql_error());
								}
							
								
							}		//end 	//end 
						
				}
				else if (($noofretests ==1) && ($testresultID ==3) && ($parentresult ==3)) //initialy failed..now failed anatha retest needed to confirm indeed faILED
				{
					$resultsrec = mysqli_query($db_conn, "UPDATE samples
             					 SET  result  	 =  '$testresultID' 
			 					WHERE (accessionno='$labcode[$a]')")or die(mysql_error());
					
			 	//update results for repeats
			 		$setsampleforrepeat = mysqli_query($db_conn, "UPDATE samples
             		 SET  repeatt  	 =  1 
			 		WHERE (accessionno='$labcode[$a]')")or die(mysql_error());	
					
					//update tht sample retest as done	
					$repeatresults = mysqli_query($db_conn, "UPDATE pendingtasks
             			 SET  status  	 =  1 
			 			WHERE (sample='$labcode[$a]' AND task=3)")or die(mysql_error());
			
					//get sample details to be resaved for final repeat test
						$repeatsampledetails = $Lab->getSampledetails($labcode[$a]);
						extract($repeatsampledetails);	
						$facility=$facility;
						//get id of last saved sample
						$lastid=$Lab->GetLastSampleSerialID($labss);
						//generate new accession number
						$labno=GenerateSampleAccessionNumber($lastid,$facility); 
						$sample=$Lab->GetSavedRepeatSamples($batchno,$envelopeno,$patient,$labno,$facility,$receivedstatus,$spots,$datecollected,$datedispatchedfromfacility, $datereceived,$comments,$labcomment,$paroid,$rejectedreason,$pcr);
						//save pendin task
					$task=3;
					$status=0;
					$repeat = SavePendingTasks($task,$batchno,$status,$labno,$labss,$datereceived);
		
				//update status of worksheet
 				$updatedworksheetrecords = mysqli_query($db_conn, "UPDATE worksheets
              SET  Flag = 1, reviewedby='$userid',datereviewed='$datereviewed'
			   			   WHERE (ID = '$waksheetno' )")or die(mysql_error());
				
				}
				else //test done = 2 n irrespective of the results, thats the final outcome
				{
								//update results
		 					$resultsrec = mysqli_query($db_conn, "UPDATE samples
             					 SET  result  	 =  '$testresultID',repeatt  	 =  0  
			 					WHERE (accessionno='$labcode[$a]')")or die(mysql_error());
						
									
						
						//pdate pendind tasks
					$repeatresults = mysqli_query($db_conn, "UPDATE pendingtasks
             			 SET  status  	 =  1 
			 			WHERE (sample='$labcode[$a]' AND task=3)")or die(mysql_error());
						//check if batch complete and make it ready for dispatch
								//check if batch complete and make it ready for dispatch	   
				$checkcompletebatch = mysqli_query($db_conn, "SELECT batchno
           					 FROM samples
							WHERE accessionno='$labcode[$a]'")or die(mysql_error());
							 while(list($batchno) = mysql_fetch_array($checkcompletebatch))
							{ 	
								$numsamples=$Lab->GetSamplesPerBatch($batchno);
								$rej_samples=$Lab->GetRejectedSamplesPerBatch($batchno);
								$with_result_samples=$Lab->GetSamplesPerBatchwithResults($batchno);
								//count no. of samples per batch that are not received
								$notrec_samples=$Lab->GetNotReceivedSamplesPerBatch($batchno);
								////no of saMPLES IN BATCH without results
								$no_result_samples = (($numsamples - $with_result_samples) - ($rej_samples + $notrec_samples));
				
								if($no_result_samples == 0)
								{
								//update batch to be complete
					 				$ifcompleterec = mysqli_query($db_conn, "UPDATE samples
              						SET  BatchComplete=2
						 		WHERE (accessionno='$labcode[$a]')")or die(mysql_error());
								}
							
								
							}		
						
				}
		}
		else if ($paroid == '0')//ordinary samples
		{   $testresultID=$Lab->GetIDfromtableandname($outcome[$a],"results"); //th resuls 1-negative 2-positive
				//echo $paroid . " - ". $labcode[$a]. " - " .$testresultID ." - " . "<br>";
				//update results
		 		$resultsrec = mysqli_query($db_conn, "UPDATE samples
             	 SET  result  	 =  '$testresultID' 
			 	WHERE (accessionno='$labcode[$a]')")or die(mysql_error());
				
					//update status of worksheet
 				$updatedworksheetrecords = mysqli_query($db_conn, "UPDATE worksheets
              SET  Flag = 1, reviewedby='$userid',datereviewed='$datereviewed'
			   			   WHERE (ID ='$waksheetno' )")or die(mysql_error());
				if 	($testresultID==2) 
				{
					//update results for repeats
			 		$repeatresults = mysqli_query($db_conn, "UPDATE samples
             		 SET  repeatt  	 =  1 
			 		WHERE (accessionno='$labcode[$a]')")or die(mysql_error());
					//get sample details to be resaved for final repeat test
						$repeatsampledetails = $Lab->getSampledetails($labcode[$a]);
						extract($repeatsampledetails);	
						$facility=$facility;
						//get id of last saved sample
						$lastid=$Lab->GetLastSampleSerialID($labss);
						//generate new accession number
						$labno=GenerateSampleAccessionNumber($lastid,$facility); 
						$sample=$Lab->GetSavedRepeatSamples($batchno,$envelopeno,$patient,$labno,$facility,$receivedstatus,$spots,$datecollected,$datedispatchedfromfacility, $datereceived,$comments,$labcomment,$labcode[$a],$rejectedreason,$pcr);
						//save pendin task
					$task=3;
					$status=0;
					$repeat = SavePendingTasks($task,$batchno,$status,$labno,$labss,$datereceived);
		
				} 
				elseif ($testresultID==3)
				{
				
				//update results for repeats
			 		$repeatresults = mysqli_query($db_conn, "UPDATE samples
             		 SET  repeatt  	 =  1 
			 		WHERE (accessionno='$labcode[$a]')")or die(mysql_error());
					//get sample details to be resaved for final repeat test
						$repeatsampledetails = $Lab->getSampledetails($labcode[$a]);
						extract($repeatsampledetails);	
						$facility=$facility;
						//get id of last saved sample
						$lastid=$Lab->GetLastSampleSerialID($labss);
						//generate new accession number
						$labno=GenerateSampleAccessionNumber($lastid,$facility); 
						$sample=$Lab->GetSavedRepeatSamples($batchno,$envelopeno,$patient,$labno,$facility,$receivedstatus,$spots,$datecollected,$datedispatchedfromfacility, $datereceived,$comments,$labcomment,$labcode[$a],$rejectedreason,$pcr);
						//save pendin task
					$task=3;
					$status=0;
					$repeat = SavePendingTasks($task,$batchno,$status,$labno,$labss,$datereceived);
				}
				else
				{
			
						//check if batch complete and make it ready for dispatch	   
				$checkcompletebatch = mysqli_query($db_conn, "SELECT batchno
           					 FROM samples
							WHERE accessionno='$labcode[$a]'")or die(mysql_error());
							 while(list($batchno) = mysql_fetch_array($checkcompletebatch))
							{ 	
								$numsamples=$Lab->GetSamplesPerBatch($batchno);
								$rej_samples=$Lab->GetRejectedSamplesPerBatch($batchno);
								$with_result_samples=$Lab->GetSamplesPerBatchwithResults($batchno);
								//count no. of samples per batch that are not received
								$notrec_samples=$Lab->GetNotReceivedSamplesPerBatch($batchno);
								////no of saMPLES IN BATCH without results
								$no_result_samples = (($numsamples - $with_result_samples) - ($rej_samples + $notrec_samples));
				
								if($no_result_samples == 0)
								{
								//update batch to be complete
					 				$ifcompleterec = mysqli_query($db_conn, "UPDATE samples
              						SET  BatchComplete=2
						 		WHERE (accessionno='$labcode[$a]')")or die(mysql_error());
								}
							}		//end 
				}
		}//end if for ordubary sample*/
	}//end if for repeat value&& $updatedworksheetrecords

		
	if ($resultsrec  )
	{
		$tasktime= date("h:i:s a");
		$todaysdate=date("Y-m-d");
		
		//save activity of user
		$task = 8; //review worksheet
		$activity = SaveUserActivity($userid,$task,$tasktime,$waksheetno,$todaysdate);
		
		$st="Test Results for Worksheet No. ".$waksheetno ."  have been successfully approved. "."<br>"." They may now be dispatched "."<br>"." All the positives, failed or indeterminates have been set aside for retest. ";
 			echo '<script type="text/javascript">' ;
			echo "window.location.href='dispatch.php?z=$st'";
			echo '</script>';

	}
	else
	{
		$error='<center>'."Failed to Update test results, try again ".'</center>';

	}
}//end if request
else if($_REQUEST['FlagWorksheet'])
{
	
	$waksheetno= $_POST['waksheetno'];
	$labcode= $_POST['labcode'];
	$outcome= $_POST['testresult'];
	$flagreason= $_POST['flagreason'];
	
	$userid=$_SESSION['uid'] ; //id of user who is updatin th record
	$blank="";
	foreach($labcode as $a => $b)
	{
	
	$testresultID=$Lab->GetIDfromtableandname($outcome[$a],"results"); //th resuls 1-negative 2-positive
	 $import = mysqli_query($db_conn, "UPDATE samples
              SET result = 0 , initialresult='$testresultID' , datemodified = '$blank'
			  			WHERE (accessionno='$labcode[$a]')")or die(mysql_error());

						

	}
	//mark waksheet as awaitin second review by lab manager
					  
						  $updateworksheetrec = mysqli_query($db_conn, "UPDATE worksheets
             SET  Flag=2 , reviewedby='$blank',datereviewed='$blank' ,comments='$flagreason'
			   			   WHERE (ID = '$waksheetno' )")or die(mysql_error());
						//flag worksheet in pedning tasks   
		$activity = "INSERT INTO 		
			pendingtasks(task,worksheet,status,lab)VALUES(8,'$worksheetno',0,'$labss')";
			$pendingactivity = @mysql_query($activity) or die(mysql_error());

		
if ( $import &&  $updateworksheetrec && $pendingactivity   )
	{
		$tasktime= date("h:i:s a");
		$todaysdate=date("Y-m-d");
		
		//save activity of user
		$task = 6; //review worksheet
		$activity = SaveUserActivity($userid,$task,$tasktime,$waksheetno,$todaysdate);
		
		$flagsuccess=" Worksheet No. ".$waksheetno ."  has been successfully flagged . "."<br>"." It will undergo a review by the lab manager before deciding on action to take  ";
 			echo '<script type="text/javascript">' ;
			echo "window.location.href='worksheetlist.php?wtype=2&flagsuccess=$flagsuccess'";
			echo '</script>';

	}
	else
	{
		$error='<center>'."Failed to Flag Worksheet, try again ".'</center>';

	}

}
?> 

<style type="text/css">
select {
width: 250;}
</style>	<script language="javascript" src="lib/js/calendar.js"></script>
<link type="text/css" href="lib/css/calendar.css" rel="stylesheet" />	
		<SCRIPT language=JavaScript>
function reload(form)
{
var val=form.cat.options[form.cat.options.selectedIndex].value;
self.location='addsample.php?catt=' + val ;
}
</script>
<script language="JavaScript">
function submitPressed() {
document.worksheetform.SaveWorksheet.disabled = true;
//stuff goes here
document.worksheetform.submit();
}
</script> 
<script language="javascript" type="text/javascript">
// Roshan's Ajax dropdown code with php
// This notice must stay intact for legal use
// Copyright reserved to Roshan Bhattarai - nepaliboy007@yahoo.com
// If you have any problem contact me at http://roshanbh.com.np
function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}
		 	
		return xmlhttp;
    }

	
	function getFlaggingReason(approve) {		
		
		var strURL="getreasonforflagging.php?rejid="+approve;
		var req = $Lab->getXMLHTTP();
		
		if (req) {
			
			req.onreadystatechange = function() {
				if (req.readyState == 4) {
					// only if "OK"
					if (req.status == 200) {						
						document.getElementById('statediv2').innerHTML=req.responseText;						
					} else {
						alert("There was a problem while using XMLHTTP:\n" + req.statusText);
					}
				}				
			}			
			req.open("GET", strURL, true);
			req.send(null);
		}		
	}



</script>
		<div  class="section">
		<div class="section-title">WORKSHEET NO <?php echo $worksheetno; ?> RESULTS DETAILS </div>
		<div class="xtop">
		<?php if ($success !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="success"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$success.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php } ?>
<?php if ($error !="")
		{
		?> 
		<table   >
  <tr>
    <td style="width:auto" ><div class="error"><?php 
		
echo  '<strong>'.' <font color="#666600">'.$error.'</strong>'.' </font>';

?></div></th>
  </tr>
</table>
<?php } ?>

	<table><tr><td>
		 <p><font color="#FF0000">If the uploaded results have any discrepancies , Please tick the check boxes at the bottom and a comment so as to flag the worksheet for further review with lab manager.</font>
		</td></tr>
		</table>
		<form  method="post" action="" name="worksheetform"  onSubmit="return confirm('Are you sure you want to approve the below test results as final results?');" >
		<table border="0" class="data-table">
			
		
		<tr class="even" style='background: #dddddd;'>
		<td  class="comment style1 style4">
		Worksheet / Template No		</td>
		<td >
		  <span class="style5"><?php echo $worksheetno; ?><input type="hidden" name="waksheetno" value="<?php echo $worksheetno; ?>" /></span></td>
</tr>
			<tr class="even" style='background:#dddddd;'>
		<td class="comment style1 style4">
		Worklist Run Batch No		</td>
		<td class="comment">
		  <span class="style5"><?php echo $runbatchno; ?></span></td>
		<td  class="comment style1 style4">
	  	  Spex Kit No		</td>
		<td  colspan="">
		<?PHP ECHO $Spekkitno; ?></td>
		
		<td class="comment style1 style4">
		Date Run	    </td>
		<td>
	    <?php echo $daterun; ?>
		  <input type="hidden" name="datesampletested" value="<?php echo $daterun2; ?>" />		</td>
	
		
		</tr>
		
<tr class="even" style='background:#dddddd;'>
		<td class="comment style1 style4">
		Created By	    </td>
		<td>
	    <?php  echo $creator; ?>		</td>
		<td >HIQCAP Kit Lot No</th>
		  <td><?PHP ECHO $HIQCAPNo; ?></td>	
			<td  class="comment style1 style4">
	  	  Date Reviewed		</td>
		<td  colspan="">
		<?PHP ECHO $datereviewedd; ?></td>
				
  </tr>
<tr class="even" style='background:#dddddd;'>
		<td class="comment style1 style4">
		Date Created		</td>
		<td class="comment" ><?php  echo  $datecreated ; //get current date ?></td>
	<td class="comment style1 style4">KIT EXP</td>
		<td><?PHP ECHO $kitexpirydate; ?></td>
		
		<td class="comment style1 style4">Reviewed by </td>
		<td><?PHP ECHO $reviewedby; ?></td>
  </tr>	
		
		
		
		<tr class="even">
			<td colspan="7" >&nbsp;</td>
		</tr>
<tr  >
	 <?php
	 
	 // this is the list you had, but in PHP terms (in an array)
$dropDownList = array (
                   "Negative", "Positive","Failed"
                   
                );


// put data into an array
//$dataArray = mysql_fetch_array($result);

	 $qury = "SELECT ID,accessionno,patient,batchno,parentid,datereceived
         FROM samples
		WHERE worksheet='$worksheetno' ORDER BY parentid DESC,ID ASC
			";			
			$result = mysql_query($qury) or die(mysql_error());
?>
	 
<tr>
<?php
	 $count = 1;
$colcount=1;
for($i = 1; $i <= 2; $i++) 
{
		if ($count==1)
		{
		$pc="<div align='right'><table><tr><td><small>1</small></td></tr></table></div><div align='center'>Negative Control<br><strong>NC</strong></div>";
		}
		elseif ($count==2)
		{
		$pc="<div align='right'><table></div><tr><td><small>2</small></td></tr></table><div align='center'>Positive Control<br><strong>PC</strong></div>";
		}
		
				$RE= $colcount%6;
?>
             <td height="50" > <?php echo $pc; ?> </td><?php	 
	

       $count ++;         
	$colcount ++;
			
}
$scount = 2;
 while(list($ID,$accessionno,$patient,$batchno,$parentid,$datereceived) = mysql_fetch_array($result))
	{
	$scount = $scount + 1;  
	$paroid=$Lab->getParentID($accessionno,$labss);//get parent id
	
if ($paroid =='0')
{
$paroid="";
}
else
{
$paroid=" - ". $paroid;
}
		
		$RE= $colcount%6;
		
		 
		// grab data from database
		$result2 = mysqli_query($db_conn, "SELECT results.name as 'resultnames' FROM samples,results  WHERE   samples.accessionno = '$accessionno' AND 		samples.result=results.ID");
		// put data into an array
		$dataArray = mysql_fetch_array($result2);
     	 $resultnames=$dataArray['resultnames'];
   
  
  
    
	
?>
 <td width='178px'>
  <div align="right">
 <table><tr><td><small>
 <?php echo $scount;?></small>
 </td></tr></table></div>
	 Patient ID:  <?php echo $patient; ?> <br>  Batch no:  <?php echo $batchno; ?> <br>  Accession no: <input name='labcode[]' type='text' id='labcode' value='<?php echo $accessionno; ?>' size='15' readonly='' style = 'background:#F6F6F6;'><?php echo $paroid;?>
<strong>
 Result: <input name='testresult[]' type='text' id='testresult[]'value='<?php echo  $resultnames ; ?>'  style = 'background:#F6F6F6;'  size='15' /></strong>
    </td>
<?php  $colcount ++;
		 
		
             if ($RE==0)
			 { 
			?>
	
       
   
     </tr>

<?php
		 }//end if modulus is 0
	 }//end while?>

<tr class="even">
			<td colspan="7" >&nbsp;</td>
		</tr>
	<tr>
	<td>Flag Worksheet?</td>
	<td colspan="6"> <div> 
			<input name="approve" type="radio" value="Y" onclick="getFlaggingReason(this.value)" />
             Y&nbsp;
              <input name="approve" type="radio" value="N" onclick="getFlaggingReason(this.value)"   />
              N 
		  <span id="spotInfo"></span></div></td>
	
</tr>

<tr>
            <td colspan="7">  <div id="statediv2"></div> </td>
           
          </tr>
				  




		 
</table>
	</form>
   


		    
		
		
	
		
 <?php include('../includes/footer.php');?>