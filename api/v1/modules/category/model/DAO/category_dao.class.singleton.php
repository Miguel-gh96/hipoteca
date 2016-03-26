<?php

class category_dao
{
    public static $_instance;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function getData_dao($db, $arrArgument) {

        $sql = "SELECT * from categories WHERE _id = '$arrArgument'";
        $stmt = $db->ejecutar($sql);

        return $db->listar($stmt);
    }


    public function getCategories_dao($db, $arrArgument) {

        $sql = "SELECT * from categories WHERE parent = '$arrArgument'";

        $stmt = $db->ejecutar($sql);
        return $db->listar($stmt);
    }

    public function getAncestors_dao($db, $arrArgument) {

        $sql = "SELECT * from
                (SELECT root._id  as root_name
                     , down1._id as down1_name
                     , down2._id as down2_name
                  from categories as root
                left outer
                  join categories as down1
                    on down1.parent = root._id
                left outer
                  join categories as down2
                    on down2.parent = down1._id
                 where root.parent = ''
                order
                    by root_name
                     , down1_name
                     , down2_name ) as t
                where t.down2_name = '".$arrArgument."' or t.down1_name = '".$arrArgument."' or t.root_name = '".$arrArgument."'";

        $stmt = $db->ejecutar($sql);
        return $db->listar($stmt);
    }

}