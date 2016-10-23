<?php

use Tykus\DataTransformer\Importers\CsvImporter;

class CsvImporterTest extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        parent::setUp();

        $this->reader = Mockery::mock(League\Csv\Reader::class);

        $this->filename = dirname(__FILE__) . '/../files/example.csv';
        $this->importer = new CsvImporter($this->filename, $this->reader);
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(
            Tykus\DataTransformer\Importers\CsvImporter::class,
            $this->importer
        );
    }

    /** @test */
    public function it_reads_the_provided_file_into_collection()
    {
        $this->assertInstanceOf(
            Illuminate\Support\Collection::class,
            $this->importer->get()
        );
    }

}
