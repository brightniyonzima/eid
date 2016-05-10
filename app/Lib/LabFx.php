<?php

class LabFx{

	protected $db_conn;

	public function __construct(){

		$db_host = env('DB_HOST', '127.0.0.1');
		$db_name = env('DB_DATABASE', 'zl4');
		$db_user = env('DB_USERNAME', 'root');
		$db_pass = env('DB_PASSWORD', '');

		$this->db_conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

		mysqli_select_db($this->db_conn, $db_name);
	}

	public function GetDBconnection(){
		return $this->db_conn;
	}

	function GetFacilityLevel($fcode){

		$SQL = "SELECT level FROM facilitys WHERE  ID='$fcode'";
		
		$districtnamequery=mysqli_query($this->db_conn,  $SQL );
		$districtname = mysqli_fetch_array($districtnamequery);  

		return $districtname['level'];
	}

	function GetFacilityHubname($fcode)
	{
		$districtnamequery=mysqli_query($this->db_conn, "SELECT hub 
	            FROM facilitys
	            WHERE  ID='$fcode'"); 
				$districtname = mysqli_fetch_array($districtnamequery);  
				$facilityname=$districtname['hub'];
			return $facilityname;
	}

	function GetQuarterName($quarter){

		    if	($quarter == 1)	return "JAN-MAR";	
		elseif 	($quarter == 2)	return "APR-JUN";
		elseif 	($quarter == 3)	return "JUL-SEP";
		elseif 	($quarter == 4)	return "OCT-DEC";
	}



	//get mothers hivstatus ID
	function GetMotherHIVstatusID($mid)
	{
		$getmother = "SELECT status as 'HIV' FROM mothers WHERE ID='$mid'";
			$gotmother = mysqli_query($this->db_conn, $getmother) or die(mysql_error());
			$motherrec = mysqli_fetch_array($gotmother);
			$HIV = $motherrec['HIV'];
		
	return $HIV;
	}

	//get mothers feeding types ID
	function GetMotherFeedingID($mid)
	{
		$getmother = "SELECT feeding as 'motherfeeding' FROM mothers WHERE ID='$mid'";
			$gotmother = mysqli_query($this->db_conn, $getmother) or die(mysql_error());
			$motherrec = mysqli_fetch_array($gotmother);
			$motherfeeding = $motherrec['motherfeeding'];
			return $motherfeeding;
	}
	//get max month
	function GetMaxMonth()
	{
		$getm = "select max(month(dateenteredindatabase)) as maxmonth from samples ";
		$gotmo = mysqli_query($this->db_conn, $getm) or die(mysql_error());
		$mo = mysqli_fetch_array($gotmo);
		$mot = $mo['maxmonth'];
		return $mot;
	}
	//get min month
	function GetMinMonth()
	{
		$getm = "select min(month(dateenteredindatabase)) as minmonth from samples ";
		$gotmo = mysqli_query($this->db_conn, $getm) or die(mysql_error());
		$mo = mysqli_fetch_array($gotmo);
		$mot = $mo['minmonth'];
		return $mot;
	}
	//get mothers entry point ID
	function GetEntryPointID($mid)
	{
		$getmother = "SELECT entry_point as 'entrypoint' FROM mothers WHERE ID='$mid'";
			$gotmother = mysqli_query($this->db_conn, $getmother) or die(mysql_error());
			$motherrec = mysqli_fetch_array($gotmother);
			$entrypoint = $motherrec['entrypoint'];
			return $entrypoint;
	}
	//get patient prophylaxis
	function GetPatientProphylaxisID($patient)
	{
		$getpatient = "SELECT patients.prophylaxis as 'infantprophylaxis' FROM patients WHERE patients.ID='$patient' ";
			$gotpatient = mysqli_query($this->db_conn, $getpatient) or die(mysql_error());
			$patientrec = mysqli_fetch_array($gotpatient);
			$infantprophylaxis = $patientrec['infantprophylaxis'];
			if ($infantprophylaxis == 0)
			{
			$infantprophylaxis="";
			}
			
		return $infantprophylaxis;
	}
	//get the batch envelope no
	function GetEnvelopeNo($batchno)
	{
	$getbatch = "SELECT DISTINCT(envelopeno) from samples WHERE batchno='$batchno'";
	  $gotbatch = mysqli_query($this->db_conn, $getbatch) or die(mysql_error());
	  $batchrec = mysqli_fetch_array($gotbatch);
	  $patient= $batchrec['envelopeno'];
	return $patient;
	}
	//get mothers pmtct intervention
	function GetMotherProphylaxisID($mid,$stage)
	{
		if ($stage==1)
		{
		$getmother = "SELECT mothers.antenalprophylaxis as 'antenalprophylaxis' FROM mothers WHERE mothers.ID='$mid'";
			$gotmother = mysqli_query($this->db_conn, $getmother) or die(mysql_error());
			$motherrec = mysqli_fetch_array($gotmother);
			$motherprophylaxis = $motherrec['antenalprophylaxis'];
		

		}
		elseif ($stage==2)
		{
		$getmother = "SELECT mothers.deliveryprophylaxis as 'deliveryprophylaxis' FROM mothers WHERE mothers.ID='$mid'";
			$gotmother = mysqli_query($this->db_conn, $getmother) or die(mysql_error());
			$motherrec = mysqli_fetch_array($gotmother);
			$motherprophylaxis = $motherrec['deliveryprophylaxis'];
		


		}
		elseif ($stage==3)
		{
		$getmother = "SELECT mothers.postnatalprophylaxis as 'postnatalprophylaxis' FROM mothers WHERE mothers.ID='$mid'";
			$gotmother = mysqli_query($this->db_conn, $getmother) or die(mysql_error());
			$motherrec = mysqli_fetch_array($gotmother);
			$motherprophylaxis = $motherrec['postnatalprophylaxis'];
		

		}
		if ($motherprophylaxis ==0)
		{
		$motherprophylaxis="";
		}
		return $motherprophylaxis;
		
	}
	//get distrcit name
	function GetFacilityName($fcode)
	{
	$districtnamequery=mysqli_query($this->db_conn, "SELECT name 
	            FROM facilitys
	            WHERE  ID='$fcode'"); 
				$districtname = mysqli_fetch_array($districtnamequery);  
				$facilityname=$districtname['name'];
			return $facilityname;
	}
	//get month names from ID
	function GetMonthName($month)
	{

	 if ($month==1)
	 {
	     $monthname=" Jan ";
	 }
	else if ($month==2)
	 {
	     $monthname=" Feb ";
	 }else if ($month==3)
	 {
	     $monthname=" Mar ";
	 }else if ($month==4)
	 {
	     $monthname=" Apr ";
	 }else if ($month==5)
	 {
	     $monthname=" May ";
	 }else if ($month==6)
	 {
	     $monthname=" Jun ";
	 }else if ($month==7)
	 {
	     $monthname=" Jul ";
	 }else if ($month==8)
	 {
	     $monthname=" Aug ";
	 }else if ($month==9)
	 {
	     $monthname=" Sep ";
	 }else if ($month==10)
	 {
	     $monthname=" Oct ";
	 }else if ($month==11)
	 {
	     $monthname=" Nov ";
	 }
	  else if ($month==12)
	 {
	     $monthname=" Dec ";
	 }
	  else if ($month==13)
	 {
	     $monthname=" Jan - Sep  ";
	 }
	return $monthname;
	}

	//get facility name
	function GetFacility($autocode)
	{
	$facilityquery=mysqli_query($this->db_conn, "SELECT name FROM facilitys where ID='$autocode' ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($facilityquery);
	$fname=$dd['name'];
	return $fname;
	}
	//get sms printer no for th sms printer
	function GetFacilitySMSPrinterNo($autocode)
	{
	$facilityquery=mysqli_query($this->db_conn, "SELECT smsprinterphoneno FROM facilitys where ID='$autocode' ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($facilityquery);
	$fname=$dd['smsprinterphoneno'];
	if ($fname !="") 
	{
	$sms=$fname;
	}
	else
	{
	$sms=0;
	}
	return $sms;
	}
	//get faclity name
	function GetPartnerName($patid)
	{
	$patnamequery=mysqli_query($this->db_conn, "SELECT name 
	            FROM partners
	            WHERE  ID='$patid'"); 
				$patname = mysqli_fetch_array($patnamequery);  
				$patnaname=$patname['name'];
			return $patnaname;
	}
	function EmailSent($batchno)
	{
	$emailquery=mysqli_query($this->db_conn, "SELECT SentEmail FROM samples where batchno='$batchno' ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($emailquery);
	$emailsent=$dd['SentEmail'];
	if ($emailsent != 1) 
	{
	$sent='N';
	}
	else
	{
	$sent='Y';
	}
	return $sent;
	}

	//get users name
	function GetUserFullnames($userid)
	{
	$usersquery=mysqli_query($this->db_conn, "SELECT surname,oname FROM original_users where ID='$userid' ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($usersquery);
	$sname=$dd['surname'];
	$onames = $dd['oname'] ;
	$names = $sname . ", " . $onames ;
	return $names;
	}

	//get users details
	function GetUserDetails($userid)
	{

		$reslt = mysqli_query($this->db_conn, "SELECT * FROM original_users where ID='$userid' ") or die(mysql_error());
	  	  $row=mysqli_fetch_assoc($reslt);
	    return $row;   
	}
	//get sample/patinet ID from lab ID
	function GetActualPatientID($labid)
	{
	$samplequery=mysqli_query($this->db_conn, "SELECT patient FROM samples where ID='$labid' ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($samplequery);
	$patient=$dd['patient'];

	return $patient;
	}
	//get lab email
	function GetLabEmail($lab)
	{
	$samplequery=mysqli_query($this->db_conn, "SELECT email FROM labs where ID='$lab' ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($samplequery);
	$email=$dd['email'];

	return $email;
	}
	//save district details
	function Savedistrict($name,$province,$comment,$districtcode,$districtcodeid)
	{
	$savedistrict = "INSERT INTO 		
	districts(name,province,comment,flag,districtcode,districtcodeid)VALUES('$name','$province','$comment',1,'$districtcode','$districtcodeid')";
				$districts = @mysqli_query($this->db_conn, $savedistrict) or die(mysql_error());
		return $districts;

	}
	//get task name from task id
	function GetTaskName($taskid)
	{
	$taskquery=mysqli_query($this->db_conn, "SELECT name FROM tasks where ID='$taskid' ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($taskquery);
	$name=$dd['name'];

	return $name;
	}
	//get lab in wich facility belongs to
	function GetLab($lab)
	{
	$labquery=mysqli_query($this->db_conn, "SELECT name FROM labs where ID='$lab' ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($labquery);
	$labname=$dd['name'];
	return $labname;
	}
	//get facility code
	function GetActualFacilityCode($fid)
	{
	$facilityquery=mysqli_query($this->db_conn, "SELECT * FROM facilitys where ID='$fid' ")or die(mysql_error()); 
	$row=mysqli_fetch_assoc($facilityquery);
	return $row;
	}
	//get samples pper facility per month
	function Gettestedperfacilitypermonth($facility,$year,$month)
	{

	$strQuery=mysqli_query($this->db_conn, "SELECT COUNT(ID) as 'monthlytestedsamples' FROM samples WHERE result > 0 AND facility='$facility' AND  YEAR(datetested)='$year' AND MONTH(datetested)='$month'")or die(mysql_error());
	$resultarray=mysqli_fetch_array($strQuery);
	$monthlytestedsamples=$resultarray['monthlytestedsamples'];
	return $monthlytestedsamples;


	}
	//get samples pper facility per year
	function Gettestedperfacilityperyear($facility,$year)
	{

	$strQuery=mysqli_query($this->db_conn, "SELECT COUNT(ID) as 'yearlytestedsamples' FROM samples WHERE result > 0 AND facility='$facility' AND  YEAR(datetested)='$year' ")or die(mysql_error());
	$resultarray=mysqli_fetch_array($strQuery);
	$yearlytestedsamples=$resultarray['yearlytestedsamples'];
	return $yearlytestedsamples;


	}

	//get samples pper facility per year per result type
	function Gettestedperfacilityperyearperresult($facility,$year,$resulttype)
	{

	$strQuery=mysqli_query($this->db_conn, "SELECT COUNT(ID) as 'testedsamples' FROM samples WHERE result ='$resulttype' AND facility='$facility' AND  YEAR(datetested)='$year' ")or die(mysql_error());
	$resultarray=mysqli_fetch_array($strQuery);
	$testedsamples=$resultarray['testedsamples'];
	return $testedsamples;


	}
	//determine if batch exists
	function GetBatchNoifExists($datereceived,$facility,$lab)
	{
	$strQuery=mysqli_query($this->db_conn, "SELECT samples.batchno FROM samples,facilitys WHERE samples.datereceived='$datereceived' AND samples.facility='$facility' AND samples.facility=facilitys.ID AND facilitys.lab='$lab' ORDER by batchno DESC LIMIT 1")or die(mysql_error());
	$numrows=mysqli_num_rows($strQuery);
	return $numrows;
	}
	//determine EXISITNG BATCH NO
	function GetExistingBatchNo($datereceived,$facility,$lab)
	{

	$strQuery=mysqli_query($this->db_conn, "SELECT samples.batchno FROM samples,facilitys WHERE samples.datereceived='$datereceived' AND samples.facility='$facility' AND samples.facility=facilitys.ID AND facilitys.lab='$lab' ")or die(mysql_error());
	$dd=mysqli_fetch_array($strQuery);
	$batch=$dd['batchno'];
	return $batch;
	}
	//get last serial id
	function GetLastSampleSerialID($lab)
	{

	$RES = mysqli_query($this->db_conn, "SELECT MAX(samples.ID) as 'Max' FROM samples	WHERE  samples.labtestedin='$lab'");

	if(mysqli_num_rows($RES) == 1)
	{
	$ROW = mysqli_fetch_assoc($RES);
	$BatchNo = $ROW['Max'] + 1; 
	}
	return $BatchNo;
	}
	function GenerateSampleAccessionNumber($lastid,$fcode) {
		$dcode=GetDistrictCode($fcode);
		$accessionno=$dcode.$fcode.$lastid;
		return $accessionno;
		
		/*
		$year=0;
		$year=gmdate("Y");
		$facilityID=0;
		$facilityID=$fcode;
		$serialNumber=0;
		
		//query
		$query=0;
		$query=mysqli_query($this->db_conn, "select count(ID) theCount from samples where facility='$facilityID' and year(datereceived)='$year'");
		$serialNumber=mysqli_result($query,0,'theCount')+1;
		
		$finalSerialNumber=0;
		switch(strlen($serialNumber)) {
			default:
			case 1:
				$finalSerialNumber="00$serialNumber";
			break;
			case 2:
				$finalSerialNumber="0$serialNumber";
			break;
			case 3:
				$finalSerialNumber=$serialNumber;
			break;
		}
		
		//accession number
		$accessionno=0;
		//$accessionno="E".gmdate("y").$facilityID.$finalSerialNumber;
		$accessionno=$dcode.gmdate("y").$facilityID.$finalSerialNumber;
		//return
		return $accessionno;
		*/
	}
	//save mother details
	function GetSavedMother($mhivstatus,$mentpoint,$caregiverphn,$mbfeeding,$propha,$prophb,$prophc,$fcode,$labss,$batch)
	{

	$motherrec ="INSERT INTO 		
	mothers(facility,entry_point,caregiverphn,feeding,antenalprophylaxis,deliveryprophylaxis,postnatalprophylaxis,status,batchno,labtestedin,fcode,synched)                                                       
	VALUES
	('$fcode','$mentpoint','$caregiverphn','$mbfeeding','$propha','$prophb','$prophc','$mhivstatus','$batch','$labss','$facility','$synched')";
				$mother = @mysqli_query($this->db_conn, $motherrec) or die(mysql_error());
		return $mother;

	}
	function SavePendingTasks($task,$batchno,$status,$accessionno,$labss,$datereceived)
	{
	//query pendin tasks table for id of mother just saved
	$repeatrec ="INSERT INTO 		
	pendingtasks(task,batchno,status,sample,lab,datereceived)VALUES
	('$task','$batchno','$status','$accessionno','$labss','$datereceived')";
				$repeat = @mysqli_query($this->db_conn, $repeatrec) or die(mysql_error());
		return $repeat;

	}
	function GetLastMotherID($labss,$batch)
	{
		$getmotherid = "SELECT mothers.ID
	            FROM mothers
					WHERE  mothers.labtestedin='$labss'  and batchno='$batch'
	            ORDER by ID DESC LIMIT 1 ";
				$getmum=mysqli_query($this->db_conn, $getmotherid);
				$mumrec=mysqli_fetch_array($getmum);
				$mid=$mumrec['ID'];
				return $mid;
	}
	/**
	 * Generates Id prefix basing on facility so as to make Ids unique
	 * @param string $fcode - facility name
	 * @return string
	 */
	function GeneratePatientIdPrefix($fcode)
	{
		$dcode=GetDistrictCode($fcode);
		$patientIdPrefix = $dcode .'-' . $lastid . '-';
		return $patientIdPrefix;
	}
	//save patient details
	function GetSavedPatient($labno,$infant,$infantid,$motherid,$age,$gender,$iproph,$labss,$datecreated)
	{
	$child = "INSERT INTO   
	patients(ID,infantname,infantid,mother,age,gender,prophylaxis,labtestedin,datecreated)VALUES('$labno','$infant','$infantid','$motherid','$age','$gender','$iproph','$labss','$datecreated')";
	   $patient = @mysqli_query($this->db_conn, $child) or die(mysql_error());
	 return $patient;

	}
	//save sampels details
	function GetSavedSamples($batch,$infantid,$labno,$fcode,$datecollected,$datedispatched,$datereceived,$comments,$pcr,$envelopeno,$datecreated, $testtype, $nonroutine)
	{


	$child ="
	INSERT INTO samples
	(batchno, patient,accessionno, facility, datecollected, datedispatchedfromfacility, datereceived, comments,pcr,Flag,envelopeno,dateenteredindatabase, testtype, nonroutine)
	VALUES
	('$batch', '$infantid','$labno', '$fcode', '$datecollected', '$datedispatched', '$datereceived', '$comments','$pcr',1,'$envelopeno','$datecreated', '$testtype', '$nonroutine')";
	   $success = mysqli_query($this->db_conn, $child) or die(mysql_error());
	 return $success;

	}


	//save sampels details
	function GetSavedRepeatSamples($batchno,$envelopeno,$patient,$labno,$facility,$receivedstatus,$spots,$datecollected,$datedispatchedfromfacility, $datereceived,$comments,$labcomment,$paroid,$rejectedreason,$pcr)
			
	{

	$child ="
	INSERT INTO samples
	(batchno,envelopeno,patient,accessionno,facility,datecollected,datedispatchedfromfacility,datereceived,comments,pcr,receivedstatus,spots,labcomment,parentid,rejectedreason,Flag,status)
	VALUES
	('$batchno','$envelopeno','$patient','$labno','$facility','$datecollected','$datedispatchedfromfacility','$datereceived','$comments','$pcr','$receivedstatus','$spots','$labcomment','$paroid','$rejectedreason',1,1)";
				$success = mysqli_query($this->db_conn, $child) or die(mysql_error());
		return $success;

	}
	//update facility details
	function GetUpdatedFacility($fcode,$transport,$sender,$returnaddress,$tel)
	{

	if ($sender !='')
	{
	$updateeentry = mysqli_query($this->db_conn, "UPDATE facilitys
	              SET contactperson='$sender',transport='$transport'
				  			   WHERE (ID = '$fcode')")or die(mysql_error());
							   return $updateeentry;
	}
	else
	{

	}

	if ($returnaddress !='')
	{
	$updateeentry = mysqli_query($this->db_conn, "UPDATE facilitys
	              SET PostalAddress = '$returnaddress',transport='$transport'
				  			   WHERE (ID = '$fcode')")or die(mysql_error());
							   return $updateeentry;
	}
	else
	{

	}

	if ($tel !='')
	{
	$updateeentry = mysqli_query($this->db_conn, "UPDATE facilitys
	              SET contacttelephone='$tel',transport='$transport'
				  			   WHERE (ID = '$fcode')")or die(mysql_error());
							   return $updateeentry;
	}
	else
	{
	}

					
	}//end fucntion

	//update sample details after 2nd approval
	function GetUpdatedSample($accessionno,$sspot,$srecstatus,$rejectedreason,$labcomment )
	{
	$updateeentry = mysqli_query($this->db_conn, "UPDATE samples
	              SET spots = '$sspot',receivedstatus='$srecstatus',rejectedreason='$rejectedreason',labcomment='$labcomment' ,status=1
				  			   WHERE (accessionno= '$accessionno')")or die(mysql_error());
							   return $updateeentry;
					
	}
	/**..................................
	 samples listings, view functions ..........
	.................
	...............**/
	//get patient id
	function GetPatient($batchno)
	{
	$getbatch = "SELECT accessionno from samples WHERE batchno='$batchno'";
			$gotbatch = mysqli_query($this->db_conn, $getbatch) or die(mysql_error());
			$batchrec = mysqli_fetch_array($gotbatch);
			$patient= $batchrec['accessionno'];
	return $patient;
	}
	//get patient names
	function GetPatientNames($accessionno)
	{
	$getbatch = "SELECT infantname from patients WHERE ID='$accessionno'";
			$gotbatch = mysqli_query($this->db_conn, $getbatch) or die(mysql_error());
			$batchrec = mysqli_fetch_array($gotbatch);
			$patient= $batchrec['infantname'];
	return $patient;
	}
	//get patient names
	function GetNextSampleID($batchno,$CurrentAutoID)
	{
	$getbatch = "SELECT ID as 'nextautoid' from samples WHERE batchno='$batchno' and status=0 and ID !='$CurrentAutoID' LIMIT 0,1";
	$gotbatch = mysqli_query($this->db_conn, $getbatch) or die(mysql_error());
	$batchrec = mysqli_fetch_array($gotbatch);
	$patient= $batchrec['nextautoid'];
	if (($patient > 0) || ($patient !=''))
	{
	$nextAutoID=$patient;
	}
	else
	{
	$nextAutoID=0;
	}
	return $nextAutoID;
	}
	//get date received for a batch
	function GetDatereceived($batchno)
	{
		$getdate = "SELECT datereceived from samples WHERE batchno='$batchno' and flag=1  and repeatt=0";
			$gotdate = mysqli_query($this->db_conn, $getdate) or die(mysql_error());
			$daterec = mysqli_fetch_array($gotdate);
			$datereceived = $daterec['datereceived'];

			if  (($datereceived !="0000-00-00") && ($datereceived !='1970-01-01') && ($datereceived !=''))
		{
			$sdrec=date("d-M-Y",strtotime($datereceived));
		}
		else
		{
		$sdrec="";
		}
			
			
	return $sdrec;
	}

	function GetDateSamplesDispatchedfromFacility($batchno)
	{
		$getdate = "SELECT datedispatchedfromfacility from samples WHERE batchno='$batchno' and flag=1  and repeatt=0";
			$gotdate = mysqli_query($this->db_conn, $getdate) or die(mysql_error());
			$daterec = mysqli_fetch_array($gotdate);
			$datedispatchedfromfacility = $daterec['datedispatchedfromfacility'];

			if  (($datedispatchedfromfacility !="0000-00-00") && ($datedispatchedfromfacility !='1970-01-01') && ($datedispatchedfromfacility !=''))
		{
			$sdispatch=date("d-M-Y",strtotime($datedispatchedfromfacility));
		}
		else
		{
		$sdispatch="";
		}
			
			
	return $sdispatch;
	}

	//get date received for a batch
	function GetBatchEnteredby($batchno)
	{
		$getdate = "SELECT accessionno from samples WHERE batchno='$batchno' and flag=1  LIMIT 0,1";
		$gotdate = mysqli_query($this->db_conn, $getdate) or die(mysql_error());
		$daterec = mysqli_fetch_array($gotdate);
		$accessionno = $daterec['accessionno'];

		$gd = mysqli_query($this->db_conn, "SELECT user from usersactivity  WHERE task=1 and patient='$accessionno'") or die(mysql_error());
		$grec = mysqli_fetch_array($gd);
		$userid = $grec['user'];
		$userfullnames=GetUserFullnames($userid);

	return $userfullnames;
	}
	//get date dispatched
	function GetDateDispatched($batchno)
	{
		$getdate = "SELECT datedispatched from samples WHERE batchno='$batchno' and flag=1 and status=1 and repeatt=0";
			$gotdate = mysqli_query($this->db_conn, $getdate) or die(mysql_error());
			$daterec = mysqli_fetch_array($gotdate);
			$datedispatched = $daterec['datedispatched'];

			if  (($datedispatched !="0000-00-00") && ($datedispatched !='1970-01-01'))
		{
			$sdis=date("d-M-Y",strtotime($datedispatched));
		}
		else
		{
		$sdis="";
		}
			
	return $sdis;
	}

	//get date batch printed
	function GetDatePrinted($batchno)
	{
		$getdate = "SELECT dateprinted from samples WHERE batchno='$batchno' and flag=1 and status=1 and repeatt=0";
			$gotdate = mysqli_query($this->db_conn, $getdate) or die(mysql_error());
			$daterec = mysqli_fetch_array($gotdate);
			$dateprinted = $daterec['dateprinted'];

			if  (($dateprinted !="0000-00-00") && ($dateprinted !='1970-01-01'))
		{
			$datebatchprinted=date("d-M-Y",strtotime($dateprinted));
		}
		else
		{
		$datebatchprinted="";
		}
			
	return $datebatchprinted;
	}
	//get miother id for patient
	function GetMotherID($patient)
	{
		$getpatient = "SELECT mother FROM patients WHERE ID='$patient' ";
			$gotpatient = mysqli_query($this->db_conn, $getpatient) or die(mysql_error());
			$patientrec = mysqli_fetch_array($gotpatient);
			$mid = $patientrec['mother'];
	return $mid;
	}
	//get patient gender
	function GetPatientGender($patient)
	{
		$getpatient = "SELECT gender FROM patients WHERE ID='$patient'";
			$gotpatient = mysqli_query($this->db_conn, $getpatient) or die(mysql_error());
			$patientrec = mysqli_fetch_array($gotpatient);
			$pgender = $patientrec['gender'];
			
			if ($pgender == "Left Blank")
			{
			$pgender="LB";
			}
		
	return $pgender;
	}
	//get patient age
	function GetPatientAge($patient)
	{
		$getpatient = "SELECT age FROM patients WHERE ID='$patient'";
			$gotpatient = mysqli_query($this->db_conn, $getpatient) or die(mysql_error());
			$patientrec = mysqli_fetch_array($gotpatient);
			$age = $patientrec['age'] . " Months";
			
		return $age;
	}

	//get patient age
	function GetPatientsAge($patient)
	{
		$getpatient = "SELECT age FROM patients WHERE ID='$patient'";
			$gotpatient = mysqli_query($this->db_conn, $getpatient) or die(mysql_error());
			$patientrec = mysqli_fetch_array($gotpatient);
			$age = $patientrec['age'] ;
			
		return $age;
	}
	//get patient date of birth
	function GetPatientDOB($patient)
	{
		$getpatient = "SELECT dob FROM patients WHERE ID='$patient'";
			$gotpatient = mysqli_query($this->db_conn, $getpatient) or die(mysql_error());
			$patientrec = mysqli_fetch_array($gotpatient);
			$dob = $patientrec['dob'];
		if ($dob !="")
			{
			$dob=date("d-M-Y",strtotime($dob));
			}
			else
			{
			$dob="None";
			}
	return $dob;
	}
	//get patient prophylaxis
	function GetPatientProphylaxis($patient)
	{
		$getpatient = "SELECT patients.prophylaxis,prophylaxis.name as 'infantprophylaxis' FROM patients,prophylaxis WHERE patients.ID='$patient' AND patients.prophylaxis=prophylaxis.ID";
			$gotpatient = mysqli_query($this->db_conn, $getpatient) or die(mysql_error());
			$patientrec = mysqli_fetch_array($gotpatient);
			$infantprophylaxis = $patientrec['infantprophylaxis'];
			
		return $infantprophylaxis;
	}

	//get facility code from batch 
	function GetFacilityCode($batchno)
	{
		$getfcode = "SELECT facility FROM samples WHERE batchno='$batchno' AND facility !=0  ";
			$gotfcode = mysqli_query($this->db_conn, $getfcode) or die(mysql_error());
			$fcoderec = mysqli_fetch_array($gotfcode);
			$facility = $fcoderec['facility'];
		
	return $facility;
	}


	//get facility code from batch 
	function GetFacilityID($sample)
	{
		$getfcode = "SELECT facility FROM samples WHERE accessionno='$sample' ";
			$gotfcode = mysqli_query($this->db_conn, $getfcode) or die(mysql_error());
			$fcoderec = mysqli_fetch_array($gotfcode);
			$facility = $fcoderec['facility'];
		
	return $facility;
	}
	//get mothers hivstatus
	function GetMotherHIVstatus($mid)
	{
		$getmother = "SELECT mothers.status,results.Name as 'HIV' FROM mothers,results WHERE mothers.ID='$mid' AND mothers.status=results.ID";
			$gotmother = mysqli_query($this->db_conn, $getmother) or die(mysql_error());
			$motherrec = mysqli_fetch_array($gotmother);
			$HIV = $motherrec['HIV'];
		
	return $HIV;
	}
	//get mothers pmtct intervention
	function GetMotherProphylaxis($mid,$stage)
	{
		if ($stage==1)
		{
			
		$getmother = "SELECT mothers.antenalprophylaxis,prophylaxis.name as 'antenalprophylaxis' FROM mothers,prophylaxis WHERE mothers.ID='$mid' AND mothers.antenalprophylaxis=prophylaxis.ID";
			$gotmother = mysqli_query($this->db_conn, $getmother) or die(mysql_error());
			$motherrec = mysqli_fetch_array($gotmother);
			$antenalprophylaxis = $motherrec['antenalprophylaxis'];
		
	return $antenalprophylaxis;
		}
		else if ($stage==2)
		{
			$getmother = "SELECT mothers.deliveryprophylaxis,prophylaxis.name as 'deliveryprophylaxis' FROM mothers,prophylaxis WHERE mothers.ID='$mid' AND mothers.deliveryprophylaxis=prophylaxis.ID";
			$gotmother = mysqli_query($this->db_conn, $getmother) or die(mysql_error());
			$motherrec = mysqli_fetch_array($gotmother);
			$deliveryprophylaxis = $motherrec['deliveryprophylaxis'];
		
	return $deliveryprophylaxis;
		}
		else if ($stage==3)
		{
			$getmother = "SELECT mothers.postnatalprophylaxis,prophylaxis.name as 'postnatalprophylaxis' FROM mothers,prophylaxis WHERE mothers.ID='$mid' AND mothers.postnatalprophylaxis=prophylaxis.ID";
			$gotmother = mysqli_query($this->db_conn, $getmother) or die(mysql_error());
			$motherrec = mysqli_fetch_array($gotmother);
			$postnatalprophylaxis = $motherrec['postnatalprophylaxis'];
		
	return $postnatalprophylaxis;
		}

	}
	//get mothers feeding types
	function GetMotherFeeding($mid)
	{
		$getmother = "SELECT mothers.feeding,feedings.name as 'motherfeeding' FROM mothers,feedings WHERE mothers.ID='$mid' AND mothers.feeding=feedings.ID";
			$gotmother = mysqli_query($this->db_conn, $getmother) or die(mysql_error());
			$motherrec = mysqli_fetch_array($gotmother);
			$motherfeeding = $motherrec['motherfeeding'];
			
			if ($motherfeeding =="Left Blank")
			{
			 $motherfeeding="LB";
			}
			return $motherfeeding;
	}
	//get mothers feeding types description
	function GetMotherFeedingDesc($mid)
	{
		$getmother = "SELECT mothers.feeding,feedings.description as 'feedingdesc' FROM mothers,feedings WHERE mothers.ID='$mid' AND mothers.feeding=feedings.ID";
			$gotmother = mysqli_query($this->db_conn, $getmother) or die(mysql_error());
			$motherrec = mysqli_fetch_array($gotmother);
			$feedingdesc = $motherrec['feedingdesc'];
			return $feedingdesc;
	}
	//get mothers entry point
	function GetEntryPoint($mid)
	{
		$getmother = "SELECT mothers.entry_point,entry_points.name as 'entrypoint' FROM mothers,entry_points WHERE mothers.ID='$mid' AND mothers.entry_point=entry_points.ID";
			$gotmother = mysqli_query($this->db_conn, $getmother) or die(mysql_error());
			$motherrec = mysqli_fetch_array($gotmother);
			$entrypoint = $motherrec['entrypoint'];
			return $entrypoint;
	}

	//get total no of sampels per batch
	function GetSamplesPerBatch($batchno)
	{
		$samplequery = mysqli_query($this->db_conn, "SELECT COUNT(ID) as num_samples FROM samples WHERE batchno='$batchno' AND parentid='0' AND Flag=1") or die(mysql_error());
			
			
			$samplerow = mysqli_fetch_array($samplequery);
			$num_samples = $samplerow['num_samples'];
		
		
	return $num_samples ;
	}

	//get total no of sampels per batch
	function GetDeletedSamplesPerBatch($batchno)
	{
		$dsamplequery = mysqli_query($this->db_conn, "SELECT COUNT(ID) as dnum_samples FROM samples WHERE batchno='$batchno' AND parentid='0' AND Flag=0") or die(mysql_error());
			
			
			$dsamplerow = mysqli_fetch_array($dsamplequery);
			$dnum_samples = $dsamplerow['dnum_samples'];
		
		
	return $dnum_samples ;
	}

	//get total no of samples deleted for a given date
	function GetTotalDeletedSamplesPerMonth()
	{
		$currentmonth = date('m'); //get current month
		$dsample = mysqli_query($this->db_conn, "SELECT COUNT(ID) as dnum_samples FROM samples WHERE parentid='0' AND Flag=0 and month(datereceived) = '$currentmonth'") or die(mysql_error());
		
		$dsamplerow = mysqli_fetch_array($dsample);
		$dnum_samples = $dsamplerow['dnum_samples'];
		
	return $dnum_samples;
	}

	function CheckBatchComplete($labbs)
	{
				$samplequery = mysqli_query($this->db_conn, "SELECT DISTINCT batchno
	            FROM samples
				WHERE labtestedin='$labss'
				AND BatchComplete=2 order by batchno ASC") or die(mysql_error());
	 				while(list($batchno) = mysqli_fetch_array($samplequery))
						{ 	
									$numsamples=GetSamplesPerBatch($batchno);
									$rejected=GetRejectedSamplesPerBatch($batchno);
									$with_result_samples=GetSamplesPerBatchwithResults($batchno);
									////no of saMPLES IN BATCH without results
									$no_result_samples = $numsamples - $with_result_samples - $rejected;
								
									if($no_result_samples != 0)
									{
									//update batch to be complete
						 				$ifcompleterec = mysqli_query($this->db_conn, "UPDATE samples
	              						SET  BatchComplete=0
							 		WHERE (batchno='$batchno')")or die(mysql_error());
									}
									else
									{
										$ifcompleterec = mysqli_query($this->db_conn, "UPDATE samples
	              						SET  BatchComplete=2
							 		WHERE (batchno='$batchno')")or die(mysql_error());
									}
								
									
						}		//en

	}


	//get no of rejected samples per batch
	function GetRejectedSamplesPerBatch($batchno)
	{
		$rejquery =  mysqli_query($this->db_conn, "SELECT COUNT(ID) as rej_samples FROM samples WHERE batchno='$batchno' AND receivedstatus=2
	 AND Flag=1") or die(mysql_error());
	$rejrow = mysqli_fetch_array($rejquery);
	$rej_samples = $rejrow['rej_samples'];

		
	return $rej_samples ;
	}
	//get no of not received samples per batch
	function GetNotReceivedSamplesPerBatch($batchno)
	{
		$rejquery =  mysqli_query($this->db_conn, "SELECT COUNT(ID) as n_samples FROM samples WHERE batchno='$batchno' AND receivedstatus=4
	 AND Flag=1") or die(mysql_error());
	$rejrow = mysqli_fetch_array($rejquery);
	$rej_samples = $rejrow['n_samples'];

		
	return $rej_samples ;
	}
	//get no of rejected samples per batch in pedning tasks table
	function GetRejectedSamplesPerBatchFromPendingTasks($batchno,$lab)
	{
		$rejquery = mysqli_query($this->db_conn, "SELECT COUNT(sample) as rej_samples FROM pendingtasks WHERE batchno='$batchno' AND task=6 AND status=0 AND lab='$lab' ") or die(mysql_error());
	$rejrow = mysqli_fetch_array($rejquery, mysqli_ASSOC);
	$rej_samples = $rejrow['rej_samples'];

		
	return $rej_samples ;
	}



	//get no of rejected samples per batch in pedning tasks table that are complete
	function GetTotalCompleteRejectedSamplesBatches($batchno,$lab)
	{
		
		$rejquery = "SELECT COUNT(sample) as rej_samples FROM pendingtasks WHERE batchno='$batchno' AND task=6 AND status=1 AND lab='$lab' 
	 ";
	$rejresult = mysqli_query($this->db_conn, $rejquery) or die('mysql_error()');
	$rejrow = mysqli_fetch_array($rejresult, mysqli_ASSOC);
	$rej_samples = $rejrow['rej_samples'];

		
	return $rej_samples ;
	}


	//get no of  samples per batch with results
	function GetSamplesPerBatchwithResults($batchno)
	{
	$noresultsamplee = mysqli_query($this->db_conn, "SELECT COUNT(ID) as with_result_samples FROM samples WHERE  batchno='$batchno' AND result > 0 AND repeatt =0  AND Flag=1" ) or die(mysql_error());
	$noresultsampleresultroww = mysqli_fetch_array($noresultsamplee);
	$with_result_samples = $noresultsampleresultroww['with_result_samples'];
	return $with_result_samples  ;
	}

	function getifcomplete($batchno)
	{
	$numsamples=GetSamplesPerBatch($batchno);
	$rejected=GetRejectedSamplesPerBatch($batchno);
	$negatives=GetSamplesPerBatchbyresultype($batchno,1);
	$indeter=GetSamplesPerBatchbyresultype($batchno,3);
	$pos=($numsamples-($negatives + $indeter));
	$posi=0;

	 for ($counter = 1; $counter <= $pos; $counter += 1)
	 {
	 	$posres=mysqli_query($this->db_conn, "select COUNT(ID) as pos_with_result_samples from samples where parent='labcode' AND  result >0 ")or die(mysql_error());
		$posresrow = mysqli_fetch_array($posres, mysqli_ASSOC);
		$pos_with_result_samples = $posresrow['pos_with_result_samples'];
		
		if ($pos_with_result_samples ==3)
		{
		$posi=posi+1;
		}
		
	 }

	$withresult=($posi+$indeter+$negatives);
	$no_result_samples = (($numsamples -$withresult ) - $rejected);

	return $no_result_samples;
	}

	//get no of  samples per batch with particular result type
	function GetSamplesPerBatchbyresultype($batchno,$resultype)
	{
	$noresultsamplee = "SELECT COUNT(ID) as with_result_samples FROM samples WHERE  batchno='$batchno' AND result = '$resultype'  AND parentid='0' AND Flag=1 AND repeatt=0";
	$noresultsampleresultt = mysqli_query($this->db_conn, $noresultsamplee) or die(mysql_error());
	$noresultsampleresultroww = mysqli_fetch_array($noresultsampleresultt, mysqli_ASSOC);
	$with_result_samples = $noresultsampleresultroww['with_result_samples'];

	return $with_result_samples  ;
	}

	//get no of  samples per batch with particular final result type  for positives and failed
	function GetSamplesPerBatchbyFinalresultype($batchno,$resultype,$dispatchtype)
	{

	if ($dispatchtype ==0) //samples bein readied for  dispatch
	{
	$noresultsamplee = "SELECT COUNT(ID) as with_result_samples FROM samples WHERE  batchno='$batchno' AND result = '$resultype'  AND parentid >'0' AND `BatchComplete` =2 AND Flag=1";
	$noresultsampleresultt = mysqli_query($this->db_conn, $noresultsamplee) or die(mysql_error());
	$noresultsampleresultroww = mysqli_fetch_array($noresultsampleresultt, mysqli_ASSOC);
	$with_result_samples = $noresultsampleresultroww['with_result_samples'];

	return $with_result_samples  ;
	}
	else if ($dispatchtype ==2) //samples bein readied for  dispatch for negatives
	{
	$noresultsamplee = "SELECT COUNT(ID) as with_result_samples FROM samples WHERE  batchno='$batchno' AND result = '$resultype'  AND parentid >'0' AND `repeatt` =0 AND Flag=1";
	$noresultsampleresultt = mysqli_query($this->db_conn, $noresultsamplee) or die(mysql_error());
	$noresultsampleresultroww = mysqli_fetch_array($noresultsampleresultt, mysqli_ASSOC);
	$with_result_samples = $noresultsampleresultroww['with_result_samples'];

	return $with_result_samples  ;
	}
	else //samples alredy dispatched
	{
	$noresultsamplee = "SELECT COUNT(ID) as with_result_samples FROM samples WHERE  batchno='$batchno' AND result = '$resultype'  AND
	 parentid > '0' AND repeatt=0 AND `BatchComplete` =1 AND Flag=1";
	$noresultsampleresultt = mysqli_query($this->db_conn, $noresultsamplee) or die(mysql_error());
	$noresultsampleresultroww = mysqli_fetch_array($noresultsampleresultt, mysqli_ASSOC);
	$with_result_samples = $noresultsampleresultroww['with_result_samples'];

	return $with_result_samples  ;
	}
	}
	//get max date of test per batch
	function GetMaxdateoftestperbatch($batchno)
	{
	$noresultsamplee = "SELECT MAX(datetested) as 'maxdate' FROM samples WHERE  batchno='$batchno'   ";
	$noresultsampleresultt = mysqli_query($this->db_conn, $noresultsamplee) or die(mysql_error());
	$noresultsampleresultroww = mysqli_fetch_array($noresultsampleresultt, mysqli_ASSOC);
	$maxdate = $noresultsampleresultroww['maxdate'];
	if   ($maxdate  != "0000-00-00")
	{
	$maxdate=date("d-M-Y",strtotime($maxdate));
	}
	else
	{
	$maxdate="";
	}
	return $maxdate  ;
	}
	//get max date of result updated afetr test per batch
	function GetMaxdateupdatedperbatch($batchno)
	{
	$noresultsamplee = "SELECT MAX(datemodified) as 'updateddate' FROM samples WHERE  batchno='$batchno'  ";
	$noresultsampleresultt = mysqli_query($this->db_conn, $noresultsamplee) or die(mysql_error());
	$noresultsampleresultroww = mysqli_fetch_array($noresultsampleresultt, mysqli_ASSOC);
	$updateddate = $noresultsampleresultroww['updateddate'];
	if ($updateddate !="0000-00-00")
	{
	$updateddate=date("d-M-Y",strtotime($updateddate));
	}
	else
	{
	$updateddate="";
	}
	return $updateddate  ;
	}

	//get max date of test per batch
	function GetSampleSentLastinBatch($batchno)
	{
	$noresultsamplee = "SELECT Time_Sent as 'maxdate' FROM  sms_log WHERE  batch_number='$batchno' ORDER BY id DESC LIMIT 0,1 ";
	$noresultsampleresultt = mysqli_query($this->db_conn, $noresultsamplee) or die(mysql_error());
	$noresultsampleresultroww = mysqli_fetch_array($noresultsampleresultt, mysqli_ASSOC);
	$maxdate = $noresultsampleresultroww['maxdate'];
	if   ($maxdate  != "")
	{
	$maxdate=date("d-M-Y h:i:s A",strtotime($maxdate));
	}
	else
	{
	$maxdate="";

	}
	return $maxdate ;
	}

	//get max date of sent
	function GetSampleDeliveredLastinBatch($batchno)
	{
	$noresultsamplee = "SELECT Time_Delivered as 'maxdate' FROM  sms_log WHERE  batch_number='$batchno' ORDER BY id DESC LIMIT 0,1 ";
	$noresultsampleresultt = mysqli_query($this->db_conn, $noresultsamplee) or die(mysql_error());
	$noresultsampleresultroww = mysqli_fetch_array($noresultsampleresultt, mysqli_ASSOC);
	$maxdate = $noresultsampleresultroww['maxdate'];
	if   ($maxdate  != "")
	{
	$maxdate=date("d-M-Y h:i:s A",strtotime($maxdate));
	}
	else
	{
	$maxdate="";

	}
	return $maxdate ;
	}


	//get max ID of sample with restets
	function GetMaxLabIDforRetest($parent)
	{
	$noresultsamplee = "SELECT MAX(ID) as 'maxid' FROM samples WHERE  parentid='$parent'   ";
	$noresultsampleresultt = mysqli_query($this->db_conn, $noresultsamplee) or die(mysql_error());
	$noresultsampleresultroww = mysqli_fetch_array($noresultsampleresultt, mysqli_ASSOC);
	$maxid = $noresultsampleresultroww['maxid'];
	return $maxid;
	}
	//get date batch was dispatched
	function Getbatchdateofdispatch($batchno)
	{
	$noresultsamplee = "SELECT MAX(datedispatched) as 'dateofdispatch' FROM samples WHERE  batchno='$batchno'  ";
	$noresultsampleresultt = mysqli_query($this->db_conn, $noresultsamplee) or die(mysql_error());
	$noresultsampleresultroww = mysqli_fetch_array($noresultsampleresultt, mysqli_ASSOC);
	$dateofdispatch = $noresultsampleresultroww['dateofdispatch'];
	$dateofdispatch=date("d-M-Y",strtotime($dateofdispatch));
	return $dateofdispatch  ;
	}
	//determine total number of batches
	function GetTotalNoBatches($labss)
	{
			$query = "SELECT DISTINCT samples.batchno   FROM samples
						WHERE samples.labtestedin='$labss' and samples.legacy = 0";
	$result = mysqli_query($this->db_conn, $query) or die(mysql_error());
	$numrows = mysqli_num_rows($result);
	return $numrows;
	}
	//determine total number of batches wit complete results 
	function GetTotalCompleteBatches($state,$labss)
	{
		//no lab specified
		if(!$labss) {
			$labss=1;
		}
			$query = "SELECT DISTINCT batchno FROM samples
						WHERE samples.labtestedin='$labss' AND samples.BatchComplete='$state' and receivedstatus !=2 and receivedstatus !=4 and samples.legacy = 0";
	$result = mysqli_query($this->db_conn, $query) or die(mysql_error());
	$numrows = mysqli_num_rows($result);
	return $numrows;
	}



	//determine total number of batches wit complete results 
	function GetRejectedSamplesAwaitingDispatch($labss)
	{
			$query = "SELECT task_id,task, batchno,sample  FROM pendingtasks
						WHERE status=0 AND task=6 AND lab='$labss'";
	$result = mysqli_query($this->db_conn, $query) or die(mysql_error());
	$numrows = mysqli_num_rows($result);
	return $numrows;
	}


	//get results based on sample code
	function GetSampleResult($accessionno)
	{
		$getdate = "SELECT results.Name as 'outcome' from samples,results WHERE samples.result = results.ID AND samples.accessionno='$accessionno'";
			$gotdate = mysqli_query($this->db_conn, $getdate) or die(mysql_error());
			$daterec = mysqli_fetch_array($gotdate);
			$outcome = $daterec['outcome'];
		return $outcome;
	}
	//get received status based on ID
	function GetReceivedStatus($receivedstatus)
	{
		$getdate = "SELECT Name as 'status' from receivedstatus WHERE ID ='$receivedstatus'";
			$gotdate = mysqli_query($this->db_conn, $getdate) or die(mysql_error());
			$daterec = mysqli_fetch_array($gotdate);
			$status = $daterec['status'];
		return $status;
	}

	//determine total number of repeated samples (all parent samples that turned positive n needed retest)
	function GetTotalRepeatParentSamples($labss)
	{
			$query = "SELECT samples.ID,samples.patient,samples.datereceived,samples.spots,samples.datecollected,samples.receivedstatus,samples.facility
						FROM samples,facilitys
						WHERE ((samples.repeatt=1) AND ((samples.parentid='0')OR(samples.parentid IS NULL))) AND samples.facility=facilitys.ID AND facilitys.lab='$labss' 
						ORDER BY samples.ID DESC";
	$result = mysqli_query($this->db_conn, $query) or die(mysql_error());
	$numrows = mysqli_num_rows($result);
	return $numrows;
	}


	//determine total number of confirmatoy tests
	function GetTotalConfirmatoryTests($labss)
	{$reason="Confirmatory PCR at 9 Mths";
			$query = "SELECT *
						FROM samples,facilitys
						WHERE samples.receivedstatus=3 AND samples.reason_for_repeat LIKE '%Confirmatory PCR at 9 Mths%'  AND samples.facility=facilitys.ID AND facilitys.lab='$labss'
						";
	$result = mysqli_query($this->db_conn, $query) or die(mysql_error());
	$numrows = mysqli_num_rows($result);
	return $numrows;
	}

	//determine total number of confirmatoy tests
	function GetTotalRepeatforRejection($labss)
	{
			$query = "SELECT *
						FROM samples,facilitys
						WHERE samples.receivedstatus=3 AND samples.reason_for_repeat LIKE '%Repeat For Rejection%'  AND samples.facility=facilitys.ID AND facilitys.lab='$labss'
						";
	$result = mysqli_query($this->db_conn, $query) or die(mysql_error());
	$numrows = mysqli_num_rows($result);
	return $numrows;
	}

	/*//get received status based on ID
	function GetReceivedStatus($receivedstatus)
	{
		$getdate = "SELECT samples.receivedstatus,receivedstatus.Name as 'status' from samples,receivedstatus WHERE samples.receivedstatus AND receivedstatus.ID AND samples.receivedstatus='$receivedstatus'";
			$gotdate = mysqli_query($this->db_conn, $getdate) or die(mysql_error());
			$daterec = mysqli_fetch_array($gotdate);
			$status = $daterec['status'];
		return $status;
	}
	*///get dsitrct ID
	function GetDistrictID($facility)
	{
		$districtidquery=mysqli_query($this->db_conn, "SELECT district
	            FROM facilitys
	            WHERE  ID='$facility'"); 
				$noticia = mysqli_fetch_array($districtidquery);  
				
				$distid=$noticia['district'];
				return $distid;

	}
	//get distirct code
	function GetDistrictCode($facility)
	{
		$districtidquery=mysqli_query($this->db_conn, "SELECT districts.districtcode as 'dcode'
	            FROM districts,facilitys
	            WHERE  districts.ID=facilitys.district and facilitys.ID='$facility'"); 
				$noticia = mysqli_fetch_array($districtidquery);  
				
				$distid=$noticia['dcode'];
				return $distid;

	}
	//get distrcit name
	function GetDistrictName($distid)
	{
	$districtnamequery=mysqli_query($this->db_conn, "SELECT name 
	            FROM districts
	            WHERE  ID='$distid'"); 
				$districtname = mysqli_fetch_array($districtnamequery);  
				$distname=$districtname['name'];
			return $distname;
	}
	//get province id
	function GetProvid($distid)
	{
	$districtnamequery=mysqli_query($this->db_conn, "SELECT province
	            FROM districts
	            WHERE  ID='$distid'"); 
				$districtname = mysqli_fetch_array($districtnamequery);  
				$provid=$districtname['province'];
				return $provid;
	}
	//get province name
	function GetProvname($provid)
	{
	$provincenamequery=mysqli_query($this->db_conn, "SELECT name 
	            FROM provinces
	            WHERE  ID='$provid'"); 
				$provincename = mysqli_fetch_array($provincenamequery);  
				$provname=$provincename['name'];
				return $provname;
				}
				//determine if sample is a repeat or normal samples
	function getRepeatValue($paroid,$lab)
	{
	$repeatquery=mysqli_query($this->db_conn, "SELECT samples.repeatt as 'repeatt'
	            FROM samples,facilitys
	            WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab' AND samples.ID='$paroid'"); 
				$repeatname = mysqli_fetch_array($repeatquery);  
				$repeatvalue=$repeatname['repeatt'];
				return $repeatvalue;
				}			
		//get the labcode parent id		
	function getParentID($labid,$lab)
	{
	$parentquery=mysqli_query($this->db_conn, "SELECT samples.parentid  as 'parentid'
	            FROM samples
	            WHERE samples.labtestedin='$lab' AND  samples.accessionno='$labid'"); 
				$parentname = mysqli_fetch_array($parentquery);  
				$parentvalue=$parentname['parentid'];
				return $parentvalue;
				}	
			function getWorksheetnoforParentID($labid,$lab)
	{
	$parentquery=mysqli_query($this->db_conn, "SELECT samples.worksheet
	            FROM samples
	            WHERE samples.labtestedin='$lab' AND  samples.accessionno='$labid'"); 
				$parentname = mysqli_fetch_array($parentquery);  
				$worksheetnovalue=$parentname['worksheet'];
				return $worksheetnovalue;
				}		

//get no of repeats for that parent id
	function GetNoofRetests($parentid,$lab){

		$SQL = "SELECT 	COUNT(samples.ID) as 'noofrepeats' 
				
				FROM 	samples 
				
				WHERE 	samples.labtestedin = '$lab'  
				AND 	samples.parentid = '$parentid' ";

		$results=mysqli_query($this->db_conn, $SQL) or die(mysql_error());
		$resultarray=mysqli_fetch_array($results);
		$testedsamples=$resultarray['noofrepeats'];
	
		return $testedsamples;
	}

	//generate new  worksheet no
	function GetNewWorksheetNo() {

	$RES = mysqli_query($this->db_conn, "SELECT MAX(ID) as 'Max' FROM worksheets");

	if(mysqli_num_rows($RES) == 1) {
	$ROW = mysqli_fetch_assoc($RES);
			$worksheetno = $ROW['Max'] + 1;
		}
	return $worksheetno;
	}

	//get total number of samples
	function Gettotalsamples($labss){

		$provincenamequery=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'totalsamples'
	           FROM samples
						WHERE samples.labtestedin='$labss'  and flag=1 and repeatt=0
					
	            "); 
				$provincename = mysqli_fetch_array($provincenamequery);  
				$totalsamples=$provincename['totalsamples'];
				return $totalsamples;
				}
				
		//get total number of worksheets
	function Gettotalworksheets()
	{
	$provincenamequery=mysqli_query($this->db_conn, "SELECT COUNT(ID) as 'worksheets'
	            FROM worksheets
	            "); 
				$provincename = mysqli_fetch_array($provincenamequery);  
				$worksheets=$provincename['worksheets'];
				return $worksheets;
				}
				
				
						
		//get total number of complete worksheets
	function Gettotalcompleteworksheets()
	{
	$provincenamequery=mysqli_query($this->db_conn, "SELECT COUNT(ID) as 'worksheets'
	            FROM worksheets where flag=2
	            "); 
				$provincename = mysqli_fetch_array($provincenamequery);  
				$worksheets=$provincename['worksheets'];
				return $worksheets;
				}
				//get total number of repeat worksheets
	function GettotalRepeatworksheets()
	{
	$provincenamequery=mysqli_query($this->db_conn, "SELECT COUNT(ID) as 'worksheets'
	            FROM worksheets where type=1
	            "); 
				$provincename = mysqli_fetch_array($provincenamequery);  
				$worksheets=$provincename['worksheets'];
				return $worksheets;
				}
				//get total number of pending  worksheets
				
		function GettotalPendingworksheets()
	{
	$provincenamequery=mysqli_query($this->db_conn, "SELECT COUNT(ID) as 'worksheets'
	            FROM worksheets where flag=0
	            "); 
				$provincename = mysqli_fetch_array($provincenamequery);  
				$worksheets=$provincename['worksheets'];
				return $worksheets;
				}
				
				
				function Gettotalworksheetsunderreview()
	{
	$provincenamequery=mysqli_query($this->db_conn, "SELECT COUNT(ID) as 'worksheets'
	            FROM worksheets where flag=2
	            "); 
				$provincename = mysqli_fetch_array($provincenamequery);  
				$worksheets=$provincename['worksheets'];
				return $worksheets;
				}
		//get total no of sampels per worksheet
	function GetSamplesPerworksheet($worksheet)
	{
		$samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE worksheet='$worksheet'
			 ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}

		//get total no of sampels per worksheet for repeat
	function GetRepeatSamplesPerworksheet($worksheet)
	{
		$samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE worksheet='$worksheet' AND 	inrepeatworksheet  = 1
			 ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}
	//get worksheet details
		function getWorksheetDetails($wno)
	{
	    $qury = "SELECT *
	            FROM worksheets
	            WHERE ID= '$wno'";
		$reslt = mysqli_query($this->db_conn, $qury) or die(mysql_error());
	  	  $row    =mysqli_fetch_assoc($reslt);
	    return $row;            
	} 	
	//get sample details
		function getSampleetails($sample)
	{
	    $qury = "SELECT *
	            FROM samples
	            WHERE ID= '$sample'";
		$reslt = mysqli_query($this->db_conn, $qury) or die(mysql_error());
	  	  $row =mysqli_fetch_assoc($reslt);
	    return $row;            
	} 	

	//get sample details
		function getSampledetails($sample)
	{
	    $qury = "SELECT *
	            FROM samples
	            WHERE accessionno= '$sample'";
		$reslt = mysqli_query($this->db_conn, $qury) or die(mysql_error());
	  	  $row =mysqli_fetch_assoc($reslt);
	    return $row;            
	} 	
	//get facility details
		function getFacilityDetails($fcode)
	{
	    $qury = "SELECT *
	            FROM facilitys
	            WHERE ID= '$fcode'";
		$reslt = mysqli_query($this->db_conn, $qury) or die(mysql_error());
	  	  $row    =mysqli_fetch_assoc($reslt);
	    return $row;            
	} 	
	//get samples in batch
		function getBatchDetails($batchno)
	{
	    $qury = "SELECT *
	            FROM samples
	            WHERE batchno= '$batchno'";
		$reslt = mysqli_query($this->db_conn, $qury) or die(mysql_error());
	  	  $row    =mysqli_fetch_assoc($reslt);
	    return $row;            
	}
	//get requisition details
	function getRequisitionDetails($rno)
	{
	    $qury = "SELECT *
	            FROM requisitions
	            WHERE id= '$rno'";
		$reslt = mysqli_query($this->db_conn, $qury) or die(mysql_error());
	  	  $row    =mysqli_fetch_assoc($reslt);
	    return $row;            
	} 	
	//save user details 
	function SaveUser($surname,$oname,$initials,$imagename,$telephone,$postal,$email,$account,$username,$password,$lab,$datecreated)

	{

	$saved = "INSERT INTO 		
	original_users(surname,oname,initials,signature,telephone,postal,email,account,lab,username,password,flag,datecreated)VALUES('$surname','$oname','$initials','$imagename','$telephone','$postal','$email','$account','$lab','$username','$password',1,'$datecreated')";
				$users = @mysqli_query($this->db_conn, $saved) or die(mysql_error());
		return $users;

	}

	//edit user details 
	function UpdateUser($userid,$surname,$oname,$initials,$imagename,$telephone,$postal,$email,$account,$username,$lab,$datemodified)
	{
	IF ($imagename !="")
	{
	$saved = "UPDATE original_users SET surname='$surname',oname='$oname',initials='$initials',signature='$imagename',telephone='$telephone',postal='$postal',email='$email',account='$account',lab='$lab',username='$username',datemodified='$datemodified' WHERE ID='$userid'";
				$users = @mysqli_query($this->db_conn, $saved) or die(mysql_error());
							
	}
	else
	{
	$saved = "UPDATE 		
	original_users SET surname='$surname',oname='$oname',initials='$initials',telephone='$telephone',postal='$postal',email='$email',account='$account',lab='$lab',username='$username',datemodified='$datemodified' WHERE ID='$userid'";
				$users = @mysqli_query($this->db_conn, $saved) or die(mysql_error());

	}
		return $users;

	}
	//save user groups
	function SaveUserGroup($groupname)
	{
	$saved = "INSERT INTO 		
	usergroups(name)VALUES('$groupname')";
				$users = @mysqli_query($this->db_conn, $saved) or die(mysql_error());
		return $users;

	}
	//save menus
	function SaveMenu($menu,$url,$location)
	{
	$saved = "INSERT INTO 		
	menus(name,url,location)VALUES('$menu','$url','$location')";
				$users = @mysqli_query($this->db_conn, $saved) or die(mysql_error());
		return $menus;

	}
	function GetTotalMenus()
	{ 
	$query = "SELECT ID  FROM menus";
	$result = mysqli_query($this->db_conn, $query) or die(mysql_error());
	$numrows = mysqli_num_rows($result);
	return $numrows;
	}
	function GetTotalUserActivity()
	{
	$query = "SELECT TaskID  FROM usersactivity";
	$result = mysqli_query($this->db_conn, $query) or die(mysql_error());
	$numrows = mysqli_num_rows($result);
	return $numrows;
	}
	function GetTotalSingleUserActivity($user)
	{
	$query = "SELECT TaskID  FROM usersactivity where user=$user";
	$result = mysqli_query($this->db_conn, $query) or die(mysql_error());
	$numrows = mysqli_num_rows($result);
	return $numrows;
	}
	//get total users
	function GetTotalUsers()
	{ 
	$query = "SELECT ID  FROM original_users where flag=1";
	$result = mysqli_query($this->db_conn, $query) or die(mysql_error());
	$numrows = mysqli_num_rows($result);
	return $numrows;
	}
	//get rejected reason
	function GetRejectedReason($ID)
	{ 
	$query = "SELECT Name  FROM rejectedreasons where ID='$ID'";
	$result = mysqli_query($this->db_conn, $query) or die(mysql_error());
	$dd=mysqli_fetch_array($result);
	$rejreason=$dd['Name'];
	return $rejreason;
	}


	//get total distrcts
	function GetTotalDistricts()
	{ 
	$query = "SELECT ID  FROM districts where flag=1";
	$result = mysqli_query($this->db_conn, $query) or die(mysql_error());
	$numrows = mysqli_num_rows($result);
	return $numrows;
	}
	//get total facilities
	function GetTotalFacilities()
	{ 
	$query = "SELECT ID  FROM facilitys where Flag=1";
	$result = mysqli_query($this->db_conn, $query) or die(mysql_error());
	$numrows = mysqli_num_rows($result);
	return $numrows;
	}
	//get total samples for repeat
	function GetTotalRepeatSamples()
	{ 
	$query = "select * from samples where parentid IS NOT NULL AND inrepeatworksheet =0";
	$result = mysqli_query($this->db_conn, $query) or die('Error, query failed');
	$row = mysqli_fetch_array($result, mysqli_ASSOC);
	$numrows = $row['numrows'];
	return $numrows;

	}
	//save facility details
	function Savefacility($code,$name,$district,$lab,$postal,$telephone,$otelephone,$fax,$email,$fullname,$contacttelephone,$ocontacttelephone,$contactemail,$hub,$level)
	{
	$saved = "INSERT INTO 		
	facilitys(facilitycode,name,district,lab,physicaladdress,telephone,telephone2,fax,email,contactperson,contacttelephone,contacttelephone2,flag,ContactEmail,hub,level)VALUES('$code','$name','$district','$lab','$postal','$telephone','$otelephone','$fax','$email','$fullname','$contacttelephone','$ocontacttelephone',1,'$contactemail','$hub','$level')";
				$users = @mysqli_query($this->db_conn, $saved) or die(mysql_error());
		return $users;

	}
	//save user activity
	function SaveUserActivity($userid,$task,$tasktime,$patient,$todaysdate)
	{
	$activity = "INSERT INTO 		
	usersactivity(user,task,timetaskdone,patient,dateofentry)VALUES('$userid','$task','$tasktime','$patient','$todaysdate')";
				$useractivity = @mysqli_query($this->db_conn, $activity) or die('error');
					return $useractivity;
	}
	//get account type name
	function GetAccountType($account)
	{
	$userquery=mysqli_query($this->db_conn, "SELECT name FROM usergroups where ID='$account' ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($userquery);
	$grupname=$dd['name'];
	return $grupname;
	}
	function totalrepeatsamples()
	{
	$qury = "select ID,accessionno,patient,batchno,parentid,datereceived, IF(parentid > '0' OR parentid IS NULL, 0, 1) AS isnull  from samples  WHERE Inworksheet=0 AND ((receivedstatus !=2) and (receivedstatus !=4))   AND ((result IS NULL ) OR (result =0 )) AND status =1 AND Flag=1 and parentid > '0' 
				ORDER BY isnull ASC,datereceived ASC,parentid ASC,ID ASC
				";			
				$result = mysqli_query($this->db_conn, $qury) or die(mysql_error());
				$samplesawaitingrepeattest=mysqli_num_rows($result); //no of samples


	return  $samplesawaitingrepeattest;
	}
	//get the user activity by accession no
	function GetUserActivity($accessionno,$monthdateenteredindatabase)
	{
	$userquery=mysqli_query($this->db_conn, "SELECT max(timetaskdone),user as username FROM usersactivity WHERE patient = '$accessionno' and month(dateofentry) = '$monthdateenteredindatabase'")or die(mysql_error()); 
	$dd=mysqli_fetch_array($userquery);
	$grupname=$dd['username'];
	return $grupname;
	}
	//get sample date of testting based on id
	function GetSampleDateofTest($parentid)
	{
	$datequery=mysqli_query($this->db_conn, "SELECT datetested FROM samples where ID='$parentid' ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($datequery);
	$datetested=$dd['datetested'];
	$datetested=date("d-M-Y",strtotime($datetested));
	return $datetested;
	}
	//get sample date of testting based on id
	function GetSampleResultbasedonparentid($parentid)
	{
	$resultquery=mysqli_query($this->db_conn, "SELECT result FROM samples where ID='$parentid' ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($resultquery);
	$outcome=$dd['result'];
	return $outcome;
	}
	//get menu name
	function GetMenuName($menu)
	{
	$menuquery=mysqli_query($this->db_conn, "SELECT name FROM menus where ID='$menu' ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($menuquery);
	$menuname=$dd['name'];
	return $menuname;
	}

	function GetMenuUrl($menu)
	{
	$menuquery=mysqli_query($this->db_conn, "SELECT url FROM menus where ID='$menu' ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($menuquery);
	$menuurl=$dd['url'];
	return $menuurl;
	}



	//get sample result
	function GetResultType($result)
	{
		$result = "SELECT name FROM results WHERE ID = '$result'";
		$getresult = mysqli_query($this->db_conn, $result) or die(mysql_error());
		$resulttype = mysqli_fetch_array($getresult);
		$showresult = $resulttype['name'];
		
	return $showresult;
	}
	//get ID baased on name and table
	function GetIDfromtableandname($names,$tabl)
	{
	$menuquery=mysqli_query($this->db_conn, "SELECT ID FROM $tabl where name='$names' ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($menuquery);
	$ID=$dd['ID'];
	return $ID;
	}
	//get ID baased on name and table
	function GetIDfromprophylaxis($names,$type)
	{
	if ($type==1)
	{
	$menuquery=mysqli_query($this->db_conn, "SELECT ID FROM prophylaxis where name='$names' AND ptype=1 ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($menuquery);
	$ID=$dd['ID'];
	return $ID;
	}
	else if ($type==2)
	{
	$menuquery=mysqli_query($this->db_conn, "SELECT ID FROM prophylaxis where name='$names' AND ptype=2 ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($menuquery);
	$ID=$dd['ID'];
	return $ID;
	}
	}


	function gettotalpendingtasks($acttype)
	{

	//total no of batches awaiting dispatch
	$batchesfordispatch = mysqli_query($this->db_conn, "SELECT task_id,task,batchno
	            FROM pendingtasks
				WHERE status=0 AND task=2
				ORDER BY batchno ASC
				") or die(mysql_error());
	$noofbatches=mysqli_num_rows($batchesfordispatch);
	 
	 //total no of rejected samples awaiting dispatch
	$rejectedsamplesfordispatch = mysqli_query($this->db_conn, "SELECT task_id,task,batchno
	            FROM pendingtasks
				WHERE status=0 AND task=6
				ORDER BY batchno ASC
				") or die(mysql_error());
	 $noofrejsamples=mysqli_num_rows($rejectedsamplesfordispatch);

	 
	//total sampels for repeat
	$samplesforrepeat = mysqli_query($this->db_conn, "SELECT task_id,task,batchno,sample
	            FROM pendingtasks
				WHERE status=0 AND task=3
				ORDER BY batchno ASC
				") or die(mysql_error());
				
	$noofrepeatsamples=mysqli_num_rows($samplesforrepeat);

	//total number of samples awaiting testing
	 $samplesfortest = self::samplesawaitingtests();
	 
	 
	 //total no of worksheets awaiting results update
	$worksheetsforresults = mysqli_query($this->db_conn, "SELECT task_id,task,worksheet
	            FROM pendingtasks
				WHERE status=0 AND task=9
				ORDER BY worksheet ASC
				") or die(mysql_error());
	$noofworksheetswaitingresultupdate=mysqli_num_rows($worksheetsforresults);

	//total no of worksheets awaiting review
	$worksheetsforrreview = mysqli_query($this->db_conn, "SELECT task_id,task,worksheet
	            FROM pendingtasks
				WHERE status=0 AND task=8
				ORDER BY worksheet ASC
				") or die(mysql_error());
	$noofworksheetswaitingreview=mysqli_num_rows($worksheetsforrreview);


	if ($acttype ==5) //lab tech
	{
	$totaltasks=  ( ($noofrepeatsamples + $samplesfortest + $noofworksheetswaitingresultupdate ));
	}
	else if ($acttype ==4)//data clerk 1
	{
	$totaltasks= $noofbatches ;
	}
	else if ($acttype ==1)//data clerk 2
	{
	$batchesforapproval=gettotalpendingbatches(0);
	$totaltasks=  ($noofrejsamples + $batchesforapproval );
	}
	elseif ($acttype ==6)//data clerk 2 
	{
	$totaltasks=  ($noofworksheetswaitingreview);
	}
	return $totaltasks;

	}

	function gettotalpendingbatches($state)
	{

	//total no of batches awaiting approval by data clerk 2
	$batchesforapproval = mysqli_query($this->db_conn, "SELECT DISTINCT(batchno)
	            FROM samples
				WHERE status='$state' AND flag=1
				ORDER BY batchno ASC
				") or die(mysql_error());
	$noofbatches=mysqli_num_rows($batchesforapproval);
	 
	 
	return $noofbatches;

	}
	function gettotalpendingsamplesinbatches($batchno)
	{

	//total no of samples in particular batch awaiting approval by data clerk 2
	$batchesforapproval = mysqli_query($this->db_conn, "SELECT ID
	            FROM samples
				WHERE status='0' AND batchno='$batchno' AND flag=1 and repeatt=0
				
				") or die(mysql_error());
	$noofbatches=mysqli_num_rows($batchesforapproval);
	 
	 
	return $noofbatches;

	}
	function samplesawaitingtests()
	{
	$qury = "select ID,accessionno,patient,batchno,parentid,datereceived, IF(parentid > '0' OR parentid IS NULL, 0, 1) AS isnull  from samples  WHERE Inworksheet=0 AND ((receivedstatus !=2) and (receivedstatus !=4))   AND ((result IS NULL ) OR (result =0 )) AND status =1 AND Flag=1
				ORDER BY isnull ASC,datereceived ASC,parentid ASC,ID ASC
				";			
				$result = mysqli_query($this->db_conn, $qury) or die(mysql_error());
				$samplesawaitingtest=mysqli_num_rows($result); //no of samples


	return  $samplesawaitingtest;
	}
	//get sample Lab ID of  last saved sample record
	function GetLastSampleID($lab)
	{
		$getsampleid = "SELECT samples.ID
	            FROM samples,facilitys
				WHERE  samples.facility=facilitys.ID AND facilitys.lab='$lab'
				ORDER by ID DESC LIMIT 0,1 ";
				$getsample=mysqli_query($this->db_conn, $getsampleid);
				$samplerec=mysqli_fetch_array($getsample);
				$sid=$samplerec['ID'];
				return $sid;
	}
	//get requsition no of last saved requisition record
	function GetLastRequisitionID()
	{
		$getreqid = "SELECT id
	            FROM requisitions
	            ORDER by id DESC LIMIT 1 ";
				$getreq=mysqli_query($this->db_conn, $getreqid);
				$reqrec=mysqli_fetch_array($getreq);
				$rid=$reqrec['ID'];
				return $rid;
	}
	//save requisition
	function SaveRequisition($fcode,$dbs,$ziploc,$dessicants,$rack,$glycline,$humidity,$lancets,$reqform,$comments,$datecreated,$parentid,$disapprovecomments,$ecomments,$requisitiondate,$datemodified)
	{
	if ($parentid =="" && $disapprovecomments=="" &&  $approvecomments=="")

	{
	$parentid="";
	$disapprovecomments="";
	$approvecomments="";
	}
		$saved = "INSERT INTO 		
	requisitions(facility,dbs,ziploc,dessicants,rack,glycline,humidity,lancets,reqform,comments,datecreated,flag,parentid,approvecomments,disapprovecomments,requisitiondate,datemodified)VALUES('$fcode','$dbs','$ziploc','$dessicants','$rack','$glycline','$humidity','$lancets','$reqform','$comments','$datecreated',1,'$parentid','$ecomments','$disapprovecomments','$requisitiondate','$datemodified')";
		$requisitions = mysqli_query($this->db_conn, $saved) or die(mysql_error());
		return $requisitions;
	}
	//get any date
	function GetAnyDateMin()
	{
		$getanydate = "SELECT YEAR(MIN(datetested)) AS lowdate FROM samples WHERE flag=1 AND datetested > 0";
		$anydate = mysqli_query($this->db_conn, $getanydate) or die(mysql_error());
		$dateresult = mysqli_fetch_array($anydate);
		$showdate = $dateresult['lowdate'];
		
	return $showdate;
	}
	function GetReqMin()
	{
		$getanydate = "SELECT YEAR(MIN(datecreated)) AS lowdate FROM requisitions WHERE flag=1 AND datecreated > 0";
		$anydate = mysqli_query($this->db_conn, $getanydate) or die(mysql_error());
		$dateresult = mysqli_fetch_array($anydate);
		$showdate = $dateresult['lowdate'];
		
	return $showdate;
	}
	//get requisition details
	function GetRequisitionInfo($db)
	{
		$req = "SELECT dbs,dessicants,glycline,lancets,reqform,ziploc,rack,humidity,comments,datecreated,requisitiondate,datemodified,parentid,approvecomments FROM requisitions WHERE ID = '$db'";
	$getreq = mysqli_query($this->db_conn, $req) or die(mysql_error());
	$requisition = mysqli_fetch_array($getreq);
		
	return $requisition;
	}
	//update requisition details
	function UpdateRequisition($db,$edbs,$eziploc,$edessicants,$erack,$eglycline,$ehumidity,$elancets,$ereqform,$ecomments,$datemodified)
	{
		$req = "UPDATE requisitions SET dbs='$edbs',ziploc='$eziploc',dessicants='$edessicants',rack='$erack',glycline='$eglycline',humidity='$ehumidity',lancets='$elancets',reqform='$ereqform',comments='$ecomments',datemodified='$datemodified' WHERE id = '$db'";
	$getreq = mysqli_query($this->db_conn, $req) or die(mysql_error());
		
	return $getreq;
	}
	//delete requisition details
	function DeleteRequisition($db,$datemodified)
	{
		$delreq = "UPDATE 		
	requisitions SET flag=0, datemodified='$datemodified' WHERE id = '$db'";
	$deletedreq = mysqli_query($this->db_conn, $delreq) or die(mysql_error());

	return $deletedreq;
	}


	//get date dispatched for rejected sample
	function GetDateDispatchedforRejectedSample($samplecode)
	{
	$noresultsamplee = "SELECT dateupdated FROM pendingtasks WHERE  sample='$samplecode' AND task=6  ";
	$noresultsampleresultt = mysqli_query($this->db_conn, $noresultsamplee) or die(mysql_error());
	$noresultsampleresultroww = mysqli_fetch_array($noresultsampleresultt, mysqli_ASSOC);
	$dateupdated = $noresultsampleresultroww['dateupdated'];
	return $dateupdated;
	}

	//get lab name
	function GetLabNames($lab)
	{

	$facilityquery=mysqli_query($this->db_conn, "SELECT name FROM labs where ID='$lab' ")or die(mysql_error()); 
	$dd=mysqli_fetch_array($facilityquery);
	$labname=$dd['name'];
	return $labname;

	}
	//get approved requisition details
	function GetApprovedRequisitionInfo($db)
	{
		$approvedreq = "SELECT dbs,dessicants,glycline,lancets,ziploc,rack,humidity,comments,datecreated,requisitiondate,datemodified FROM requisitions WHERE parentid = '$db'";
	$appreq = mysqli_query($this->db_conn, $approvedreq) or die(mysql_error());
	$apprequisition = mysqli_fetch_array($appreq);
		
	return $apprequisition;
	}

	//get sample id from auto lab code
	function GetInfantID($labcode)
	{ 
	$query = "select patient from samples where accessionno= '$labcode'";
	$result = mysqli_query($this->db_conn, $query) or die('Error, query failed');
	$row = mysqli_fetch_array($result, mysqli_ASSOC);
	$numrows = $row['patient'];
	return $numrows;

	}
	//get sample id from auto lab code
	function GetNoofspots($labcode)
	{ 
	$query = "select spots from samples where accessionno= '$labcode'";
	$result = mysqli_query($this->db_conn, $query) or die('Error, query failed');
	$row = mysqli_fetch_array($result, mysqli_ASSOC);
	$numrows = $row['spots'];
	return $numrows;

	}

	//get maximum year
	function GetMaxYear()
	{
		$getmaxyear = "SELECT MAX( YEAR( datereceived ) ) AS maximumyear FROM samples WHERE flag =1 ";
		$maxyear = mysqli_query($this->db_conn, $getmaxyear) or die(mysql_error());
		$year = mysqli_fetch_array($maxyear);
		$showyear = $year['maximumyear'];
		
	return $showyear;
	}


	function getfacilitylab($facilitycode)
	{
	$query = "select lab from facilitys  where ID= '$facilitycode' ";
	$result = mysqli_query($this->db_conn, $query) or die('Error, query failed');
	$row = mysqli_fetch_array($result, mysqli_ASSOC);
	$lab = $row['lab'];

	if ($lab==1)
	{
	$labname ="KEMRI Nairobi";
	}
	else if ($lab==2)
	{
	$labname ="CDC Kisumu";

	}
	else if ($lab==3)
	{
	$labname ="Alupe Busia";

	}
	else if ($lab==4)
	{
	$labname ="Walter Reed Kericho";

	}
	else
	{
	$labname ="";

	}
	return $labname;

	}

	function getmonthlytests($facilitycode,$month,$year)
	{

	$samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE facility='$facilitycode' AND MONTH(datetested)='$month'	AND YEAR(datetested)='$year' AND repeatt=0 AND Flag=1";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}

	function getyearlytotals($facilitycode,$year)
	{
	$samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE facility='$facilitycode' AND YEAR(datetested)='$year' AND repeatt=0 AND Flag=1";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}

	function getyearlyrejected($facilitycode,$year)
	{
	$samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE facility='$facilitycode' AND YEAR(datereceived)='$year' AND receivedstatus=2 AND Flag=1 ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}

	function gettotaltestsperresult($facilitycode,$resultype,$year)
	{
	$samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE facility='$facilitycode' AND YEAR(datetested)='$year' AND result='$resultype' AND repeatt=0 AND Flag=1";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}

	function gettotaltestsperresultpermonth($facilitycode,$resultype,$month,$year)
	{

	$samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE facility='$facilitycode' AND YEAR(datetested)='$year' AND MONTH(datetested)='$month' AND  result='$resultype' AND repeatt=0 AND Flag=1";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}


	function allrejectedsamples($lab)
	{
	$samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID  AND samples. receivedstatus=2 AND facilitys.lab='$lab'";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}


	function getparentsampleresult($paroid,$lab)
	{
	$strQuery=mysqli_query($this->db_conn, "SELECT samples.result as 'outcome' FROM samples WHERE samples.labtestedin='$lab'  AND samples.accessionno='$paroid' ")or die(mysql_error());
	$resultarray=mysqli_fetch_array($strQuery);
	$parentresult=$resultarray['outcome'];
	return $parentresult;
	}
	// lab reports functions
	/*
	*
	*
	*
	*/
	// weekly tests by result type
	function weeklytestsbyresult($lab,$startdate,$enddate,$resultype)
	{
	$samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples WHERE samples.labtestedin='$lab' AND samples.datetested BETWEEN '$startdate' AND '$enddate' AND samples.result='$resultype' and samples.flag=1 ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}
	//total tests weekly
	function totalweeklytests($lab,$startdate,$enddate)
	{
	$samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples WHERE samples.labtestedin='$lab' AND samples.datetested BETWEEN '$startdate' AND '$enddate' and samples.flag=1 ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}

	// weekly tests rejected
	function weeklyrejectedsamples($lab,$startdate,$enddate)
	{
	$samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples WHERE samples.labtestedin='$lab' AND samples.datereceived BETWEEN '$startdate' AND '$enddate' AND samples.receivedstatus=2";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}
	// weekly kits used
	function weeklykitsused($lab,$startdate,$enddate)
	{
	$samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM worksheets WHERE  lab='$lab' AND daterun BETWEEN '$startdate' AND '$enddate' ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_kits = $samplerow['num_kits'];
		
	return $num_kits ;
	}
	// weekly kits wasted
	function weeklykitswasted($lab,$startdate,$enddate)
	{
	$samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM kits_wasted WHERE  lab='$lab' AND daterun BETWEEN '$startdate' AND '$enddate' ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_kits = $samplerow['num_kits'];
		
		return $num_kits ;
	}

	// monthly tests by result type
	function monthlytestsbyresult($lab,$month,$resultype,$year)
	{

	$samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND MONTH(samples.datetested)=  '$month' AND YEAR(samples.datetested)=  '$year'  AND samples.result='$resultype' AND samples.Flag=1";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;




	}
	//total tests monthly
	function totalmonthlytests($lab,$month,$year)
	{
	$samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND MONTH(samples.datetested)=  '$month' AND YEAR(samples.datetested)=  '$year' ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}
	// monthly tests rejected
	function monthlyrejectedsamples($lab,$month,$year)
	{
	$samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND MONTH(samples.datereceived)=  '$month' AND YEAR(samples.datereceived)= '$year'  AND samples.receivedstatus=2";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}


	// monthly kits used
	function monthlykitsused($lab,$month,$year)
	{
	$samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM worksheets WHERE  lab='$lab' AND MONTH(daterun)= '$month'  AND YEAR(daterun)= '$year' ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_kits = $samplerow['num_kits'];
		
	return $num_kits ;
	}

	// monthly kits wasted
	function monthlykitswasted($lab,$month,$year)
	{
	$samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM kits_wasted WHERE  lab='$lab' AND MONTH(daterun)= '$month'  AND YEAR(daterun)= '$year' ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_kits = $samplerow['num_kits'];
		
	return $num_kits ;
	}
	// yearly tests by result type
	function yearlytestsbyresult($lab,$year,$resultype)
	{
	$samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND YEAR(samples.datetested)=  '$year'  AND samples.result='$resultype'";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}
	//total tests yearly
	function totalyearlytests($lab,$year)
	{
	$samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab'  AND YEAR(samples.datetested)=  '$year'  ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}
	// yearly tests rejected
	function yearlyrejectedsamples($lab,$year)
	{
	$samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND YEAR(samples.datetested)=  '$year'  AND samples.receivedstatus=2";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}

	// yearly kits used
	function yearlykitsused($lab,$year)
	{
	$samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM worksheets WHERE  lab='$lab'  AND YEAR(daterun)= '$year' ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_kits = $samplerow['num_kits'];
		
	return $num_kits ;
	}

	// yearly kits wasted
	function yearlykitswasted($lab,$year)
	{
	$samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM kits_wasted WHERE  lab='$lab'  AND YEAR(daterun)= '$year' ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_kits = $samplerow['num_kits'];
		
	return $num_kits ;
	}

	// periodic tests by result type
	function periodictestsbyresult($lab,$quarterly,$year,$resultype)
	{
			if ($quarterly == 1 ) //january - March
			{
			$startmonth=1;
			$endmonth=3;
			}
			else if ($quarterly == 2 )
			{
			$startmonth=4;
			$endmonth=6;
			}
			else if ($quarterly == 3 )
			{
			$startmonth=7;
			$endmonth=9;
			}
			else if ($quarterly == 4 )
			{
			$startmonth=10;
			$endmonth=12;
			}
	$samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND MONTH(samples.datetested) BETWEEN '$startmonth' AND '$endmonth' AND YEAR(samples.datetested)=  '$year'  AND samples.result='$resultype'";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}
	//total tests yearly
	function totalperiodictests($lab,$quarterly,$year)
	{
	if ($quarterly == 1 ) //january - March
			{
			$startmonth=1;
			$endmonth=3;
			}
			else if ($quarterly == 2 )
			{
			$startmonth=4;
			$endmonth=6;
			}
			else if ($quarterly == 3 )
			{
			$startmonth=7;
			$endmonth=9;
			}
			else if ($quarterly == 4 )
			{
			$startmonth=10;
			$endmonth=12;
			}
	$samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND MONTH(samples.datetested) BETWEEN '$startmonth' AND '$endmonth' AND YEAR(samples.datetested)=  '$year'  ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}


	// periodic tests by result type
	function periodicrejectedsamples($lab,$quarterly,$year)
	{
			if ($quarterly == 1 ) //january - March
			{
			$startmonth=1;
			$endmonth=3;
			}
			else if ($quarterly == 2 )
			{
			$startmonth=4;
			$endmonth=6;
			}
			else if ($quarterly == 3 )
			{
			$startmonth=7;
			$endmonth=9;
			}
			else if ($quarterly == 4 )
			{
			$startmonth=10;
			$endmonth=12;
			}
	$samplequery = "SELECT COUNT(samples.ID) as num_samples FROM samples,facilitys WHERE samples.facility=facilitys.ID   AND facilitys.lab='$lab' AND MONTH(samples.datetested) BETWEEN '$startmonth' AND '$endmonth' AND YEAR(samples.datetested)=  '$year'  AND samples.receivedstatus=2";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
	return $num_samples ;
	}

	// periodic kits used
	function periodickitsused($lab,$quarterly,$year)
	{

	if ($quarterly == 1 ) //january - March
			{
			$startmonth=1;
			$endmonth=3;
			}
			else if ($quarterly == 2 )
			{
			$startmonth=4;
			$endmonth=6;
			}
			else if ($quarterly == 3 )
			{
			$startmonth=7;
			$endmonth=9;
			}
			else if ($quarterly == 4 )
			{
			$startmonth=10;
			$endmonth=12;
			}
	$samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM worksheets WHERE  lab='$lab'  AND MONTH(daterun) BETWEEN '$startmonth' AND '$endmonth' AND YEAR(daterun)=  '$year' ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_kits = $samplerow['num_kits'];
		
	return $num_kits ;
	}

	// periodic kits wasted
	function periodickitswasted($lab,$quarterly,$year)
	{

	if ($quarterly == 1 ) //january - March
			{
			$startmonth=1;
			$endmonth=3;
			}
			else if ($quarterly == 2 )
			{
			$startmonth=4;
			$endmonth=6;
			}
			else if ($quarterly == 3 )
			{
			$startmonth=7;
			$endmonth=9;
			}
			else if ($quarterly == 4 )
			{
			$startmonth=10;
			$endmonth=12;
			}
	$samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM kits_wasted WHERE  lab='$lab'  AND MONTH(daterun) BETWEEN '$startmonth' AND '$endmonth' AND YEAR(daterun)=  '$year' ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_kits = $samplerow['num_kits'];
		
	return $num_kits ;
	}



	function getWorkingDays($startDate,$endDate,$holidays){


	    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
	    //We add one to inlude both dates in the interval.
	    $days = (strtotime($endDate) - strtotime($startDate)) / 86400 + 1;

	    $no_full_weeks = floor($days / 7);

	    $no_remaining_days = fmod($days, 7);

	    //It will return 1 if it's Monday,.. ,7 for Sunday
	    $the_first_day_of_week = date("N",strtotime($startDate));

	    $the_last_day_of_week = date("N",strtotime($endDate));
	    // echo              $the_last_day_of_week;
	    //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
	    //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
	    if ($the_first_day_of_week <= $the_last_day_of_week){
	        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
	        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
	    }

	    else{
	        if ($the_first_day_of_week <= 6) {
	        //In the case when the interval falls in two weeks, there will be a Sunday for sure
	            $no_remaining_days--;
	        }
	    }

	    //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
	//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
	   $workingDays = $no_full_weeks * 5;
	    if ($no_remaining_days > 0 )
	    {
	      $workingDays += $no_remaining_days;
	    }

	    //We subtract the holidays
	/*    foreach($holidays as $holiday){
	        $time_stamp=strtotime($holiday);
	        //If the holiday doesn't fall in weekend
	        if (strtotime($startDate) <= $time_stamp && $time_stamp <= strtotime($endDate) && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
	            $workingDays--;
	    }*/

	    return $workingDays;
	} 

	/**
	*
	*patna report functions
	*/
	//get total number of particular result type for particular facility
	function Getfacilityresultcountpequarter($facility,$resultype,$quarterly,$quarteryear)
	{
		if ($quarterly == 1 ) //january - March
			{
			$startmonth=1;
			$midmonth=2;
			$endmonth=3;
			}
			else if ($quarterly == 2 )
			{
			$startmonth=4;
			$midmonth=5;
			$endmonth=6;
			}
			else if ($quarterly == 3 )
			{
			$startmonth=7;
			$midmonth=8;
			$endmonth=9;
			}
			else if ($quarterly == 4 )
			{
			$startmonth=10;
			$midmonth=11;
			$endmonth=12;
			}
			//month 1
			$strQuery=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result='$resultype' AND YEAR(samples.datetested)='$quarteryear' AND MONTH(samples.datetested) = '$startmonth'  AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
			$resultarray=mysqli_fetch_array($strQuery);
			$TotalCount=$resultarray['TotalCount'];
			
			//month 2
			$strQuery2=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result='$resultype' AND YEAR(samples.datetested)='$quarteryear' AND MONTH(samples.datetested) = '$midmonth'  AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
			$resultarray2=mysqli_fetch_array($strQuery2);
			$TotalCount2=$resultarray2['TotalCount'];
			
			//month 3
			$strQuery3=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result='$resultype' AND YEAR(samples.datetested)='$quarteryear' AND MONTH(samples.datetested) = '$endmonth'  AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
			$resultarray3=mysqli_fetch_array($strQuery3);
			$TotalCount3=$resultarray3['TotalCount'];
			
			//TOTAL 
			$strQuery4=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result='$resultype' AND YEAR(samples.datetested)='$quarteryear' AND MONTH(samples.datetested) BETWEEN  '$startmonth'  AND '$endmonth'  AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
			$resultarray4=mysqli_fetch_array($strQuery4);
			$TotalCount4=$resultarray4['TotalCount'];


			
			return array($TotalCount, $TotalCount2 ,$TotalCount3,$TotalCount4); 
		
	}


	//get total number of tests for particular facility
	function Getfacilitytestscountperquarter($facility,$quarterly,$quarteryear)
	{
		if ($quarterly == 1 ) //january - March
			{
			$startmonth=1;
			$midmonth=2;
			$endmonth=3;
			}
			else if ($quarterly == 2 )
			{
			$startmonth=4;
			$midmonth=5;
			$endmonth=6;
			}
			else if ($quarterly == 3 )
			{
			$startmonth=7;
			$midmonth=8;
			$endmonth=9;
			}
			else if ($quarterly == 4 )
			{
			$startmonth=10;
			$midmonth=11;
			$endmonth=12;
			}
			//month 1
			$strQuery=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result > 0 AND YEAR(samples.datetested)='$quarteryear' AND MONTH(samples.datetested) = '$startmonth'  AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
			$resultarray=mysqli_fetch_array($strQuery);
			$TotalCount=$resultarray['TotalCount'];
			
			//month 2
			$strQuery2=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result > 0 AND YEAR(samples.datetested)='$quarteryear' AND MONTH(samples.datetested) = '$midmonth'  AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
			$resultarray2=mysqli_fetch_array($strQuery2);
			$TotalCount2=$resultarray2['TotalCount'];
			
			//month 3
			$strQuery3=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result > 0 AND YEAR(samples.datetested)='$quarteryear' AND MONTH(samples.datetested) = '$endmonth'  AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
			$resultarray3=mysqli_fetch_array($strQuery3);
			$TotalCount3=$resultarray3['TotalCount'];
			
			//TOTAL 
			$strQuery4=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result > 0 AND YEAR(samples.datetested)='$quarteryear' AND MONTH(samples.datetested) BETWEEN  '$startmonth' AND '$endmonth'  AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
			$resultarray4=mysqli_fetch_array($strQuery4);
			$TotalCount4=$resultarray4['TotalCount'];
			
			return array($TotalCount, $TotalCount2 ,$TotalCount3,$TotalCount4); 
			
		
		
	}


	//get total number of tests for particular facility
	function Getfacilityrejectedcountperquarter($facility,$quarterly,$quarteryear)
	{

	if ($quarterly == 1 ) //january - March
			{
			$startmonth=1;
			$midmonth=2;
			$endmonth=3;
			}
			else if ($quarterly == 2 )
			{
			$startmonth=4;
			$midmonth=5;
			$endmonth=6;
			}
			else if ($quarterly == 3 )
			{
			$startmonth=7;
			$midmonth=8;
			$endmonth=9;
			}
			else if ($quarterly == 4 )
			{
			$startmonth=10;
			$midmonth=11;
			$endmonth=12;
			}
			//month 1
			$strQuery=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.receivedstatus =2 AND YEAR(samples.datereceived)='$quarteryear' AND MONTH(samples.datereceived)= '$startmonth'  AND samples.repeatt=0 AND 	samples.Flag=1 ")or die(mysql_error());
			$resultarray=mysqli_fetch_array($strQuery);
			$rej=$resultarray['TotalCount'];
			
			//month 2
			$strQuery2=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.receivedstatus =2 AND YEAR(samples.datereceived)='$quarteryear' AND MONTH(samples.datereceived) = '$midmonth'  AND samples.repeatt=0 AND 	samples.Flag=1")or die(mysql_error());
			$resultarray2=mysqli_fetch_array($strQuery2);
			$rej2=$resultarray2['TotalCount'];
			
			//month 3
			$strQuery3=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.receivedstatus =2 AND YEAR(samples.datereceived)='$quarteryear' AND MONTH(samples.datereceived) = '$endmonth'  AND samples.repeatt=0 AND 	samples.Flag=1")or die(mysql_error());
			$resultarray3=mysqli_fetch_array($strQuery3);
			$rej3=$resultarray3['TotalCount'];
			
			//TOTAL 
			//total rejected
			$strQuery4=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.receivedstatus =2 AND YEAR(samples.datereceived)='$quarteryear' AND MONTH(samples.datereceived) BETWEEN '$startmonth' AND '$endmonth' AND samples.repeatt=0 AND 	samples.Flag=1 ")or die(mysql_error());
			$resultarray4=mysqli_fetch_array($strQuery4);
			$TotalRejcount=$resultarray4['TotalCount'];
					
			return array($rej, $rej2 ,$rej3,$TotalRejcount); 
			

	}



	//get total number of tests for particular facility
	function Getfacilitytestscount($facility,$month,$yea)
	{
		if ($month !=0)
		{
			$strQuery=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result > 0 AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
			$resultarray=mysqli_fetch_array($strQuery);
			$TotalCount=$resultarray['TotalCount'];
			return $TotalCount;
		}
		else
		{
			$strQuery=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result > 0 AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND 	samples.Flag=1 ")or die(mysql_error());
			$resultarray=mysqli_fetch_array($strQuery);
			$TotalCount=$resultarray['TotalCount'];
			return $TotalCount;
		}
	}

	//get total number of tests for particular facility
	function Getfacilityrejectedcount($facility,$month,$yea)
	{
		if ($month !=0)
		{
			$strQuery=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.receivedstatus =2 AND YEAR(samples.datereceived)='$yea'  AND MONTH(samples.datereceived)='$month' AND samples.repeatt=0 AND 	samples.Flag=1 ")or die(mysql_error());
			$resultarray=mysqli_fetch_array($strQuery);
			$TotalCount=$resultarray['TotalCount'];
			return $TotalCount;
		}
		else
		{
		
			$strQuery=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.receivedstatus =2 AND YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND 	samples.Flag=1 ")or die(mysql_error());
			$resultarray=mysqli_fetch_array($strQuery);
			$TotalCount=$resultarray['TotalCount'];
			return $TotalCount;
		}
	}


	//get total number of particular result type for particular facility
	function Getfacilityresultcount($facility,$resultype,$month,$yea)
	{
		if ($month !=0)
		{
			$strQuery=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result='$resultype' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
			$resultarray=mysqli_fetch_array($strQuery);
			$TotalCount=$resultarray['TotalCount'];
			return $TotalCount;
		}
		else
		{
			$strQuery=mysqli_query($this->db_conn, "SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result='$resultype' AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND 	samples.Flag=1 ")or die(mysql_error());
			$resultarray=mysqli_fetch_array($strQuery);
			$TotalCount=$resultarray['TotalCount'];
			return $TotalCount;
		}
	}

	/**

	**


	**

	**

	kits consumption n procrement functions

	**
	*/
	//determine if procurement report fro specified month/year has been submitted
	function GetProcurementReportStatus($month,$year,$lab)
	{
	$squery=mysqli_query($this->db_conn, "SELECT COUNT(ID) as 'submission' from procurement where month='$month' and year='$year' and lab='$lab' and submitted=1")or die(mysql_error()); ; 
				$ss = mysqli_fetch_array($squery); 
				$reportsubmitted=$ss['submission'];
				
			return $reportsubmitted;
	}





	function GetConsumablesTotalTests($month,$year,$lab,$type)
	{
	//1=total tets, 2-new samples , 3-repeat samples , 4-total controls


		if ($type ==1 ) //ALL TESST
		{
				$samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE labtestedin='$lab' AND  MONTH(datetested)='$month'  AND YEAR(datetested)='$year' AND Flag=1 AND result > 0 ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
			return $num_samples ;
		}
		else if ($type ==2 ) //new samples
		{
				$samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE labtestedin='$lab' AND  MONTH(datetested)='$month'  AND YEAR(datetested)='$year' AND Flag=1 AND result > 0 AND parentid='0'";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
			return $num_samples ;
		}
		else if ($type ==3 )//repeatt
		{
			$samplequery = "SELECT COUNT(ID) as num_samples FROM samples WHERE labtestedin='$lab' AND  MONTH(datetested)='$month'  AND YEAR(datetested)='$year' AND Flag=1 AND result > 0 AND parentid > 0";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_samples = $samplerow['num_samples'];
		
			return $num_samples ;
		}
		

	}

	function GetConsumablesTotalKits($month,$year,$lab)
	{
	$samplequery = "SELECT COUNT( DISTINCT HIQCAPNo) as num_kits FROM worksheets WHERE  lab='$lab' AND MONTH(daterun)='$month' AND YEAR(daterun)='$year'  ";
			$sampleresult = mysqli_query($this->db_conn, $samplequery) or die(mysql_error());
			$samplerow = mysqli_fetch_array($sampleresult, mysqli_ASSOC);
			$num_kits = $samplerow['num_kits'];
		
	return $num_kits ;
	}


	function getlastprocurementid($lab)
	{
	$getmotherid = "SELECT ID
	            FROM procurement
					WHERE  lab='$lab'
	            ORDER by ID DESC LIMIT 1 ";
				$getmum=mysqli_query($this->db_conn, $getmotherid);
				$mumrec=mysqli_fetch_array($getmum);
				$mid=$mumrec['ID'];
				return $mid;
	}

	function getmindatecollectedinbatch($lab,$batchno)
	{
	$getmotherid = "SELECT MIN(datecollected) as 'mindate'
	            FROM samples
					WHERE  labtestedin='$lab' and batchno='$batchno' and flag=1 and status=1 and repeatt=0
	            ";
				$getmum=mysqli_query($this->db_conn, $getmotherid);
				$mumrec=mysqli_fetch_array($getmum);
				$mindate=$mumrec['mindate'];
				
				if  (($mindate !="0000-00-00") && ($mindate !='1970-01-01') && ($mindate !=''))
		{
			$leastdate=date("d-M-Y",strtotime($mindate));
		}
		else
		{
		$leastdate="";
		}
				
				return $leastdate;
	}

	//get number of samples added, deleted, dispatched by user x in a certain month
	function GetMonthlyUserActivity($task,$systemmonth,$user)
	{
		$userqury = mysqli_query($this->db_conn, "select count(patient) as nums from usersactivity where task = '$task' and month(dateofentry) = '$systemmonth' and user = '$user'") or die(mysql_error());
		$userresult = mysqli_fetch_array($userqury); 
		$entrys = $userresult['nums'];
		return $entrys;
	}
	//get totla batches for dispatch depending on ser type
	function gettotalbatchesfordispatch($accttype,$labss)
	{
	if ($accttype ==4)//data clerk
	{
	 $qury = "SELECT DISTINCT samples.batchno
	   			 FROM samples
				WHERE samples.labtestedin='$labss'
				AND samples.BatchComplete=1 AND resultsprinted=0
				ORDER BY datedispatched DESC,batchno DESC
				";
				
				$result = mysqli_query($this->db_conn, $qury) or die(mysql_error()); //for main display
				
	$totalbatches=mysqli_num_rows($result);
	}
	elseif ($accttype ==5) //lab tech
	{
	 $qury = "SELECT DISTINCT samples.batchno
	            FROM samples
				WHERE samples.labtestedin='$labss'
				AND samples.BatchComplete=2  and receivedstatus !=2 and receivedstatus !=4 order by batchno ASC
				";
				
				$result = mysqli_query($this->db_conn, $qury) or die(mysql_error()); //for main display
				
	$totalbatches=mysqli_num_rows($result);

	}
	return $totalbatches;
	}


	//get mothers entry point ID
	function GetPrintedPMTCTCode($pmtctcode)
	{

		$getmother = "SELECT printingID  FROM prophylaxis WHERE ID='$pmtctcode'";
			$gotmother = mysqli_query($this->db_conn, $getmother) or die(mysql_error());
			$motherrec = mysqli_fetch_array($gotmother);
			$printingID = $motherrec['printingID'];
			return $printingID;
	}


	//////////////TRINITY'S FUNCTIONS
	function getdatedispatchedfromfacility($lab,$batchno)
	{
	$dpdate = "SELECT distinct datedispatchedfromfacility FROM samples WHERE batchno='$batchno'";
				$getdate=mysqli_query($this->db_conn, $dpdate);
				$daterec=mysqli_fetch_array($getdate);
				$datedispatchedfromfacility = $daterec['datedispatchedfromfacility'];
				
		if  (($datedispatchedfromfacility !="0000-00-00") && ($datedispatchedfromfacility !='1970-01-01'))
		{
			$leastdate1=date("d-M-Y",strtotime($datedispatchedfromfacility));
		}
		else
		{
		$leastdate1="";
		}
				
				return $leastdate1;
	}
	//..get the mother's telephone number
	function GetCaregiverphn($mid)
	{
		$getmother2 = "SELECT caregiverphn as 'caregiverphn' FROM mothers WHERE ID='$mid'";
			$gotmother2 = mysqli_query($this->db_conn, $getmother2) or die(mysql_error());
			$motherrec2 = mysqli_fetch_array($gotmother2);
			$caregiverphn = $motherrec2['caregiverphn'];
			return $caregiverphn;
	}
	//get last patientautoid to indicate on samples table
	function GetLastPatientID($labss)
	{
		$getpid = "SELECT patients.AutoID
	            FROM patients
					WHERE  patients.labtestedin='$labss'
	            ORDER by AutoID DESC LIMIT 1 ";
				$getp=mysqli_query($this->db_conn, $getpid);
				$prec=mysqli_fetch_array($getp);
				$pid=$prec['AutoID'];
				return $pid;
	}

	//get total distrcts
	function GetTotalSplitDistricts()
	{ 
	$query = "SELECT ID FROM districts where split=1";
	$result = mysqli_query($this->db_conn, $query) or die(mysql_error());
	$numrows = mysqli_num_rows($result);
	return $numrows;
	}


	function getfacilitiesbydistrict($districtcode)
	{
	$sql=mysqli_query($this->db_conn, "select ID as totalfacilities from facilitys where district='$districtcode' and flag=1") or die(mysql_error());
	$numrows=mysqli_num_rows($sql);
	return $numrows;
	}


}