<html>
    <head>
        <meta charset="UTF-8">
        <style>
            .red{
                color: red;
            }
            .red:hover, .green:hover{
                cursor: pointer;
            }
            .red + ul{
                display: none;
            }
            .green{
                color: green;
            }
            .green + ul{
                display: block;
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
            $(document).on('click','.red',function(){
                $(this).addClass('green');
                $(this).removeClass('red');
            });
            $(document).on('click','.green',function(){
                $(this).addClass('red');
                $(this).removeClass('green');
            });
        });
    </script>
</html>