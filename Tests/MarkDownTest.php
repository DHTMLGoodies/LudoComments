<?php
/**
 * Comment pending.
 * User: Alf Magne Kalleland
 * Date: 13.03.13
 * Time: 21:09
 */
require_once(__DIR__ . "/../autoload.php");
ini_set('display_errors', 'on');

class MarkDownTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldHandleBold()
    {
        // given
        $m = new LudoMarkDown();
        // when
        $text = $m->get('Alf Magne __Test__ end');

        // then
        $this->assertEquals('Alf Magne <strong>Test</strong> end', $text);
        $this->assertEquals('Alf Magne <strong>Test</strong> end <strong>bold</strong>', $m->get('Alf Magne __Test__ end __bold__'));
    }

    /**
     * @test
     */
    public function shouldHandleItalic()
    {
        // given
        $m = new LudoMarkDown();
        // when

        // then
        $this->assertEquals('Alf Magne <em>Test</em> end', $m->get('Alf Magne _Test_ end'));
        $this->assertEquals('Alf Magne <strong>Test</strong> end <em>bold</em>', $m->get('Alf Magne __Test__ end _bold_'));
    }

    /**
     * @test
     */
    public function shouldHandleHeaders()
    {
        // given
        $m = new LudoMarkDown();

        $this->assertEquals("Test\n<h1>Heading 1</h1>\ntester", $m->get("Test\n#Heading 1\ntester"));
        $this->assertEquals("Test\n<h1>Heading 1</h1>\ntester\n<h2>Test</h2>", $m->get("Test\n#Heading 1\ntester\n##Test"));

    }

    /**
     * @test
     */
    public function shouldHandleCode(){
        // given
        $m = new LudoMarkDown();

        $this->assertEquals("Use the <code>printf()</code> function.", $m->get("Use the `printf()` function."));
        $this->assertEquals("<code>There is a literal backtick (`) here.</code>", $m->get("``There is a literal backtick (`) here.``"));
    }
}
