<?php

use OrbitaDigital\OdBydemesCategories\Categories;

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('_PS_VERSION_')) {
    require_once '../../config/config.inc.php';
    require_once '../../init.php';
}



$categories = new Categories();
print "<pre><ul>";
print_r($categories->getCategories());
print "</ul></pre>";