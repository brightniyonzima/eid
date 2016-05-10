<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsDlr extends Migration {

	public function up(){}
	public function down(){}
}


// ----------------------


// 0 Message is sent, no status yet (default)
// 1 Message is delivered
// 2 Message is not sent
// 3 Message delivery failed
// 4 Message is sent
// 5 Message expired
// 6 Message has a invalid destination address
// 7 SMSC error, message could not be processed
// 8 Message is not allowed
// 11 Message status unknown, usually after 24 hours without status update SMSC
// 12 Message status unknown, SMSC received unknown status code
// 13 Message status unknown, no status update received from SMSC after 72 hours after submit
