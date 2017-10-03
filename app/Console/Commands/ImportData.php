<?php

namespace App\Console\Commands;

use App\City;
use Illuminate\Console\Command;

class ImportData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:people';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from file';

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
        $filePath = storage_path('app\public\people.csv');
        $row = 0;
        if (($handle = fopen($filePath, "r")) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $row++;
                if($row == 1) continue;
                
                $city = new City();
                $city->fill([
                    'birth_year' => $data[0],
                    'birth_country' => $data[1],
                    'gender' => $data[2],
                    'family_situation' => $data[3],
                    'kids' => $data[4],
                    'location' => $data[5],
                    'street' => $data[6]
                ]);
                $city->save();
            }
            fclose($handle);
        }

          }
}
