<?php

namespace Tests\Feature;

use App\Rol;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;
use TestCase;

class DeleteRoleTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @var string
     */
    private $url = 'rols';

    /**
     * @var
     */
    private $user;

    protected function setUp() : void
    {
        parent::setUp();
        $this->user = $user = factory(User::class)->create();
        $role = factory(Rol::class)->create([
            'id' => 1
        ]);

        $user->roles()->attach($role->id);
        $this->be($user);

        session()->start();
    }

    protected function tearDown() : void
    {
        Artisan::call('migrate:reset');
        parent::tearDown();
    }

    /** @test */
    public function a_role_can_be_deleted_by_an_admin(){
        $role = factory(Rol::class)->create();

        $response = $this->delete($this->url . '/' . $role->id);

        $response->assertStatus(302);

        $this->assertNull(Rol::find($role->id));
    }

    /** @test */
    public function a_user_can_not_delete_a_role(){
        $role = factory(Rol::class)->create();
        $this->user->roles()->detach();

        $response = $this->delete($this->url . '/' . $role->id);

        $response->assertStatus(403);

    }
}
