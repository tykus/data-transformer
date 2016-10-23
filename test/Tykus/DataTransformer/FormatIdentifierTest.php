<?php

use Tykus\DataTransformer\FormatIdentifier;

class FormatIdentifierTest extends PHPUnit_Framework_TestCase
{
    protected $filename;

    public function setUp()
    {
        parent::setUp();
        $this->filename = __DIR__ . '/files/example.csv';
    }

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(
            Tykus\DataTransformer\FormatIdentifier::class,
            new FormatIdentifier($this->filename)
        );
    }

    /** @test */
    public function it_determines_the_format_of_the_provided_file()
    {
        $identifier = new FormatIdentifier($this->filename);

        $this->assertEquals(
            'csv',
            $identifier->identify()
        );
    }

    /** @test */
    public function it_squawks_if_the_format_is_not_supported()
    {
        $this->expectException(Tykus\DataTransformer\Exceptions\UnsupportedFormatException::class);
        $filename = __DIR__ . '/files/example.txt';

        $identifier = new FormatIdentifier($filename);
        $identifier->identify();
    }
}
