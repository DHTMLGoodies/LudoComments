<?php

require_once(__DIR__."/../autoload.php");
error_reporting(E_ALL);
ini_set('display_errors','on');
class CommentAllTests
{
    public static function main()
    {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->setName('AllTests');
        $suite->addTestSuite("MarkDownTest");

        return $suite;
    }
}