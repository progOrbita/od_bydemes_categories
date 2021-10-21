<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
        <style>

            .disabled + ul{
                display: none;
            }
            .active + ul{
                display: block;
            }

            .active:hover, .disabled:hover{
                cursor: pointer;
            }
        </style>
    </head>
<body>

<?php

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