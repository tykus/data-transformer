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

}
