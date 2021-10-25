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

    //string that will contains the category tree
    private $tree_info = '';

    //spain lang id and iso code
    private $lang_es;

    private $arr_parents = [];

    /**
     * Construct, initialize parents, categories arrays and obtains the id of the category root.
     */

    function __construct()
    {
        $this->lang_es = Db::getInstance()->getRow('SELECT `id_lang`, `iso_code` FROM ' . _DB_PREFIX_ . 'lang WHERE `iso_code` = "es"');

        $categories_data = $this->find_categories();
        if (!$categories_data) {
            die('<p>Error somewhere in the categories query</p>');
        }
        $this->findCategoryRoot();
        if (!$this->root_id) {
            die('<p>Id of category root not found</p>');
        }

        $this->generateArrays($categories_data);
    }
    private function find_categories()
    {

        $cat_query = Db::getInstance()->executeS('SELECT ca.id_category, ca.id_parent, ca.level_depth, ca.active, cal.name
        FROM `' . _DB_PREFIX_ . 'category` ca 
        INNER JOIN `' . _DB_PREFIX_ . 'category_lang` cal ON ca.id_category = cal.id_category WHERE cal.id_lang = ' . $this->lang_es['id_lang'] . ' ORDER BY `nleft` ASC');

        return $cat_query;
    }

    private function findCategoryRoot()
    {
        $this->root_id = (int) Db::getInstance()->getValue('SELECT id_category FROM `' . _DB_PREFIX_ . 'category` WHERE is_root_category = 1');
    }

    private function generateArrays(array $cats_info)
    {
        //key -> id_category. value are the fields: id_category, id_parent, and name from category_lang
        foreach ($cats_info as $cat_values) {
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
        //Tags at beggining
        if ($parent == $this->root_id) {
            $this->tree_info .= '<div id="cat_root" class="dropdown-menu">
                <div class="btn-group dropright">
                    <a class="dropdown-item"><input type="hidden" value="' . $parent . '">' . $this->all_cats[$parent]['name'] . '</a>
                <a class="btn btn-secondary dropdown-toggle dropdown-toggle-split" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"></a> ';
        }
        if (!isset($this->parents_list[$parent])) {
            $this->tree_info .= '<a class="dropdown-item"><input type="hidden" value="' . $parent . '"> ' . $this->all_cats[$parent]['name'] . '</a>';
        }

        if (isset($this->parents_list[$parent])) {
            if ($parent != $this->root_id) {
                $this->tree_info .= '
                <li class="dropdown-submenu">
                    <div class="btn-group dropright">
                        <a class="dropdown-item">
                        <input type="hidden" value="' . $parent . '">
                        ' . $this->all_cats[$parent]['name'] . '
                        </a>
                    <a class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"></a>
                ';
            }
            $this->tree_info .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
            //Obtaining the childs of the parent
            $childs = $this->parents_list[$parent];

            foreach ($childs as $child) {
                $this->display_categories((int) $child);
            }
            if ($parent != $this->root_id) {
                $this->tree_info .= '
                        </ul>
                    </div>
                </li>';
            }
            //Returns the tree, adding end tags
            if ($parent === $this->root_id) {
                return $this->tree_info . '</ul></div>';
            }
        }
    }

    /**
     * Finds the parents from the specified category until reaching the root category.
     * shows the current category and then calls again the function with the id_parent of that category, until at some point reaching the root.
     * @param int $id_category id of the category to find the parents
     * @param int $root id of the root category to stop the process
     */
    public function display_parent(int $id_category, int $root)
    {
        $cat_name = strtolower($this->all_cats[$id_category]['name']);
        //cleanup troublesome header characters
        $cat_replaced = str_replace(['"'],'',$cat_name);
        $cat_clean = str_replace([' / ',' ', '(', ')','-&-','/'], '-', $cat_replaced);
        if($this->all_cats[$id_category]['active'] == 1){
        $this->arr_parents[$id_category] = '<a href="' . _PS_BASE_URL_ . __PS_BASE_URI__ . $this->lang_es['iso_code'] . '/' . $id_category . '-' . $cat_clean . '">' . $this->all_cats[$id_category]['name'] . '</a>';
        }
        else{
            $this->arr_parents[$id_category] = $this->all_cats[$id_category]['name'];
        }
        $new_par = $this->all_cats[$id_category]['id_parent'];

        if ($id_category != $root) {
            return $this->display_parent((int)$new_par, $root);
        }

        return '<pre>' . implode(' -> ', array_reverse($this->arr_parents)) . '</pre>';
    }
}
