<?php

namespace BlueprintRouter\Parser;

use BlueprintRouter\Endpoint\DefinitionFactory;
use BlueprintRouter\Parser\DefinitionParser\HashDelimitedHeadingParser;
use BlueprintRouter\Parser\DefinitionParser\Tokenizer\BasicDefinitionTokenizer;

class ScannerTest extends \PHPUnit_Framework_TestCase
{
    use DefinitionFactory;

    private $file;

    private $definitionParser;

    private $scanner;

    public function setUp()
    {
        $this->definitionParser = new HashDelimitedHeadingParser(new BasicDefinitionTokenizer());
        $this->scanner = new Scanner();
        $this->scanner->addDefinitionParser($this->definitionParser);

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

        $this->scanner->scan('foo');
    }

    public function testParseInvalidDefinition()
    {
        $this->writeContents('# foo bar');

        $definitions = $this->scanner->scan($this->file);

        $this->assertCount(0, $definitions);
    }

    public function testScanADefinition()
    {
        $this->writeContents('# Foo [/foo]');

        $definitions = $this->scanner->scan($this->file);

        $this->assertCount(1, $definitions);
    }

    public function testGroup()
    {
        $this->writeContents('## Group foo bar ');

        $definitions = $this->scanner->scan($this->file);

        $this->assertEquals([$this->createDefinition(null, 2, ['foo bar'])], $definitions);
    }

    public function testUriTemplate()
    {
        $this->writeContents('### /foo/{bar}');

        $definitions = $this->scanner->scan($this->file);

        $this->assertEquals([$this->createDefinition(null, 3, [], null, '/foo/{bar}')], $definitions);
    }

    public function testIdentifierUriTemplate()
    {
        $this->writeContents('# Foo  [/foo] ');

        $definitions = $this->scanner->scan($this->file);

        $this->assertEquals([$this->createDefinition(null, 1, ['Foo'], null, '/foo')], $definitions);
    }

    public function testMethodUriTemplate()
    {
        $this->writeContents('## POST  /foo ');

        $definitions = $this->scanner->scan($this->file);

        $this->assertEquals([$this->createDefinition(null, 2, [], 'POST', '/foo')], $definitions);
    }

    public function testIdentifierMethodUriTemplate()
    {
        $this->writeContents('### Whiz bang [PATCH /pop] ');

        $definitions = $this->scanner->scan($this->file);

        $this->assertEquals([$this->createDefinition(null, 3, ['Whiz bang'], 'PATCH', '/pop')], $definitions);
    }

    public function testMethodTemplate()
    {
        $this->writeContents('## PUT ');

        $definitions = $this->scanner->scan($this->file);

        $this->assertEquals([$this->createDefinition(null, 2, [], 'PUT')], $definitions);
    }

    public function testIdentifierMethodTemplate()
    {
        $this->writeContents('## Bar foo [PUT] ');

        $definitions = $this->scanner->scan($this->file);

        $this->assertEquals([$this->createDefinition(null, 2, ['Bar foo'], 'PUT')], $definitions);
    }

    public function testMultiLevelNesting()
    {
        $this->writeContents('# Group Foo bar'."\n".'## /foo/bar'."\n".'### GET'."\n".'### PATCH');

        $definitions = $this->scanner->scan($this->file);

        $d1 = $this->createDefinition(null, 1, ['Foo bar']);
        $d2 = $this->createDefinition($d1, 2, [], null, '/foo/bar');
        $d3 = $this->createDefinition($d2, 3, [], 'GET');
        $d4 = $this->createDefinition($d2, 3, [], 'PATCH');

        $this->assertEquals([$d1, $d2, $d3, $d4], $definitions);
    }
}
