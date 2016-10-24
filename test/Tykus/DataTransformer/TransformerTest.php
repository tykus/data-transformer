<?php

use Tykus\DataTransformer\Transformer;
use Tykus\DataTransformer\FormatIdentifier;

class TransformerTest extends PHPUnit_Framework_TestCase
{
    protected $filename;
    protected $formatIdentifier;
    protected $transformer;

    public function setUp()
    {
        parent::setUp();
        $this->formatIdentifier = Mockery::mock(FormatIdentifier::class);
        $this->formatIdentifier->shouldReceive('identify')
            ->once()
            ->andReturn('csv');
        $this->filename = __DIR__ . '/files/example.csv';

        $this->transformer = new Transformer($this->filename, $this->formatIdentifier);
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(
            Tykus\DataTransformer\Transformer::class,
            $this->transformer
        );
    }

    /** @test */
    public function it_validates_the_file_exists()
    {
        $this->expectException(Tykus\DataTransformer\Exceptions\FileNotFoundException::class);

        $transformer = new Transformer('fileThatDoesNotExist.csv', $this->formatIdentifier);
    }

    /** @test */
    public function it_determines_which_importer_it_should_use()
    {
        $this->assertInstanceOf(
            Tykus\DataTransformer\Importers\CsvImporter::class,
            $this->transformer->getImporter()
        );
    }

    /** @test */
    public function it_determines_which_exporter_to_use()
    {
        $this->transformer->toJson();

        $this->assertInstanceOf(
            Tykus\DataTransformer\Exporters\JsonExporter::class,
            $this->transformer->getExporter()
        );
    }

    /** @test */
    public function it_transforms_data_into_nested_data()
    {
        $transformed = $this->transformer->transform(function($row) {
            return [
                'item id' => $row['item id'],
                'description' => $row['description'],
                'price' => $row['price'],
                'cost' => $row['cost'],
                'price_type' => $row['price_type'],
                'quantity_on_hand' => $row['quantity_on_hand'],
                'modifiers' => [
                    [
                        'name' => $row['modifier_1_name'],
                        'price' => $row['modifier_1_price']
                    ],
                    [
                        'name' => $row['modifier_2_name'],
                        'price' => $row['modifier_2_price']
                    ],
                    [
                        'name' => $row['modifier_3_name'],
                        'price' => $row['modifier_3_price']
                    ]
                ]
            ];
        });
    }

}
