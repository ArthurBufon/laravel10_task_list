<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if admin can create a new task.
     */
    public function test_admin_can_store_new_task()
    {
        // Creates new admin user.
        $admin = User::factory()->create(['is_admin' => 1]);

        // Sends post request to route.
        $response = $this->actingAs($admin)->post('/tasks', [
            'title' => 'Learn Programming',
            'description' => 'Learn Unit Test with PHPUnit',
            'long_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
        ]);
        // dd($response->getContent());
        // Asserts no errors occured.
        $response->assertSessionHasNoErrors();

        // // Asserts the redirect.
        $response->assertRedirect('/tasks');

        // // Asserts that database has 1 tasks.
        $this->assertCount(1, Task::all());

        // Checks if task was stored in database.
        $this->assertDatabaseHas('tasks', [
            'title' => 'Learn Programming',
            'description' => 'Learn Unit Test with PHPUnit',
            'long_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
        ]);
    }
}
