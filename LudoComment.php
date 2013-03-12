<?php
/**
 * Comment pending.
 * User: Alf Magne Kalleland
 * Date: 12.03.13
 * Time: 18:39
 */
class LudoComment extends LudoDBModel
{
    protected $config = array(
        "table" => "ludo_comment",
        "sql" => "select * from ludo_comment where id = ?",
        "columns" => array(
            "id" => "int auto_increment not null primary key",
            "comment" => array(
                "db" => "mediumtext",
                "access" => "rw"
            ),
            "name" => array(
                "db" => "varchar(255)",
                "access" => "rw"
            ),
            "email" => array(
                "db" => "varchar(255)",
                "access" => "rw"
            ),
            "ip" => array(
                "db" => "varchar(255)"
            ),
            "posted" => array(
                "db" => "datetime"
            ),
            "approved" => array(
                "db" => "char(1)"
            ),
            "channel" => array(
                "db" => "varchar(64)",
                "access" => "rw"
            )
        )
    );
}
