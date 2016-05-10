<?php
session_start();
$labss=$_SESSION['lab'];
require_once('../connection/config.php');
require_once('classes/tc_calendar.php');
include("../includes/functions.php");
$wno=$_GET['ID'];

$worksheet =$Lab->getWorksheetDetails($wno);
extract($worksheet);			
$datecreated=date("d-M-Y",strtotime($datecreated));
if ($kitexpirydate !="")
{
$kitexpirydate=date("d-M-Y",strtotime($kitexpirydate));
}
else if (NULL($kitexpirydate))
{
$kitexpirydate = "";
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
}
else
{
$daterun="";
}->
$creator=$LabGetUserFullnames($createdby);
?>
<html>
<link rel="stylesheet" type="text/css" href="../style44.css" media="screen" />
<style type="text/css">
<!--
.style1 {font-family: "Courier New", Courier, monospace}
.style4 {font-size: 12}
.style5 {font-family: "Courier New", Courier, monospace; font-size: 12; }
-->
</style>
<body onLoad="JavaScript:window.print();">
<div align="center">
<table>
<tr>
<td><strong>HIV	LAB EARLY INFANT DIAGNOSIS<br/>

COBAS AMPLIPREP / TAQMAN TEMPLATE </strong>
</td>
</tr>
</table>
</div>
<table border="0" class="data-table" cellspacing="4">
		<tr class="even" style='background: #dddddd;'>
		<td  class="comment style1 style4">
		Worksheet / Template No		</td>
		<td >
		  <span class="style5"><?php echo $ID; ?></span></td>
</tr>
			<tr class="even" style='background:#dddddd;'>
		<td class="comment style1 style4">
		Worklist Run Batch No		</td>
		<td class="comment">
		  <span class="style5"><?php echo $runbatchno; ?></span></td>
		<td class="comment style1 style4">
		Date Created		</td>
		<td class="comment" ><?php  echo  $datecreated ; //get current date ?></td>
		<td >HIQCAP Kit Lot No</th>
		  <td><?PHP ECHO $HIQCAPNo; ?></td>	
		</tr>
		
<tr class="even" style='background:#dddddd;'>
		<td class="comment style1 style4">
		Created By	    </td>
		<td>
	    <?php  echo $creator; ?>		</td><td  class="comment style1 style4">
	  	  Spex Kit No		</td>
		<td  colspan="">
		<?PHP ECHO $Spekkitno; ?></td>
		<td class="comment style1 style4">KIT EXP</td>
		<td><?PHP ECHO $kitexpirydate; ?></td>
  </tr>
			
<tr style='background:#dddddd;'>
	 <?php
	 $qury = "SELECT ID,accessionno,pcr,patient,batchno,parentid,datereceived
         FROM samples
		WHERE worksheet='$wno' ORDER BY parentid DESC,ID ASC";			
			$result = mysql_query($qury) or die(mysql_error());
?>
	<tr class="even"><td colspan="6">&nbsp;</td></tr>
<tr style='background:#dddddd;'>
<?php
	 $count = 1;
$colcount=1;
for($i = 1; $i <= 2; $i++) 
{
		if ($count==1)
		{
		$pc="<div align='right'>
	 	<table><tr><td class='comment style1 style4'><small>1</small></td></tr></table></div><div align='center'>Negative Control<br><strong>NC</strong></div>";
		}
		elseif ($count==2)
		{
		$pc="<div align='right'><table></div><tr><td class='comment style1 style4'><small>2</small></td></tr></table><div align='center'>Positive Control<br><strong>PC</strong></div>";
		}
		
				$RE= $colcount%6;
?>
             <td height="50" bgcolor="#dddddd" class="comment style1 style4"> <?php echo $pc; ?> </td><?php	 
	

       $count ++;         
	$colcount ++;
			
}
$scount = 2;
 while(list($ID,$accessionno,$pcr,$patient,$batchno,$parentid,$datereceived) = mysql_fetch_array($result))
	{  
	$scount = $scount + 1;
	$paroid=$Lab->getParentID($accessionno,$labss);//get parent id
	
if ($paroid =="0")
{
$paroid="";
$previouswsheet="";
$labnodesc="Lab no:";
$rerundetails="";
}
else
{
$previouswsheet=$Lab->getWorksheetnoforParentID($paroid,$labss);
$paroid=" <b>".$paroid."</b> " ;
$labnodesc="New Lab no:";
$rerundetails= '<br> Parent Lab No:  '. $paroid .'<br> Previous Run Worksheet #: '.   " <b>". $previouswsheet ."</b> ";
}
		
		$RE= $colcount%6;
		
		 ?>
		 
	

  
     
     <td width='178px' bgcolor="#dddddd">
	 <div align="right">
	 <table><tr><td class="comment style1 style4"><small>
	 <?php echo $scount;?></small>
	 </td></tr></table></div>
	 
	
	 <font size="-4"> Patient ID:   <?php echo $patient; ?></font><br>  
	 <font size="-4"> Batch no:   <?php echo $batchno; ?></font> <br> 
	 <font size="-4"> PCR no:   <?php echo $pcr; ?></font> <br> 
	 <font size="-4"> <?php echo $labnodesc; ?>  <?php echo $accessionno; ?></font> 
	 <font size="-4"><?php echo $rerundetails; ?></font> 
	 	 <div align="center">
	<?php echo" <img src='../html/image.php?code=code128&o=2&dpi=50&t=50&r=1&rot=0&text=$accessionno&f1=Arial.ttf&f2=0&a1=&a2=B&a3='/>";?>   </div></td>
<?php  $colcount ++;
		 
		
             if ($RE==0)
			 { 
			?>
     </tr>
<?php
		 }//end if modulus is 0
	 }//end while?>
</table>

</body>
</html>