<?php

use OrbitaDigital\OdBydemesCategories\Categories;

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('_PS_VERSION_')) {
    require_once '../../config/config.inc.php';
    require_once '../../init.php';
}



$categories = new Categories();
$root_values = $categories->get_root_values();

echo "<pre><ul>";
echo $categories->display_categories($root_values['id_category'],$root_values['level_depth']);
echo "</ul></pre>";
