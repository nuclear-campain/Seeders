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
            $this->createOnlyNormalUserRole();
        }
    }

    /**
     * Implement the default permissions in the storage for the application.
     * 
     * @return void
     */
    protected function seedDefaultPermissions(): void 
    {
        foreach ($this->defaultPermissions() as $permission) {
            Permission::firstOrCreate(['name' => trim($permission)]);
        }
    }

    /**
     * Function for creating only an normal user role in the application. 
     * 
     * @return void 
     */
    protected function createOnlyNormalUserRole(): void 
    {
        Role::firstOrCreate(['name' => 'user']);
        $this->command->info('Added only default user role.');
    }

    /**
     * Function whe we define the default permissions for the application. 
     * 
     * @return void
     */
    public function defaultPermissions(): array 
    {
        // Here we can define default permissions for the application. 
        // For this application there are no default permissions for now. 
        // Because we only use the roles section from spatie/permission package.

        return [];
    }

    /**
     * Function for attaching permissions and creating a role if it not already exists in the database. 
     * 
     * @param  string $roles The one dimensional array for the given roles.
     * @return void
     */
    protected function createRoleIfNotExists(string $roles): void 
    {
        foreach (explode(',', $roles) as $role) {
            $role = Role::firstOrCreate(['name' => trim($role)]);
            if ($this->isAdmin($role->name)) { // Assign all permissions
                $role->syncPermissions(Permission::all());
                $this->command->info('Admin granted all permissions');
            } else { // For others by default only read access
                $role->syncPermissions($this->getUserPermissions());
            }
            $this->createUser($role); // Create one user for each role.
        }
    }
    
    /**
     * Creating a normal user in the storage
     * 
     * @return void
     */
    protected function createUser(Role $role): void 
    {
        $user = factory(User::class)->create(['password' => 'secret'])->assignRole($role->name);
        if ($this->isAdmin($role->name)) {
            $this->command->info('Here are your admin details to login:'); 
            $this->command->warn($user->email); 
            $this->command->warn('Password is "secret"');
        }
    }
    
    /**
     * Function for getting the permissions that assigned to normal users. 
     * 
     * @return void
     */
    protected function getUserPermissions(): Collection
    {
        return Permission::where('name', 'LIKE', 'view_%')->get();
    }

    /**
     * Determine if the created role is admin or not. 
     * 
     * @return bool
     */
    protected function isAdmin(string $role): bool 
    {
        return $role === 'admin'; 
    }
}
