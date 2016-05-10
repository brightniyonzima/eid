<?php

namespace EID\Console\Commands;

use EID\Lib\Awk;
use Illuminate\Console\Command;

class FetchFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:fetch';// backup:fetch {--save-as : Save the received backup file as }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the most recent backup file auto-created by the system';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->line('');
        $this->line('This command searches the archives for the newest backup file and downloads it.');
        $this->line('You can use the downloaded file to restore system to a previous state');
        $this->line('');
        $this->line('');

        $save_as = $this->ask('Save File As:');
        $save_as .= ".zip";

        $a = new Awk;
        $a->getBackupFileName($save_as, $this);

        $this->line('SUCCESS! You can restore the system using data in this file: ' . storage_path('app/') . $save_as);
    }
}
