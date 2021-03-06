<?php

namespace Tests\Model;

use LogicException;
use Tests\RegistersPackage;
use Tests\MocksDatabaseFile;
use Orchestra\Testbench\TestCase;
use Tests\CleansProjectFromScaffoldData;

class RouteBindingTest extends TestCase
{
    use RegistersPackage;
    use CleansProjectFromScaffoldData;
    use MocksDatabaseFile;

    public function test_custom_model_does_not_includes_route_binding()
    {
        $this->mockDatabaseFile([
            'models' => [
                'User' => [
                    'columns' => [
                        'name' => 'string',
                    ]
                ],
            ],
        ]);

        $this->artisan('larawiz:scaffold');

        $this->assertStringNotContainsString(
            '    /**\n
     * Get the route key for the model.\n
     *\n
     * @return string\n
     */\n
    public function getRouteKeyName()\n
    {\n
        return $this->name;\n
    }\n',
            $this->filesystem->get($this->app->path('User.php'))
        );
    }

    public function test_includes_route_binding()
    {
        $this->mockDatabaseFile([
            'models' => [
                'User' => [
                    'columns' => [
                        'name' => 'string'
                    ],
                    'route' => 'name'
                ],
            ],
        ]);

        $this->artisan('larawiz:scaffold');

        $this->assertStringNotContainsString(
            '    /**\n
     * Get the route key for the model.\n
     *\n
     * @return string\n
     */\n
    public function getRouteKeyName()\n
    {\n
        return $this->name;\n
    }\n',
            $this->filesystem->get($this->app->path('User.php'))
        );
    }

    public function test_error_when_route_binding_column_does_not_exists()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage("The route binding [does_not_exists] column for the [User] model doesn't exists.");

        $this->mockDatabaseFile([
            'models' => [
                'User' => [
                    'columns' => [
                        'name' => 'string'
                    ],
                    'route' => 'does_not_exists'
                ],
            ],
        ]);

        $this->artisan('larawiz:scaffold');
    }

    protected function tearDown() : void
    {
        $this->cleanProject();

        parent::tearDown();
    }
}
