<?php
namespace classes;

interface TemplateClasses
{
    //public function getId();
    public function insert();
    public function update();
    public function delete();
    public static function table();
    public static function listar(array $fields, array $args, $type);
}
