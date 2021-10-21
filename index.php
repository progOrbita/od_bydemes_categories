<?php

use OrbitaDigital\OdBydemesCategories\Categories;

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('_PS_VERSION_')) {
    require_once '../../config/config.inc.php';
    require_once '../../init.php';
}

$categories = new Categories();
$root_id = $categories->get_cat_root_id();

echo '<pre>';
echo $categories->display_parent(38,$root_values['id_category']);
echo '</pre>';
$categories->display_categories($root_id);
