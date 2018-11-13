<?php

if (!class_exists('PHPUnit\DbUnit\TestCase')) {
    class_alias('PHPUnit_Extensions_Database_TestCase', 'PHPUnit\DbUnit\TestCase');
}
