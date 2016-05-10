<?php
class budgetSMS {

	// User parameters
	var $username;
	var $userid;
	var $handle;
	
	// SMS parameters, mandatory
	var $msg;
	var $to;
	var $from;
	
	// SMS parameters, optional
	var $customid;
	
	// API resultcode or errorcode
	var $result;
	
	var $baseurl;
	
    function BudgetSMS($username, $userid, $handle) {
    	$this->username = $username;
    	$this->userid = $userid;
    	$this->handle = $handle;
    	
    	$this->baseurl = 'http://www.budgetsms.net/';
    }
    
    function sendSMS() {
    	$url = $this->baseurl.'api/sendsms_utf8/';
	    	$url .= '?username='.$this->username;
	    	$url .= '&userid='.$this->userid;
	    	$url .= '&handle='.$this->handle;
			$url .= '&to='.$this->to;
	    	$url .= '&msg='.rawurlencode($this->msg);
	    	$url .= '&from='.rawurlencode($this->from);

	    if ($this->customid!='') {
	    	$url .= '&customid='.rawurldecode($this->customid);
	    }
	    
    	$ret = file_get_contents($url);
    	$send = explode(" ",$ret);
    	
    	if ($send[0] == 'OK') {
    		$this->result = $send[1];
    		return true;
    	} else {
    		// Sending failed.
    		// Handle error in function error
    		$this->result = $send[1];
    		return false;
    	}
    }
    
    function checkOperator() {
    	$url = $this->baseurl.'api/checkoperator';
    		$url .= '?username='.$this->username;
	    	$url .= '&userid='.$this->userid;
	    	$url .= '&handle='.$this->handle;
			$url .= '&check='.$this->to;
			
    	$ret = file_get_contents($url);
    	
    	if (substr($ret, 0, 2) == 'OK') {
    		$arr = explode(':', $ret);
    		$check['operatorid'] = $arr[1];
    		$check['operatorname'] = $arr[2];
    		$check['cost'] = $arr[3];
    		$this->result = $check;
    		return true;
    	} else {
    		// Operator check failed.
    		// Handle error in function error
    		$send = explode(' ', $ret);
    		$this->result = $send[1];
    		return false;
    	}			
    }
    
    function checkCredit() {
    	$url = $this->baseurl.'api/checkcredit';
    		$url .= '?username='.$this->username;
	    	$url .= '&userid='.$this->userid;
	    	$url .= '&handle='.$this->handle;
			
    	$ret = file_get_contents($url);
    	$send = explode(" ",$ret);
    	
    	if ($send[0] == 'OK') {
    		$this->result = $send[1];
    		return true;
    	} else {
    		// Credit check failed.
    		// Handle error in function error
    		$this->result = $send[1];
    		return false;
    	}			
    }
    
    function processDLR() {
    	$ret['id'] = $_GET['id'];
    	$ret['status'] = $_GET['status'];
    	$ret['date'] = $_GET['date'];
    	$ret['customid'] = $_GET['customid'];
    	
    	return $ret;
    }
    
    function error($code) {
   		
   		if (substr($code, 0, 1)!='9') {
	   		$errors[1001] = 'Not enough credits to send messages';
	   		$errors[1002] = 'Identification failed. Wrong credentials';
	   		$errors[1003] = 'Account not active, contact BudgetSMS';
	   		$errors[1004] = 'This IP address is not added to this account. No access to the API';
	   		$errors[1005] = 'No handle provided';
	   		$errors[1006] = 'No UserID provided';
	   		$errors[1007] = 'No Username provided';
	   		$errors[2001] = 'SMS message text is empty';
	   		$errors[2002] = 'SMS numeric senderid can be max. 16 numbers';
	   		$errors[2003] = 'SMS alphanumeric sender can be max. 12 characters';
	   		$errors[2004] = 'SMS senderid is empty';
	   		$errors[2005] = 'Destination number is to short';
	   		$errors[2006] = 'Destination is not numeric';
	   		$errors[2007] = 'Destination is empty';
	   		$errors[2008] = 'SMS text is not OK (check encoding?)';
	   		$errors[2009] = 'Problem with the parameters. (check all mandatory params, encoding, etc)';
	   		$errors[2010] = 'Destination number is invalidly formatted';
	   		$errors[2011] = 'Destination is invalid';
	   		$errors[2012] = 'SMS message text is too long';
	   		$errors[2013] = 'SMS message is invalid';
	   		$errors[2014] = 'SMS CustomID is used before';
	   		$errors[2015] = 'Charset problem';
	   		$errors[3001] = 'No route to destination. Contact BudgetSMS for possible solutions';
	   		$errors[3002] = 'No routes are setup. Contact BudgetSMS for a route setup';
	   		$errors[3003] = 'Invalid destination. Check international mobile number formatting';
	   		$errors[4001] = 'System error, related to customID';
	   		$errors[4002] = 'System error, temporary issue. Try resubmitting in 2 to 3 minutes';
	   		$errors[4003] = 'System error, temporary issue';
	   		$errors[4004] = 'System error, temporary issue. Contact BudgetSMS';
	   		$errors[4005] = 'System error, permanent';
	   		$errors[4006] = 'Gateway not reachable';
	   		$errors[5001] = 'Send error, Contact BudgetSMS with the send details';
	   		$errors[5002] = 'Wrong SMS type';
	   		$errors[5003] = 'Wrong operator';
	   		$errors[6001] = 'Unknown error';
	   		
	   		return $errors[$code];
   		} else {
   			return 'Uplink provider error. Contact BudgetSMS with the code for possible solutions';
   		}
   	}
}

/**
* 
*/
class TextMsg extends BudgetSMS
{

    function __construct($username, $userid, $handle)
    {
        parent::__construct($username, $userid, $handle);
    }
}
?>