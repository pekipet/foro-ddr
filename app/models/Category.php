<?php

class Category {

    function __construct()
    {
        
    }

    function getCategories($idParent = null){

        $sql = "SELECT * ";
        $sql .= "FROM categories ";

        if(isset($idParent)){
            $sql .= "WHERE id = " .$idParent. " or parent_cat = " .$idParent;
        }

        $db = new MySQLDB();

        $datadb = $db->getData($sql);

        $data = array();
        $data['categories'] = array();

        if(isset($idParent)){
            foreach ($datadb  as $key => $value) {
                if($value['id'] === $idParent){
                    array_push($data['categories'], $value);
                }
            }
        }else{
            foreach ($datadb  as $key => $value) {
                if($value['id'] === $value['parent_cat']){
                    array_push($data['categories'], $value);
                }
            }
        }


        foreach ($data['categories'] as $key => $value) {

            $idParent = $value['id'];

            $child = array_filter($datadb, function($element) use ($idParent){
                return $element['parent_cat'] === $idParent && $element['id'] != $element['parent_cat'];
            });

            $data['categories'][$key]['child'] = $child;

        }


        $db->close();

        return $data;



    }

}
