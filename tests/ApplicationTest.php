<?php


use Dotenv\Dotenv;
use Minivel\Application;
use Minivel\DB\Database;
use Minivel\Request;
use Minivel\Response;
use Minivel\Router;
use Minivel\Session;
use Minivel\View;
use Minivel\UserModel;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    protected static Application $app;
    public static function setUpBeforeClass(): void
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->safeLoad();
        $config =[
            "DB" => [
                "dsn" => $_ENV['DB_DSN'],
                "username" => $_ENV['DB_USER'],
                "password" => $_ENV['DB_PASSWORD']
            ]
        ];
        self::$app = new Application(dirname(__DIR__), $config);
    }

    public function testApplicationDependenciesInstanceCreated()
    {
        $this->assertInstanceOf(Application::class, self::$app);
        $this->assertInstanceOf(Application::class, Application::$app);
        $this->assertInstanceOf(Response::class, self::$app->response);
        $this->assertInstanceOf(Router::class, self::$app->router);
        $this->assertInstanceOf(Database::class, self::$app->database);
        $this->assertInstanceOf(View::class, self::$app->view);
    }

    public function testApplicationRunMethodExecuteRouteResolve(){
        $mock = $this->getMockBuilder(Router::class)
            ->disableOriginalConstructor()
            ->onlyMethods(["resolve"])
            ->getMock();
        $mock->method("resolve")
            ->willReturn("test resolve");
        self::$app->router = $mock;
        $this->expectOutputString("test resolve");
        self::$app->run();
    }

    public function testApplicationUserLogin(): UserModel
    {
        $user = new class() extends UserModel{
            public int $id = 10;
            public function getAttributes(): array{
                return [];
            }
            public static function getPrimaryKey(): string
            {
                return "id";
            }
            public function getRules(): array
            {
                return [];
            }
            public function getLabels(): array
            {
                return [];
            }
            public function getDisplayName(): string
            {
                return "";
            }
            public static function getTableName(): string
            {
                return "";
            }
        };
        $mSession = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['set'])
            ->getMock();
        self::$app->session = $mSession;
        $this->assertTrue(self::$app->login($user));
        return $user;
    }

    protected function tearDown(): void
    {
        unset(self::$app->user);
    }

    public function testIsGuestReturnTrueWhenUserNotSet(){
        $this->assertTrue(self::$app::isGuest());
    }

    /**
     * @depends testApplicationUserLogin
     */
    public function testIsGuestReturnFalseWhenUserSet($user){
        self::$app->user = $user;
        $this->assertFalse(self::$app::isGuest());
    }
}
