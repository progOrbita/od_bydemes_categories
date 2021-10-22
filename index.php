<?php

use OrbitaDigital\OdBydemesCategories\Categories;

require_once __DIR__ . '/vendor/autoload.php';

if (!defined('_PS_VERSION_')) {
    require_once '../../config/config.inc.php';
    require_once '../../init.php';
}

$categories = new Categories();

$root_id = $categories->get_cat_root_id();

if (isset($_POST['id_cat'])) {
    echo $categories->display_parent($_POST['id_cat'], $root_id);
    exit();
}

?>
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

    echo $categories->display_parent(722, $root_id);
    echo $categories->display_categories($root_id);

    ?>

</body>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.bundle.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script>
    $(document).ready(function() {
        //Dropdown-submenu
        $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
            if (!$(this).next().hasClass('show')) {
                $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
            }
            var $subMenu = $(this).next('.dropdown-menu');
            $subMenu.toggleClass('show');

            return false;
        });
        //Select all the childs that aren't the small tag
        $('a:not(a.dropdown-toggle-split').on('click', function() {
            let id = $(this).find('input').val();
            console.log(id);
            let ajaxRequest = $.ajax({
                url: 'index.php',
                context: document.body,
                data: {
                    'id_cat': id
                },
                type: "POST"
            });
            ajaxRequest.done(function(data) {
                $('#parents').empty();
                $('#parents').append(data);
            });
        });
    });
</script>

</html>