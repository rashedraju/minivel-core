<?php


use Minivel\Model;
use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testLoadData($data){
        $model = new class() extends Model{
            public string $name = "";
            public string $email = "";
            public string $password = "";

            public function getRules(): array
            {
                return [
                    "name" => [self::RULE_REQUIRE],
                    "email" => [self::RULE_EMAIL],
                    "password" => [[self::RULE_MATCH, 'password'], [self::RULE_MIN, 4], [self::RULE_MAX, 24]]
                ];
            }
            public function getLabels(): array
            {
                return [
                    "name" => "Name",
                    "email" => "Email",
                    "password" => "Password"
                ];
            }
        };
        $model->loadData($data);
        $this->assertObjectHasAttribute("name", $model);
        $this->assertArrayHasKey("name", $model->getRules());
        $this->assertArrayHasKey("email", $model->getLabels());
        $this->assertTrue($model->validate());
    }

    public function dataProvider(): array{
        return [
          [["name"=> "john doe", "email"=> "john@exmaple.com", "password"=>"123456"]],
          [["name"=> "mark morton", "email"=> "mark@exmaple.com", "password"=> "123456"]]
        ];
    }
}
