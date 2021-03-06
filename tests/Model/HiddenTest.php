<?php

namespace Tests\Model;

use Tests\RegistersPackage;
use Tests\MocksDatabaseFile;
use Illuminate\Support\Carbon;
use Orchestra\Testbench\TestCase;
use Tests\CleansProjectFromScaffoldData;
use const DIRECTORY_SEPARATOR as DS;

class HiddenTest extends TestCase
{
    use RegistersPackage;
    use CleansProjectFromScaffoldData;
    use MocksDatabaseFile;

    public function test_quick_model_adds_sensible_columns_automatically()
    {
        $this->mockDatabaseFile([
            'models' => [
                'User' => [
                    'foo' => 'string',
                    'bar' => 'boolean',
                    'password' => 'string',
                    'password_foo' => 'string',
                    'private' => 'string',
                    'private_foo' => 'string',
                    'rememberToken' => null,
                    'hidden' => 'string',
                    'foo_hidden' => 'string',
                    'secret' => 'string',
                    'secret_foo' => 'string',
                ],
            ],
        ]);

        Carbon::setTestNow(Carbon::parse('2020-01-01 16:30:00'));

        $this->artisan('larawiz:scaffold');

        $model = $this->filesystem->get($this->app->path('User.php'));
        $migration = $this->filesystem->get(
            $this->app->databasePath('migrations' . DS . '2020_01_01_163000_create_users_table.php'));

        $this->assertStringContainsString("protected \$hidden = [
        'password',
        'password_foo',
        'private',
        'private_foo',
        'remember_token',
        'hidden',
        'foo_hidden',
        'secret',
        'secret_foo',
    ];", $model);

        $this->assertStringContainsString("\$table->string('foo');", $migration);
        $this->assertStringContainsString("\$table->boolean('bar');", $migration);
        $this->assertStringContainsString("\$table->string('password');", $migration);
        $this->assertStringContainsString("\$table->string('password_foo');", $migration);
        $this->assertStringContainsString("\$table->string('private');", $migration);
        $this->assertStringContainsString("\$table->string('private_foo');", $migration);
        $this->assertStringContainsString("\$table->rememberToken();", $migration);
        $this->assertStringContainsString("\$table->string('hidden');", $migration);
        $this->assertStringContainsString("\$table->string('foo_hidden');", $migration);
        $this->assertStringContainsString("\$table->string('secret');", $migration);
        $this->assertStringContainsString("\$table->string('secret_foo');", $migration);
    }

    public function test_custom_model_adds_sensible_columns_automatically()
    {
        $this->mockDatabaseFile([
            'models' => [
                'User' => [
                    'columns' => [
                        'foo' => 'string',
                        'bar' => 'boolean',
                        'password' => 'string',
                        'password_foo' => 'string',
                        'private' => 'string',
                        'private_foo' => 'string',
                        'rememberToken' => null,
                        'hidden' => 'string',
                        'foo_hidden' => 'string',
                        'secret' => 'string',
                        'secret_foo' => 'string',
                    ],
                ],
            ],
        ]);

        Carbon::setTestNow(Carbon::parse('2020-01-01 16:30:00'));

        $this->artisan('larawiz:scaffold');

        $model = $this->filesystem->get($this->app->path('User.php'));
        $migration = $this->filesystem->get(
            $this->app->databasePath('migrations' . DS . '2020_01_01_163000_create_users_table.php'));

        $this->assertStringContainsString("protected \$hidden = [
        'password',
        'password_foo',
        'private',
        'private_foo',
        'remember_token',
        'hidden',
        'foo_hidden',
        'secret',
        'secret_foo',
    ];", $model);

        $this->assertStringContainsString("\$table->string('foo');", $migration);
        $this->assertStringContainsString("\$table->boolean('bar');", $migration);
        $this->assertStringContainsString("\$table->string('password');", $migration);
        $this->assertStringContainsString("\$table->string('password_foo');", $migration);
        $this->assertStringContainsString("\$table->string('private');", $migration);
        $this->assertStringContainsString("\$table->string('private_foo');", $migration);
        $this->assertStringContainsString("\$table->rememberToken();", $migration);
        $this->assertStringContainsString("\$table->string('hidden');", $migration);
        $this->assertStringContainsString("\$table->string('foo_hidden');", $migration);
        $this->assertStringContainsString("\$table->string('secret');", $migration);
        $this->assertStringContainsString("\$table->string('secret_foo');", $migration);
    }

    public function test_custom_model_doesnt_adds_hidden()
    {
        $this->mockDatabaseFile([
            'models' => [
                'User' => [
                    'columns' => [
                        'foo' => 'string',
                        'bar' => 'boolean',
                        'password' => 'string',
                        'password_foo' => 'string',
                        'private' => 'string',
                        'private_foo' => 'string',
                        'rememberToken' => null,
                        'hidden' => 'string',
                        'foo_hidden' => 'string',
                        'secret' => 'string',
                        'secret_foo' => 'string',
                    ],
                    'hidden' => false,
                ],
            ],
        ]);

        Carbon::setTestNow(Carbon::parse('2020-01-01 16:30:00'));

        $this->artisan('larawiz:scaffold');

        $model = $this->filesystem->get($this->app->path('User.php'));
        $migration = $this->filesystem->get(
            $this->app->databasePath('migrations' . DS . '2020_01_01_163000_create_users_table.php'));

        $this->assertStringNotContainsString("protected \$hidden", $model);

        $this->assertStringContainsString("\$table->string('foo');", $migration);
        $this->assertStringContainsString("\$table->boolean('bar');", $migration);
        $this->assertStringContainsString("\$table->string('password');", $migration);
        $this->assertStringContainsString("\$table->string('password_foo');", $migration);
        $this->assertStringContainsString("\$table->string('private');", $migration);
        $this->assertStringContainsString("\$table->string('private_foo');", $migration);
        $this->assertStringContainsString("\$table->rememberToken();", $migration);
        $this->assertStringContainsString("\$table->string('hidden');", $migration);
        $this->assertStringContainsString("\$table->string('foo_hidden');", $migration);
        $this->assertStringContainsString("\$table->string('secret');", $migration);
        $this->assertStringContainsString("\$table->string('secret_foo');", $migration);
    }

    public function test_custom_model_add_custom_hidden()
    {
        $this->mockDatabaseFile([
            'models' => [
                'User' => [
                    'columns' => [
                        'foo' => 'string',
                        'bar' => 'string',
                        'quz' => 'string',
                    ],
                    'hidden' => [
                        'bar', 'quz', 'quuz'
                    ]
                ],
            ],
        ]);

        Carbon::setTestNow(Carbon::parse('2020-01-01 16:30:00'));

        $this->artisan('larawiz:scaffold');

        $model = $this->filesystem->get($this->app->path('User.php'));
        $migration = $this->filesystem->get(
            $this->app->databasePath('migrations' . DS . '2020_01_01_163000_create_users_table.php'));

        $this->assertStringContainsString("protected \$hidden = ['bar', 'quz', 'quuz'];", $model);

        $this->assertStringContainsString("\$table->string('foo');", $migration);
        $this->assertStringContainsString("\$table->string('bar');", $migration);
        $this->assertStringContainsString("\$table->string('quz');", $migration);
    }

    protected function tearDown() : void
    {
        $this->cleanProject();

        parent::tearDown();
    }
}
