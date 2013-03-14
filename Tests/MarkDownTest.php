<?php
/**
 * Comment pending.
 * User: Alf Magne Kalleland
 * Date: 13.03.13
 * Time: 21:09
 */
require_once(__DIR__ . "/../autoload.php");
require_once(__DIR__ . "/../markdown.php");
ini_set('display_errors', 'on');

class MarkDownTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldHandleBold()
    {
        // when
        $texts = array(
            'Alf Magne __Test__ end',
            'Alf Magne __Test__ end __bold__',
            'Alf Magne **Test** end __bold__',
            '\*this text is surrounded by literal asterisks\*');

        $this->assertTexts($texts);
    }

    private function assertTexts($texts)
    {
        $m = new LudoMarkDown();
        foreach ($texts as $text) {
            $this->assertEquals($this->withoutNewlines(Markdown($text)), $this->withoutNewlines($m->get($text)));
        }
    }

    private function withoutNewlines($text)
    {
        return trim(preg_replace("/[\n\r]/s", "", $text));
    }

    /**
     * @test
     */
    public function shouldHandleItalic()
    {
        // when
        $texts = array(
            'Alf Magne _Test_ end',
            'Alf Magne __Test__ end _bold_');
        // then
        $this->assertTexts($texts);
    }

    /**
     * @test
     */
    public function shouldHandleHeaders()
    {
        // when
        $texts = array("Test\n#Heading 1\ntester", "Test\n#Heading 1\ntester\n##Test");

        // then
        $this->assertTexts($texts);
    }

    /**
     * @test
     */
    public function shouldHandleCode()
    {
        // given
        $texts = array("Use the `printf()` function.", "``There is a literal backtick (`) here.``");

        // then
        $this->assertTexts($texts);
    }

    /**
     * @test
     */
    public function shouldHandleCodeUsingTab()
    {
        $texts = array(
            "Here is an example of AppleScript:\n\n    tell application 'Foo'\n        beep\n    end tell",
            "    <div class='footer'>\n        &copy; 2004 Foo Corporation\n    </div>",
            "```\nThis is my code ```");

        $this->assertTexts($texts);
    }

    /**
     * @test
     */
    public function shouldHandleEntities()
    {
        $m = new LudoMarkDown();

        $this->assertEquals("<p>&copy;</p>\n", $m->get("&copy;"));
        $this->assertEquals("<p>AT&amp;T</p>\n", $m->get("AT&T"));
    }

    /**
     * @test
     */
    public function shouldHandleTagBrackets()
    {
        $m = new LudoMarkDown();

        $this->assertEquals("<p>4 &lt; 5</p>\n", $m->get("4 < 5"));
        $this->assertEquals("<h1>", $m->get("<h1>"));
    }

    /**
     * @test
     */
    public function shouldHandleBlockQuote()
    {
        // when
        $texts = array(
            "> This is a blockquote with two paragraphs. Lorem ipsum dolor sit amet,\n> consectetuer adipiscing elit. Aliquam hendrerit mi posuere lectus.\n> Vestibulum enim wisi, viverra nec, fringilla in, laoreet vitae, risus.\n\n>\n>Donec sit amet nisl. Aliquam semper ipsum sit amet velit. Suspendisse\n> id sem consectetuer libero luctus adipiscing.",
            "> This is a blockquote with two paragraphs. Lorem ipsum dolor sit amet,\nconsectetuer adipiscing elit. Aliquam hendrerit mi posuere lectus.\nVestibulum enim wisi, viverra nec, fringilla in, laoreet vitae, risus.\n\n>Donec sit amet nisl. Aliquam semper ipsum sit amet velit. Suspendisse\nid sem consectetuer libero luctus adipiscing.",
            "> ## This is a header.\n>\n\n> 1.   This is the first list item.\n> 2.   This is the second list item.\n> \n> Here's some example code:\n>\n\n>     return shell_exec('echo \$input | \$markdown_script');"
        );

        $this->assertTexts($texts);
    }

    /**
     * @test
     */
    public function shouldHandleParagraphs()
    {

        $texts = array("Paragraph 1\n\nParagraph 2\nParagraph 3");

        $this->assertTexts($texts);
    }

    /**
     * @test
     */
    public function shouldHandleNestedBlockQuotes()
    {
        $texts = array("> This is the first level of quoting.\n>\n> > This is nested blockquote.\n>\n> Back to the first level.",
            "> ## This is a header.\n>\n> 1.   This is the first list item.\n> 2.   This is the second list item.\n>\n> Here's some example code:\n>\n>     return shell_exec('echo \$input | \$markdown_script');");

        $this->assertTexts($texts);
    }

    /**
     * @test
     */
    public function shouldHandleLists()
    {
        $texts = array(
            "*   Red\n*   Green\n*   Blue",
            "+   Red\n+   Green\n+   Blue",
            "-   Red\n-   Green\n-   Blue",
            "*   Bird\n\n*   Magic",
            "*   Lorem ipsum dolor sit amet, consectetuer adipiscing elit.\n   Aliquam hendrerit mi posuere lectus. Vestibulum enim wisi,\n   viverra nec, fringilla in, laoreet vitae, risus.\n*   Donec sit amet nisl. Aliquam semper ipsum sit amet velit.\nSuspendisse id sem consectetuer libero luctus adipiscing.",
            "*   Lorem ipsum dolor sit amet, consectetuer adipiscing elit.\nAliquam hendrerit mi posuere lectus. Vestibulum enim wisi,\nviverra nec, fringilla in, laoreet vitae, risus.\n*   Donec sit amet nisl. Aliquam semper ipsum sit amet velit.\nSuspendisse id sem consectetuer libero luctus adipiscing.",
            "*   A list item with a code block:\n\n        code goes here"
        );

        $this->assertTexts($texts);
    }

    /**
     * @test
     */
    public function shouldHandleOrderedLists()
    {
        $texts = array(
            "1.  Bird\n2.  McHale\n3.  Parish",
            "1.  Bird\n1.  McHale\n1.  Parish",
            "3.  Bird\n1.  McHale\n8.  Parish",
            "1.  This is a list item with two paragraphs. Lorem ipsum dolor\nsit amet, consectetuer adipiscing elit. Aliquam hendrerit\nmi posuere lectus.\n\nVestibulum enim wisi, viverra nec, fringilla in, laoreet\nvitae, risus. Donec sit amet nisl. Aliquam semper ipsum\nsit amet velit.\n\n2.  Suspendisse id sem consectetuer libero luctus adipiscing.",
            "1986\. What a great season."
        );


        $this->assertTexts($texts);
    }
}
