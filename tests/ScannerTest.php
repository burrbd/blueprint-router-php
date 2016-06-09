<?php

namespace BlueprintRouter;

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

        $this->assertEquals(new Definition(null, 'foo bar', null, null), $definitions[0]);
    }

    public function testUriTemplate()
    {
        $this->writeContents('### /foo/{bar}');

        $scanner = new Scanner(new HashDelimitedHeadingParser());

        $definitions = $scanner->scan($this->file);

        $this->assertEquals(new Definition(null, null, null, '/foo/{bar}'), $definitions[0]);
    }

    public function testIdentifierUriTemplate()
    {
        $this->writeContents('# Foo  [/foo] ');

        $scanner = new Scanner(new HashDelimitedHeadingParser());

        $definitions = $scanner->scan($this->file);

        $this->assertEquals(new Definition(null, 'Foo', null, '/foo'), $definitions[0]);
    }

    public function testMethodUriTemplate()
    {
        $this->writeContents('## POST  /foo ');

        $scanner = new Scanner(new HashDelimitedHeadingParser());

        $definitions = $scanner->scan($this->file);

        $this->assertEquals(new Definition(null, null, 'POST', '/foo'), $definitions[0]);
    }

    public function testIdentifierMethodUriTemplate()
    {
        $this->writeContents('### Wiz bang [PATCH /pop] ');

        $scanner = new Scanner(new HashDelimitedHeadingParser());

        $definitions = $scanner->scan($this->file);

        $this->assertEquals(new Definition(null, 'Wiz bang', 'PATCH', '/pop'), $definitions[0]);
    }
}
