<?php

use Illuminate\Support\Collection;
use Tykus\DataTransformer\Exporters\JsonExporter;

class JsonExporterTest extends PHPUnit_Framework_TestCase
{
    protected $data;
    protected $expected;

    public function setUp()
    {
        parent::setUp();
        $this->data = Collection::make([
            [
                'item_id' => 1,
                'name' => 'Product 1',
                'modifiers' => [
                    [
                        'name' => 'Blue Thing',
                        'price' => 19.99
                    ]
                ]
            ]
        ]);
        $this->expected = '[{"item_id":1,"name":"Product 1","modifiers":[{"name":"Blue Thing","price":19.99}]}]';
    }

    /** @test */
    public function it_encodes_the_data()
    {
        $result = new JsonExporter($this->data);

        $this->assertEquals($result, $this->expected);
    }

}
