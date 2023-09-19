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

        // Asserts no errors occured.
        $response->assertSessionHasNoErrors();

        // Asserts the redirect.
        $response->assertRedirect('/tasks');

        // Asserts that database has 1 tasks.
        $this->assertCount(1, Task::all());

        // Checks if task was stored in database.
        $this->assertDatabaseHas('tasks', [
            'title' => 'Learn Programming',
            'description' => 'Learn Unit Test with PHPUnit',
            'long_description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
        ]);
    }

    /**
     * Test if admin can create a new task.
     */
    public function test_admin_can_see_the_edit_task_page()
    {
        // Creates new admin user.
        $admin = User::factory()->create(['is_admin' => 1]);

        // Creates new task.
        $task = Task::factory()->create();

        // Sends get request to edit task route.
        $response = $this->actingAs($admin)->get('/tasks/' . $task->id . '/edit');

        // Verifies if response status is 200.
        $response->assertStatus(200);

        // Verifying if edit page contains the title of the created task.
        $response->assertSee($task->title);
    }

    /**
     * Test if admin can update a task
     */
    public function test_admin_can_update_task()
    {
        // Creates new admin user.
        $admin = User::factory()->create(['is_admin' => 1]);

        // Creates new task.
        Task::factory()->create();

        // Asserts that there is at least 1 task in table.
        $this->assertCount(1, Task::all());

        // Gets the first task.
        $task = Task::first();

        // Sends put request to update task route.
        $response = $this->actingAs($admin)->put('/tasks/' . $task->id, [
            'title' => 'UPDATED Learn Programming',
            'description' => 'UPDATED Learn Unit Test with PHPUnit',
            'long_description' => 'UPDATED Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
        ]);

        // Asserts no errors occured.
        $response->assertSessionHasNoErrors();

        // Asserts the redirect.
        $response->assertRedirect('/tasks');

        // Asserts that the data of the task has been updated successfully.
        $this->assertEquals('UPDATED Learn Programming', Task::first()->title);
        $this->assertEquals('UPDATED Learn Unit Test with PHPUnit', Task::first()->description);
        $this->assertEquals('UPDATED Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', Task::first()->long_description);
    }

    /**
     * Test if admin can delete a task
     */
    public function test_admin_can_delete_task()
    {
        // Creates new admin user.
        $admin = User::factory()->create(['is_admin' => 1]);

        // Creates new task.
        $task = Task::factory()->create();

        // Asserts that there is at least 1 task in table.
        $this->assertEquals(1, Task::count());

        // Sends delete request to delete task route.
        $response = $this->actingAs($admin)->delete('/tasks/'. $task->id);

        // Asserts response status is 302 (deleted successfully).
        $response->assertStatus(302);

        // Asserts tasks table is empty
        $this->assertEquals(0, Task::count());
    }
}
