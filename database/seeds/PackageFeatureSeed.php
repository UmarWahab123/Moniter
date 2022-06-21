<?php


use App\Models\System\SystemFeature;
use Illuminate\Database\Seeder;

class PackageFeatureSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $Features = [
            'Servers',
            'Websites',
            'SubUsers',
        ];

        foreach($Features as $feature) 
        {
             SystemFeature::create([
                'name' => $feature
            ]);
        }
    }
}
