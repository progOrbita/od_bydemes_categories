<?php

use OrbitaDigital\OdBydemesCategories\Categories;

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('_PS_VERSION_')) {
    require_once '../../config/config.inc.php';
    require_once '../../init.php';
}



$categories = new Categories();
$cats = $categories->getCategories();
print "<pre>";
print_r($categories->display_categories($root));
print "</pre>";