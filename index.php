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
        
        .btn-group{
            background-color: #6c757d;
            border-radius: 5px;
            padding: 5px;
        }

        .selected {
            background-color: rgb(54, 58, 54);
            color: white;
        }

        .dropdown-menu {
            background-color: #6c757d;
        }
        .dropdown-item {
            border-radius: 5px;
            color: whitesmoke;
        }
        .dropdown-item:hover{
            cursor: pointer;
        }

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
        #cat_root{
            display: block;
            padding: 0px;
            min-width: 0px;
        }
        #tree, #parents{
            margin-top: 12px;
        }
    </style>
</head>

<body>
    <?php
    echo
    '<div class="container-fluid">
        <div class="row">
            <div class="col" id="tree">' .
            $categories->display_categories($root_id) .
            '</div>
            </div>
            <div class="col" id="parents">' .
                $categories->display_parent($root_id, $root_id) .
            '</div>
        </div>
    </div>';
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
            if (!$(this).next().hasClass('selected')) {
                $(this).parents('.dropdown-menu').first().find('.selected').removeClass('selected');
            }
            let selection = $(this).first('dropdown-toggle');
            selection.toggleClass('selected');


            return false;
        });
        //Select all the links (parent and childs) that aren't the small icon
        $('.dropdown-item').on('click', function() {
            let id = $(this).find('input').val();

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