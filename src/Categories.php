<?php

declare(strict_types=1);

namespace OrbitaDigital\OdBydemesCategories;

use Db;

class Categories
{
    //Id of root category
    private $root_id;

    //Parents and their childs as elements
    private $parents_list = [];

    //Data of all the categories
    private $all_cats = [];

    /**
     * Construct, initialize parents, categories arrays and obtains the id of the category root.
     */

    function __construct()
    {
        $cat_query = Db::getInstance()->executeS('SELECT ca.id_category, ca.id_parent, ca.level_depth, cal.name
        FROM `' . _DB_PREFIX_ . 'category` ca 
        INNER JOIN `' . _DB_PREFIX_ . 'category_lang` cal ON ca.id_category = cal.id_category WHERE cal.id_lang = 1 ORDER BY `nleft` ASC');

        if ($cat_query === false) {
            die('<p>Error somewhere in the categories query</p>');
        }
        $this->root_id = Db::getInstance()->getValue('SELECT id_category FROM `' . _DB_PREFIX_ . 'category` WHERE is_root_category = 1');

        if(!$this->root_id){
            die('<p>Id of category root not found</p>');
        }

        //key -> id_category. value are the fields: id_category, id_parent, and name from category_lang
        foreach ($cat_query as $cat_values) {
            $cat_parent = $cat_values['id_parent'];
            $cat_id = $cat_values['id_category'];

            $this->parents_list[$cat_parent][] = $cat_id;
            $this->all_cats[$cat_id] = $cat_values;
        }
    }

    /**
     * Obtains the id of the root category
     * @return int id of the category root
     */
    public function get_cat_root_id()
    {
        return $this->root_id;
    }

    /**
     * Read categories recursively.
     * Prints the element, if is a child, will print only the <li>
     * parents will print <ul> at the beggining and </ul> when they ends
     * @param int $parent the id_category of the parent or child
     */
    public function display_categories(int $parent)
    {
        echo '<li>' . $parent . ' ' . $this->all_cats[$parent]['name'] . '</li>';

        if (isset($this->parents_list[$parent])) {

            echo '<ul>';

            //Obtaining the childs of the parent
            $childs = $this->parents_list[$parent];

            /**
             * Childs will only display the <li>, parents will print <ul> and then </ul> when it ends
             */
            foreach ($childs as $child) {
                $this->display_categories((int) $child);
            }

            echo '</ul>';

            }
        }
    }

     * Finds the parents from the specified category until reaching the root category.
     * shows the current category and then calls again the function with the id_parent of that category, until at some point reaching the root.
     * @param int $id_category id of the category to find the parents
     * @param int $root id of the root category to stop the process
     */
    public function display_parent(int $id_category, int $root){
        echo $this->all_cats[$id_category]['name'].' -> ';
        $new_par = $this->all_cats[$id_category]['id_parent'];

        if($id_category == $root){
            return;
        }
        $this->display_parent((int)$new_par,$root);
    }
}
