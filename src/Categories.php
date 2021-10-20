<?php

class Categories
{
    public function getCategories()
    {
        //Ordered by nleft so it goes from the very first of all the elements to the last one through all the parents with their childs
        $cat_query = Db::getInstance()->executeS('SELECT ca.id_category, ca.id_parent, ca.nleft, ca.nright, ca.level_depth, ca.active, cal.name
        FROM `ps_category` ca 
        INNER JOIN `ps_category_lang` cal ON ca.id_category = cal.id_category WHERE cal.id_lang = 1 AND ca.active = 1 ORDER BY `nleft` ASC');

        //key -> id_category. value are the fields: id_category, id_parent, level_depth, nleft, nright and name from category_lang
        $cat_list = [];
        if ($cat_query === false) {
            die('<p>Error somewhere in the categories query</p>');
        }
        foreach ($cat_query as $key => $value) {
            $cat_list[$value['id_category']] = $value;
        }
        return $cat_list;
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
