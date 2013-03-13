<?php
/**
 * Comment pending.
 * User: Alf Magne Kalleland
 * Date: 13.03.13
 * Time: 21:08
 */
class LudoMarkDown
{
    public function get($text){
        $text = $this->parseBold($text);
        $text = $this->parseItalic($text);
        $text = $this->parseHeadings($text);
        $text = $this->parseInlineCode($text);
        $text = $this->insertParagraphs($text);
        return $text;
    }

    private function insertParagraphs($text){
        $ret = "";
        $tokens = explode("\n", $text);
        foreach($tokens as $token){
            if(substr($token,0,2) != '<h')$ret.="<p>".$token."</p>";
            else $ret.=$token;
            $ret.="\n\n";
        }
        return rtrim($ret,"\n\n")."\n";


    }

    private function parseBold($text){
        return $this->parseTag($text, "__", 'strong');

    }

    private function parseItalic($text){
        return $this->parseTag($text, "_", "em");
    }

    private function parseHeadings($text){
        $tokens = explode("\n", $text);
        $ret = array();
        foreach($tokens as $token){
            $text = ltrim($token,"#");
            $headingLevel = strlen($token) - strlen($text);
            if($headingLevel > 0){
                $text = "<h". $headingLevel.">". rtrim($text,"#")."</h". $headingLevel.">";
            }
            $ret[] = $text;
        }
        return implode("\n", $ret);
    }

    private function parseTag($text, $markdown, $tag){
        $htmlTags = array("</". $tag . ">", "<".$tag.">");
        $tokens = explode($markdown, $text);
        $ret = "";
        for($i=0,$count=count($tokens);$i<$count;$i++){
            if($i)$ret.=$htmlTags[$i%2];
            $ret.= $tokens[$i];
        }

        return $ret;
    }

    private function parseInlineCode($text){
        $ret = $this->parseTag($text, "``", "code");
        return $this->parseTag($ret, "`", "code");
    }
}
