<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 12/26/18
 * Time: 5:19 PM
 */

namespace Test\Auth\Authorization;

use App\Models\Auth\User\User;

class ManageTest extends BaseRole
{
    public function setUp(): void
    {
        parent::setUp();
        $this->loggedInAs();
    }

    /** @test */
    public function assign_role_ro_user()
    {
        $user = factory(User::class)->create();
        $role = $this->createRole();

         $this->showModelWithRelation('backend.users.show', $user, $role, 'roles', 'dontSeeJson');

        $this->post(
            route('backend.authorizations.assign-role-to-user').'?include=roles',
            [
                'role_id' => $role->getHashedId(),
                'user_id' => $user->getHashedId(),
            ],
            $this->addHeaders()
        );
        $this->assertResponseOk();

        $this->showModelWithRelation('backend.users.show', $user, $role, 'roles');
    }

    /** @test */
    public function revoke_role_from_user()
    {
        $user = factory(User::class)->create();
        $role = $this->createRole();
        $user->assignRole($role);

//        $this->showModelWithRelation('backend.users.show', $user, $role, 'roles');

        $this->post(
            route('backend.authorizations.revoke-role-from-user').'?include=roles',
            [
                'role_id' => $role->getHashedId(),
                'user_id' => $user->getHashedId(),
            ],
            $this->addHeaders()
        );
        $this->assertResponseOk();

        $this->seeJsonApiRelation($role, 'roles', 'dontSeeJson');
//        $this->showModelWithRelation('backend.users.show', $user, $role, 'roles', 'dontSeeJson');
    }

    /** @test */
    public function assign_permission_to_user()
    {
        $user = factory(User::class)->create();
        $permission = $this->createPermission();

        $this->showModelWithRelation('backend.users.show', $user, $permission, 'permissions', 'dontSeeJson');

        $this->post(
            route('backend.authorizations.assign-permission-to-user').'?include=permissions',
            [
                'permission_id' => $permission->getHashedId(),
                'user_id' => $user->getHashedId(),
            ],
            $this->addHeaders()
        );
        $this->assertResponseOk();

        $this->showModelWithRelation('backend.users.show', $user, $permission, 'permissions');
    }

    /** @test */
    public function revoke_permission_to_user()
    {
        $user = factory(User::class)->create();

        $permission = $this->createPermission();
        $user->givePermissionTo($permission);

//        $this->showModelWithRelation('backend.users.show', $user, $permission, 'permissions');

        $this->post(
            route('backend.authorizations.revoke-permission-from-user').'?include=permissions',
            [
                'permission_id' => $permission->getHashedId(),
                'user_id' => $user->getHashedId(),
            ],
            $this->addHeaders()
        );
        $this->assertResponseOk();

        $this->seeJsonApiRelation($permission, 'permissions', 'dontSeeJson');
//        $this->showModelWithRelation('backend.users.show', $user, $permission, 'permissions', 'dontSeeJson');
    }

    /** @test */
    public function attach_permission_to_role()
    {
        $role = $this->createRole();
        $permission = $this->createPermission();

        $this->showModelWithRelation('backend.roles.show', $role, $permission, 'permissions', 'dontSeeJson');

        $this->post(
            route('backend.authorizations.attach-permission-to-role').'?include=permissions',
            [
                'permission_id' => $permission->getHashedId(),
                'role_id' => $role->getHashedId(),
            ],
            $this->addHeaders()
        );
        $this->assertResponseOk();

        $this->showModelWithRelation('backend.roles.show', $role, $permission, 'permissions');
    }

    /** @test */
    public function revoke_permission_from_role()
    {
        $role = $this->createRole();
        $permission = $this->createPermission();
        $role->givePermissionTo($permission);

//        $this->showModelWithRelation('backend.roles.show', $role, $permission, 'permissions');

        $this->post(
            route('backend.authorizations.revoke-permission-from-role').'?include=permissions',
            [
                'permission_id' => $permission->getHashedId(),
                'role_id' => $role->getHashedId(),
            ],
            $this->addHeaders()
        );
        $this->assertResponseOk();

        $this->seeJsonApiRelation($permission, 'permissions', 'dontSeeJson');
//        $this->showModelWithRelation('backend.roles.show', $role, $permission, 'permissions', 'dontSeeJson');
    }
}