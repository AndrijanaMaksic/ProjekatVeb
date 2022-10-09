<?php

namespace vebProjekat\model;

use vebProjekat\core\Database;

class JewelryModel
{
    public function findAll(){
        $database = Database::connect();
        $queryString = "SELECT 
            jewelry.model, jewelry.price, jewelry_brand.brand, jewelry.id
        FROM jewelry
        INNER JOIN jewelry_brand on jewelry.brand_id=jewelry_brand.id";
        $query = mysqli_query($database,$queryString);
        return mysqli_fetch_all($query);
    }

    public function searchJewelry($text)
    {
        $db = Database::connect();
        $q = "SELECT jewelry.model, jewelry.price, jewelry_brand.brand FROM jewelry 
        INNER JOIN jewelry_brand on jewelry.brand_id=jewelry_brand.id
        WHERE model LIKE '%$text%';";
        $query = mysqli_query($db, $q);

        $array = mysqli_fetch_all($query);
        return $array;
    }

    public function getByType($type) {
        $db = Database::connect();
        $q = "SELECT jewelry.model, jewelry.price, jewelry_brand.brand FROM jewelry 
                INNER JOIN jewelry_brand on jewelry.brand_id=jewelry_brand.id
                INNER JOIN jewelry_type ON jewelry.type_id = jewelry_type.id
                WHERE jewelry_type.type='$type'";
        $query = mysqli_query($db, $q);
        $array = mysqli_fetch_all($query);
        return $array;
    }

    public function getTypes()
    {
        $db = Database::connect();

        $q = "SELECT type FROM jewelry_type;";
        $query = mysqli_query($db, $q);
        $types = mysqli_fetch_all($query);

        return $types;
    }

    public function jewelryInfo($id) {
        $db = Database::connect();
        $q = "SELECT 
                jewelry.model, 
                jewelry.price,
                jewelry_brand.brand, 
                jewelry_gender.pol, 
                jewelry_color.color,
                jewelry.id
            FROM jewelry
            INNER JOIN jewelry_brand ON jewelry.brand_id = jewelry_brand.id
            INNER JOIN jewelry_gender ON jewelry.gender_id = jewelry_gender.id
            INNER JOIN jewelry_color ON jewelry.color_id = jewelry_color.id
            WHERE jewelry.id=$id";
        $query = mysqli_query($db, $q);
        return mysqli_fetch_all($query)[0];
    }

    public function getBrandId($brand) {
        $db = Database::connect();

        $q = "SELECT id FROM jewelry_brand WHERE jewelry_brand.brand = '$brand';";
        $query = mysqli_query($db, $q);
        $types = mysqli_fetch_all($query);

        return $types[0][0];

    }

    public function getGenderId($gender) {
        $db = Database::connect();

        $q = "SELECT id FROM jewelry_gender WHERE jewelry_gender.pol = '$gender';";
        $query = mysqli_query($db, $q);
        $types = mysqli_fetch_all($query);

        return $types[0][0];

    }

    public function getColorId($color) {
        $db = Database::connect();

        $q = "SELECT id FROM jewelry_color WHERE jewelry_color.color = '$color';";
        echo $q;
        $query = mysqli_query($db, $q);
        $types = mysqli_fetch_all($query);

        return $types[0][0];
    }

    public function getTypeId($type) {
        $db = Database::connect();

        $q = "SELECT id FROM jewelry_type WHERE jewelry_type.type = '$type';";
        $query = mysqli_query($db, $q);
        $types = mysqli_fetch_all($query);

        return $types[0][0];
    }
    public function insertJewelry($model, $price, $brand, $gender, $color, $type) {
        $db = Database::connect();
        $brand_id = $this->getBrandId($brand);
        $color_id = $this->getColorId($color);
        $gender_id = $this->getGenderId($gender);
        $type_id = $this->getTypeId($type);
        $q = "INSERT INTO jewelry(model, price, brand_id, gender_id, color_id, type_id) VALUES 
        ('$model', '$price', '$brand_id', '$gender_id', '$color_id', '$type_id');";
        $query = mysqli_query($db, $q);
    }

    public function deleteJewelry($id) {
        $db = Database::connect();
        $q = "DELETE FROM jewelry WHERE id='$id';";
        $query = mysqli_query($db, $q);
    }
}