<?php 

namespace ActivismBe\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

/**
 * Class DatabaseSeeder
 *  
 * @package ActivismBe\Seeders
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void 
    {
        DB::connection()->disableQueryLog(); 
        Model::unguard(); 

        if ($this->canResetDatabase()) {
            $this->truncateAll(); // Method for truncating all the database tables. 
            $this->command->warn('All the tables from the database are empty now!');
        }
    }

    /**
     * Function for determining if the deveoper want to truncate the database or not. 
     * 
     * @return bool
     */
    protected function canResetDatabase(): bool
    {
        return ! app()->environment(['production', 'prod'])
            && $this->command->confirm('Do you wish to empty all the database tables before seeding, it will clear all old data?');
    }

    /**
     * Function to perform the truncate command on every database table. Except migrations 
     * 
     * @return void
     */
    protected function truncateAll(): void 
    {
        Schema::disableForeignKeyConstraints(); 

        collect(DB::select("SHOW FULL TABLES WHERE Table_Type = 'BASE TABLE'"))
            ->map(function ($tableProperties) {
                return get_object_vars($tableProperties)[key($tableProperties)];
            })
            ->reject(function (string $tableName) {
                return $tableName === 'migrations';
            })
            ->each(function (string $tableName) {
                DB::table($tableName)->truncate();
            });

        Schema::enableForeignKeyConstraints();
    }
}