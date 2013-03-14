<?php
/**
 * Comment pending.
 * User: Alf Magne Kalleland
 * Date: 13.03.13
 * Time: 21:08
 */
class LudoMarkDown
{
    private $blockElements = 'p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|address|script|noscript|form|fieldset|iframe|math|svg|article|section|nav|aside|hgroup|header|footer|figure';

    public function get($text)
    {
        $text = $this->addEntities($text);
        $text = $this->escapeSpecial($text);

        $text = $this->parseHeadings($text);
        $text = $this->parseUnorderedLists($text);
        $text = $this->parseBold($text);
        $text = $this->parseItalic($text);
        $text = $this->parseInlineCode($text);
        $text = $this->insertParagraphs($text);
        return $text;
    }

    private function addEntities($text)
    {
        $text = htmlentities($text);
        $specials = array("copy", "nbsp", "iexcl", "cent", "pound", "curren", "yen", "brvbar", "sect", "uml", "laquo", "not", "shy", "reg", "macr",
            "deg", "euro", "trade");
        foreach ($specials as $special) {
            $text = str_replace("&amp;" . $special, "&" . $special, $text);
        }
        return $text;
    }

    private function escapeSpecial($text)
    {
        $characters = array("\\", "`", "*", "_", "{", "}", "[", "]", "(", ")", "#", "+", "-", ".", "!");
        foreach ($characters as $character) {
            $text = str_replace("\\" . $character, "&#" . ord($character) . ";", $text);
        }
        return $text;
    }

    private function insertParagraphs($text)
    {
        $ret = "";
        $tokens = explode("\n", $text);
        foreach ($tokens as $token) {
            if (strlen($token) && !preg_match("/^<(" . $this->blockElements . ")/si", $token)) {
                $ret .= "<p>" . $token . "</p>";
            } else $ret .= $token;
            if (strlen($token)) $ret .= "\n\n";
        }
        return rtrim($ret, "\n\n") . "\n";
    }

    private function parseBold($text)
    {
        return $this->parseTag($text, array("__", "**"), 'strong');

    }

    private function parseItalic($text)
    {
        return $this->parseTag($text, array("_", "*"), "em");
    }

    private function parseUnorderedLists($text)
    {
        $tokens = explode("\n", $text);
        $ret = array();
        $started = false;
        foreach ($tokens as $token) {
            $text = ltrim($token, "*");
            if (trim($text)) {
                $headingLevel = strlen($token) - strlen($text);
                if ($headingLevel > 0) {
                    $text = ($started ? "" : "<ul>") . "<li>" . trim($text) . "</li>";
                    $started = true;
                } else {
                    if ($started) $text .= "</ul>";
                    $started = false;
                }
                $ret[] = $text;
            }
        }
        return implode("\n", $ret);
    }

    private function parseHeadings($text)
    {
        $tokens = explode("\n", $text);
        $ret = array();
        foreach ($tokens as $token) {
            $text = ltrim($token, "#");
            $headingLevel = strlen($token) - strlen($text);
            if ($headingLevel > 0) {
                $text = "<h" . $headingLevel . ">" . rtrim($text, "#") . "</h" . $headingLevel . ">";
            }
            $ret[] = $text;
        }
        return implode("\n", $ret);
    }

    private function parseTag($text, $markdown, $tag)
    {
        if (!is_array($markdown)) $markdown = array($markdown);

        $ret = "";
        foreach ($markdown as $markdownTag) {
            $ret = "";
            $htmlTags = array("</" . $tag . ">", "<" . $tag . ">");
            $tokens = explode($markdownTag, $text);
            $ret = "";
            for ($i = 0, $count = count($tokens); $i < $count; $i++) {
                if ($i) $ret .= $htmlTags[$i % 2];
                $ret .= $tokens[$i];
            }
            $text = $ret;
        }

        return $ret;
    }

    private function parseInlineCode($text)
    {
        $ret = $this->parseTag($text, "``", "code");
        return $this->parseTag($ret, "`", "code");
    }
}
