<?php

namespace BlueprintRouter\Parser;

class ScannerTest extends \PHPUnit_Framework_TestCase
{
    private $file;

    public function setUp()
    {
        $this->file = tmpfile();
    }

    private function writeContents($text)
    {
        fwrite($this->file, $text);
        rewind($this->file);
    }

    public function testInvalidArgument()
    {
        $this->expectException(\InvalidArgumentException::class);

        $scanner = new Scanner(new HashDelimitedHeadingParser());

        $scanner->scan('foo');
    }

    public function testParseInvalidDefinition()
    {
        $this->writeContents('# foo bar');

        $scanner = new Scanner(new HashDelimitedHeadingParser());

        $scanner->scan($this->file);
    }

    public function testScanADefinition()
    {
        $this->writeContents('# Foo [/foo]');

        $scanner = new Scanner(new HashDelimitedHeadingParser());

        $definitions = $scanner->scan($this->file);

        $this->assertCount(1, $definitions);
    }

    public function testGroup()
    {
        $this->writeContents('## Group foo bar ');

        $scanner = new Scanner(new HashDelimitedHeadingParser());

        $definitions = $scanner->scan($this->file);

        $this->assertEquals(new Definition(['foo bar']), $definitions[0]);
    }

    public function testUriTemplate()
    {
        $this->writeContents('### /foo/{bar}');

        $scanner = new Scanner(new HashDelimitedHeadingParser());

        $definitions = $scanner->scan($this->file);

        $this->assertEquals(new Definition([], null, '/foo/{bar}'), $definitions[0]);
    }

    public function testIdentifierUriTemplate()
    {
        $this->writeContents('# Foo  [/foo] ');

        $scanner = new Scanner(new HashDelimitedHeadingParser());

        $definitions = $scanner->scan($this->file);

        $this->assertEquals(new Definition(['Foo'], null, '/foo'), $definitions[0]);
    }

    public function testMethodUriTemplate()
    {
        $this->writeContents('## POST  /foo ');

        $scanner = new Scanner(new HashDelimitedHeadingParser());

        $definitions = $scanner->scan($this->file);

        $this->assertEquals(new Definition([], 'POST', '/foo'), $definitions[0]);
    }

    public function testIdentifierMethodUriTemplate()
    {
        $this->writeContents('### Whiz bang [PATCH /pop] ');

        $scanner = new Scanner(new HashDelimitedHeadingParser());

        $definitions = $scanner->scan($this->file);

        $this->assertEquals(new Definition(['Whiz bang'], 'PATCH', '/pop'), $definitions[0]);
    }

    public function testMethodTemplate()
    {
        $this->writeContents('## PUT ');

        $scanner = new Scanner(new HashDelimitedHeadingParser());

        $definitions = $scanner->scan($this->file);

        $this->assertEquals(new Definition([], 'PUT'), $definitions[0]);
    }

    public function testIdentifierMethodTemplate()
    {
        $this->writeContents('## Bar foo [PUT] ');

        $scanner = new Scanner(new HashDelimitedHeadingParser());

        $definitions = $scanner->scan($this->file);

        $this->assertEquals(new Definition(['Bar foo'], 'PUT'), $definitions[0]);
    }

    public function testMultiLevelNesting()
    {
        $this->writeContents('# Group Foo bar'."\n".'## /foo/bar'."\n".'### GET'."\n".'### PATCH');

        $scanner = new Scanner(new HashDelimitedHeadingParser());

        $definitions = $scanner->scan($this->file);

        $this->assertEquals(
            [
                new Definition(['Foo bar']),
                new Definition([], null, '/foo/bar', $definitions[0]),
                new Definition([], 'GET', null, $definitions[1]),
                new Definition([], 'PATCH', null, $definitions[1])
            ],
            $definitions
        );
    }
}
