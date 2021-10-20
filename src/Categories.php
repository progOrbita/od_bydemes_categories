<?php

declare(strict_types=1);

namespace OrbitaDigital\OdBydemesCategories;

use Db;

class Categories
{

    private $parents_list = [];
    private $all_cats = [];

    /**
     * Construct, initialize parents and categories arrays
     */
    
    function __construct()
    {
        $cat_query = Db::getInstance()->executeS('SELECT ca.id_category, ca.id_parent, ca.level_depth, cal.name
        FROM `' . _DB_PREFIX_ . 'category` ca 
        INNER JOIN `' . _DB_PREFIX_ . 'category_lang` cal ON ca.id_category = cal.id_category WHERE cal.id_lang = 1 ORDER BY `nleft` ASC');

        //key -> id_category. value are the fields: id_category, id_parent, and name from category_lang
        $parents_list = [];
        if ($cat_query === false) {
            die('<p>Error somewhere in the categories query</p>');
        }
        foreach ($cat_query as $cat_values) {
            $cat_parent = $cat_values['id_parent'];
            $cat_id = $cat_values['id_category'];

            $parents_list[$cat_parent][] = $cat_id;
            $this->all_cats[$cat_values['id_category']] = $cat_values;
        }
        $cat_root = (int) Db::getInstance()->getValue('SELECT id_category FROM `' . _DB_PREFIX_ . 'category` WHERE is_root_category = 1');
        $root_depth = (int) Db::getInstance()->getValue('SELECT level_depth FROM `' . _DB_PREFIX_ . 'category` WHERE is_root_category = 1');
        $this->parents_list = $parents_list;
    }

    }

    /**
     * Read categories recursively.
     * Prints the element, if it's a parent add a new level, being the current_level(depth) the same than the parent.
     * If finished reading the childs, current_level go down, at that point
     */
    public function display_categories(int $root, int $current_level, int $previous_level = 0)
    {

        echo '<li>' . $root . ' ' . $this->all_cats[$root]['name'] . '</li>';
        //Avoid entering in elements who aren't in parents. For each parent enter in the loop
        //If is a child it will only print <li>
        if (isset($this->parents_list[$root])) {

            echo '<ul>';

            //Previous level will takes current level as the actual one of the parent. To "restore" the tree depth
            $previous_level = $current_level;
            //Obtaining the childs of the parent
            $childs = $this->parents_list[$root];

            /**
             * A small explanation
             * Current_level go up everytime it ends a parent,  so current_level will be lesser than previous and it will add the <ul> of the parent end.
             */
            foreach ($childs as $child) {
                $this->display_categories((int) $child, (int) $this->all_cats[$child]['level_depth'], (int) $previous_level);
                $current_level--;
            }
            if ($current_level < $previous_level) {
                echo '</ul>';
            }
        }
    }
}
