<?php namespace EID\Lib;

use Knp\Snappy\AbstractGenerator;

class Awk extends AbstractGenerator{// refactor this class



    public function __construct()
    {

    }

	public function getBackupFileName($save_as, $x)
	{

		$tail_backup_log = "tail -2 storage/app/backups/backuplog.txt | head -1 ";
		$get_backup_file =  "awk -F \" \" '{print \$7 }' | awk -F '\"' '{ print \$2}'";
		$cmd = $tail_backup_log . " | " . $get_backup_file;


		$reply = $this->executeCommand( $cmd );
		$backup_file_name = trim($reply[1]);


		$backup_data = $this->get_backup_data( $backup_file_name , $x );


		if( \Storage::disk('local')->has($save_as) ){

			$err_msg = 	"ERROR: Choose another name. " . 
						"A File with that name [" . $save_as . "] already exists at " . 
						storage_path('app/') . $save_as;
			dd(	trim($err_msg) );
		}

		\Storage::disk('local')->put($save_as, $backup_data);

	}


	public function get_backup_data( $backup_file , $x)
	{

		$local_disk = \Storage::disk('local');
		$remote_disk = \Storage::disk('s3')->getDriver();


		if( $local_disk->has( $backup_file )){
			return $local_disk->get( $backup_file );
		}


		if( $remote_disk->has( $backup_file )){
			return $this->download_via_stream($backup_file, $remote_disk);
		}


		$full_path = storage_path('app/') . $backup_file;
		$e = "ERROR: Checked localhost and amazon: Backup file [" . $full_path . "] not found";
		throw new \Exception($e, 1);
	}


	protected function download_via_stream($backup_file, $remote_disk )
	{

		$stream = $remote_disk->readStream($backup_file);
		$http_response_headers = [
		    "Content-Type" => $remote_disk->getMimetype($backup_file),
		    "Content-Length" => $remote_disk->getSize($backup_file),
		    "Content-disposition" => "attachment; filename=\"" . basename($backup_file) . "\"",
		];
		$http_status_code = 200;

		return \Response::stream(function() use($stream) {

		    fpassthru($stream);

		}, $http_status_code, $http_response_headers);
	}


	protected function configure(){
		throw new \Exception("\EID\Lib\Awk --- method configure() has no code" .
			"\nSee children of Knp\Snappy\AbstractGenerator for ideas", 1);
	}
}
