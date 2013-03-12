<?php
/**
 * Comment pending.
 * User: Alf Magne Kalleland
 * Date: 12.03.13
 * Time: 18:48
 */
class LudoComments extends LudoDBCollection
{
    protected $config = array(
        "sql" => "select * from comments "

    );
}
