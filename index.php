<html>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <style>
        /**To make submenus in bootstrap because by default only can have one level, nested menus doesnt work */
        .dropdown-submenu {
            position: relative;
        }

        .dropdown-submenu a::after {
            transform: rotate(-90deg);
            position: absolute;
            right: 6px;
            top: .8em;
        }

        .dropdown-submenu .dropdown-menu {
            top: 0;
            left: 100%;
            margin-left: .1rem;
            margin-right: .1rem;
        }
    </style>
</head>

use OrbitaDigital\OdBydemesCategories\Categories;

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('_PS_VERSION_')) {
    require_once '../../config/config.inc.php';
    require_once '../../init.php';
}

$categories = new Categories();
$root_id = $categories->get_cat_root_id();

echo $categories->display_parent(38, $root_id);
echo $categories->display_categories($root_id);

?>

</body>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script>
        $(document).ready(function(){

            $(document).on('click','.disabled',function(){
                $(this).find("i").remove();

                $(this).append('<i class="bi bi-arrow-down"></i>');
                $(this).addClass('active');
                $(this).removeClass('disabled');
            });

            $(document).on('click','.active',function(){
                $(this).find("i").remove();
                $(this).append('<i class="bi bi-arrow-right"></i>');
                $(this).addClass('disabled');
                $(this).removeClass('active');
            });

        });
    </script>
</html>