<?php

class Categories
{

    private $parents_list = [];
    private $all_cats = [];

    public function getCategories()
    {
        //Ordered by nleft so it goes from the very first of all the elements to the last one through all the parents with their childs
        $cat_query = Db::getInstance()->executeS('SELECT ca.id_category, ca.id_parent, cal.name
        FROM `ps_category` ca 
        INNER JOIN `ps_category_lang` cal ON ca.id_category = cal.id_category WHERE cal.id_lang = 1 ORDER BY `nleft` ASC');

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
        $this->cat_root = Db::getInstance()->getValue('SELECT id_category FROM `ps_category` WHERE is_root_category = 1');
        $this->parents_list = $parents_list;
        
        return $parents_list;
    }

    /**
     * Read categories recursively
     */
    public function get_categories(int $root)
    {
        echo '<ul>';
        echo '<li>'.$root.' '.$this->all_cats[$root]['name'].'</li>';

        $temp = $this->parents_list[$root];

        foreach ($temp as $key => $value) {
            $this->get_categories($value);
        }

        echo '</ul>';

    }
}
