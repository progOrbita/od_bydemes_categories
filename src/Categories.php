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
        $this->parents_list = $parents_list;
    }

    /**
     * Obtains the id and depth of the root category
     */
    public function get_root_values()
    {
        return Db::getInstance()->getRow('SELECT id_category, level_depth FROM `' . _DB_PREFIX_ . 'category` WHERE is_root_category = 1');
    }

    /**
     * Read categories recursively.
     * Prints the element, if it's a parent add a new level, being the current_level(depth) the same than the parent.
     * If is a child, will print only the <li>
     * @param int $parent the id_category of the parent
     * @param int $current_level actual level_depth (field) of the category
     * @param int $previous_level previously level_depth stored in the function. Start with level 0
     */
    public function display_categories(int $parent, int $current_level, int $previous_level = 0)
    {

        echo '<li>' . $parent . ' ' . $this->all_cats[$parent]['name'] . '</li>';

        //Avoid entering in elements who aren't in parents. The parents enter in the loop
        if (isset($this->parents_list[$parent])) {

            echo '<ul>';

            //Previous level will takes current level as the actual one of the parent. To "restore" the tree depth
            $previous_level = $current_level;
            //Obtaining the childs of the parent
            $childs = $this->parents_list[$parent];

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

    public function display_parent(int $val){

        
        echo ' '.$this->all_cats[$val]['name'].' -> ';
        $new_par = $this->all_cats[$val]['id_parent'];
        if($val == 1){
            
            return;
        }
        $this->display_parent((int)$new_par);
    }
}
