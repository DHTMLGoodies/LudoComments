<?php
/**
 * Comment pending.
 * User: Alf Magne Kalleland
 * Date: 13.03.13
 * Time: 21:01
 */
class LudoChannelComments extends LudoDBCollection implements LudoDBService
{
    protected $config = array(
        "sql" => "select * from ludo_comment where channel=?"
    );

    public function getValidServices(){
        return array("get");
    }

    public function get(){

    }

    public function shouldCache($service){
        return false;
    }

    public function validateArguments($service, $arguments){
        return count($arguments) === 1;
    }

    public function validateServiceData($service, $data){
        return empty($data);
    }

}
