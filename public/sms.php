<?php
include('TextMsg.php');
 

$userID = '9786';
$userName = 'tornado';
$userHandle = '419fef41a92fbd6c64eccf90e830fbd7';


$SMS = new TextMsg($userName, $userID, $userHandle);


$SMS->msg = 'This is test #10';
$SMS->to = '256776111186';
$SMS->from = '0701000314';


$sms_sent = $SMS->sendSMS();


if ( $sms_sent ) {
	echo 'Sending SMS successful, SMSid: '.$SMS->result;
} else {
	echo 'Sending SMS failed, errorcode: '.$SMS->result.'<br>'.$SMS->error($SMS->result);
}




















// $url = 'http://www.budgetsms.net/api/checksms';
// $url .= '?username='.$SMS->username;
// $url .= '&userid=' . $SMS->userid;
// $url .= '&handle=' . $SMS->handle;
// $url .= '&smsid=' .  $SMS->result;

		
// $ret = file_get_contents($url);
// $send = explode(" ",$ret);

// var_dump($send);
