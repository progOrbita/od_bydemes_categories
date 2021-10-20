<?php

namespace OrbitaDigital\OdBydemesCategories;

use Db;

class Categories
{

    private $parents_list = [];
    private $all_cats = [];

    public function getCategories()
    {

        //Ordered by nleft. It goes from the first category to the last one in the tree.
        $cat_query = Db::getInstance()->executeS('SELECT ca.id_category, ca.id_parent, ca.level_depth, cal.name
        FROM `' . _DB_PREFIX_ . 'category` ca 
        INNER JOIN `' . _DB_PREFIX_ . 'category_lang` cal ON ca.id_category = cal.id_category WHERE cal.id_lang = 1 ORDER BY `nleft` ASC');

        //key -> id_category. value are the fields: id_category, id_parent, and name from category_lang
        $parents_list = [];
        if ($cat_query === false) {
            die('<p>Error somewhere in the categories query</p>');
        }
        foreach ($cat_query as $key => $value) {
            $cat_parent = $value['id_parent'];
            $cat_id = $value['id_category'];

            $parents_list[$cat_parent][] = $cat_id;
            $this->all_cats[$value['id_category']] = $value;
        }
        $cat_root = Db::getInstance()->getValue('SELECT id_category FROM `' . _DB_PREFIX_ . 'category` WHERE is_root_category = 1');
        $root_depth = Db::getInstance()->getValue('SELECT level_depth FROM `' . _DB_PREFIX_ . 'category` WHERE is_root_category = 1');
        $this->parents_list = $parents_list;

        $this->display_categories($cat_root, $root_depth);
        return;
    }

    /**
     * Read categories recursively.
     */
    public function display_categories(int $root, int $current_level, int $previous_level = 0)
    {

        echo '<li>' . $root . ' ' . $this->all_cats[$root]['name'] . '</li>';
        //Avoid entering in elements who aren't in parents. For each parent this if is reproduced.
        //If is a child only will print <li> and do current_level--
        if (isset($this->parents_list[$root])) {

            echo '<ul>';

            //Previous level will takes current level as the actual one of the parent. To "restore" the tree depth
            $previous_level = $current_level;
            //Obtaining the childs of the parent
            $childs = $this->parents_list[$root];

            foreach ($childs as $child) {
                $this->display_categories($child, $this->all_cats[$child]['level_depth'], $previous_level);
            }
        }
        $current_level--;

        //Teorically, level go down everytime it exits a child, so if the previous level it's over current(child) level it will ends the <ul>. 
        //If it's a child nothing happen
        if ($current_level < $previous_level) {
            echo '</ul>';
        }
    }
}
