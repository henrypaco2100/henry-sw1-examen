<?php

namespace henry\sw1\Tests;

use Illuminate\Database\Schema\Blueprint;
use henry\sw1\Tests\Models\User;
use henry\sw1\Models\Role;
use henry\sw1\Models\Permission;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->loadLaravelMigrations([]);
        $this->artisan('migrate');

        $this->seedDatabase();
    }

    protected function seedDatabase()
    {
        $user = [
          'admin' =>    User::create(['name'  => 'Admin', 'password' => 'password', 'email' => 'admin@example.com']),
          'user' =>     User::create(['name'  => 'User', 'password' => 'password', 'email' => 'user@example.com']),
          'super' =>    User::create(['name'  => 'Super User', 'password' => 'password', 'email' => 'super_user@example.com']),
          'disabled' => User::create([ 'name'  => 'Disabled User', 'password' => 'password', 'email' => 'disabled_user@example.com']),
        ];
        
        $roles = [
            'admin' =>      Role::create(['name' => 'Admin', 'slug' => 'admin']),
            'user' =>       Role::create(['name' => 'User', 'slug' => 'user']),
            'allaccess' =>  Role::create(['name' => 'Super Access', 'slug' => 'super', 'special' => 'all-access']),
            'noaccess' =>   Role::create(['name' => 'No access', 'slug' => 'no-access', 'special' => 'no-access']),
        ];
        
        $permission = [
            'access' => Permission::create(['name' => 'Admin Access', 'slug' => 'access.admin']),
            'view' =>   Permission::create(['name' => 'View Users', 'slug' => 'view.users']),
            'edit' =>   Permission::create(['name' => 'Edit Users', 'slug' => 'edit.users']),
            'email' =>  Permission::create(['name' => 'Email Users', 'slug' => 'email.users']),
        ];

        $roles['admin']->permissions()->attach($permission['access']);
        $roles['admin']->permissions()->attach($permission['edit']);
        $roles['user']->permissions()->attach($permission['view']);
        $roles['noaccess']->permissions()->attach($permission['view']);

        $user['admin']->roles()->attach(Role::all());
        $user['user']->roles()->attach($roles['user']);
        $user['user']->permissions()->attach($permission['email']);
        $user['super']->roles()->attach($roles['allaccess']);
        $user['disabled']->roles()->attach($roles['noaccess']);
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', true);
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            \henry\sw1\sw1ServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'sw1' => \henry\sw1\Facades\sw1::class,
        ];
    }
}