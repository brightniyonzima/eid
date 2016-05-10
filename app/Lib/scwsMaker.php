<?php namespace EID\Lib;

use EID\Http\Controllers\LabController;

class scwsMaker{// Sickle Cell WorkSheet Maker

	private $grp_id;
	private $samples;
	private $status = "";

	private $mg_array = [];// Array of Master Groups. Each group is the master and its samples[] array is the details section


	public function __construct(LabController $lab){

		$this->grp_id = 0;
		$this->samples = $lab->get_scws_sources();
		$this->group_the_samples();
	}

	public function group_the_samples()
	{

		if(($nSamples = count($this->samples)) == 0) {
			$this->status = 'No samples available';
			return;
		}


		$i=-1;
		$ploc = ""; // make_new_group($samples[0], $grp_id);

		// for($i=0; $i < $nSamples; $i++){
		foreach ($this->samples as $this_sample) {
			$i++;

			// $this_sample = $this->samples[$i];

			$is_new_location = ($ploc != $this_sample->physical_location) ? true : false;
			$is_last_sample = ($i == ($nSamples-1)) ? true : false;

			if($is_new_location){
				
				$this->grp_id++;
				$this->make_new_group($this_sample, $this->grp_id);
				$ploc = $this_sample->physical_location;

			}else{
				
				$this->append_to_group($this->grp_id, $this_sample);
			}
		}
	}

	public function make_new_group($first_sample, $group_id)
	{

		$g = new \StdClass;		
		$g->header = new \StdClass;
		$g->samples = [];

		$first_sample->group_id = $group_id;
		$g->samples[($first_sample->id)] = $first_sample;

		$g->header->id = $group_id;
		$g->header->physical_location = $first_sample->physical_location;
		$g->header->source = $first_sample->source;

		$this->mg_array[$group_id] = $g;
	}


	public function append_to_group($group_id, $sample)
	{
		$sample->group_id = $group_id;		
		$this->mg_array[$group_id]->samples[($sample->id)] = $sample;
	}


	public function getStatus()
	{
		return $this->status;
	}

	public function getData()
	{
		return $this->mg_array;
	}
}