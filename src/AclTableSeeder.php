<?php 

namespace ActivismBe\Seeders; 

use App\User; 
use Spatie\Permission\Models\{Permission, Role}; 
use Illuminate\Database\Eloquent\{Collection, Seeder};

/**
 * Class AclTableSeeder 
 * 
 * @package ActivismBe\Seeders
 */
class AclTableSeeder extends DatabaseSeeder
{
    /**
     * Run the database seeds. 
     * 
     * @return void
     */
    public function run(): void 
    {
        $this->seedDefaultPermissions(); // Needed for first because we are attaching permissions to roles. 

        if ($this->command->confirm('Create roles for the user(s), default is admin and user.', true)) {
            // Confirm and ask for application specific roles for the application. 
            $inputRoles = $this->command->ask('Enter roles in comma separated formal.', 'admin, user');
            $this->createRoleIfNotExists($inputRoles);
        } else { // Only default user is needed
            $this->createOnlyNormalUser();
        }
    }
}