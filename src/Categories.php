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
     * Stores categories in a array like-tree shape
     * @param array $categories_list array containing the categories with their info
     */
    public function saveCats(array $categories_list)
    {
        $parents_data = [];

        $root_depth = Db::getInstance()->getValue('SELECT `level_depth` FROM `ps_category` WHERE `is_root_category` = 1 ');
        $longest_depth = Db::getInstance()->getValue('SELECT `level_depth` FROM `ps_category` ORDER BY `level_depth` DESC ');

        for ($i = $root_depth; $i <= $longest_depth; $i++) {
            $parents_data[$i] = "";
        }

        $cat_arranged = [];
        foreach ($categories_list as $cat_id => $cat_values) {

            $category_id = $cat_id;
            $node_size = (int) $cat_values['nright'] - (int) $cat_values['nleft']; 
            $parent = $node_size === 1 ? false : true; //Greater than 1, category is a parent. 1 category isn't a parent

            $category_name = $cat_values['name'];
            $depth = $cat_values['level_depth'];
            $insert = $category_id . ' ' . $category_name;
            //0 depth is index which appends main and the rest of the tree elements.
            switch ($depth) {
                case 1:
                    $cat_arranged[$insert] = [];
                    $parents_data[1] = $insert;
                    break;
                case 2:
                    $parent ? $cat_arranged[$parents_data[1]][$insert] = [] : $cat_arranged[$parents_data[1]][$insert] = "";
                    $parents_data[2] = $insert;
                    break;
                case 3:
                    $parent ? $cat_arranged[$parents_data[1]][$parents_data[2]][$insert] = [] : $cat_arranged[$parents_data[1]][$parents_data[2]][$insert] = "";
                    $parents_data[3] = $insert;
                    break;
                case 4:
                    $parent ? $cat_arranged[$parents_data[1]][$parents_data[2]][$parents_data[3]][$insert] = [] : $cat_arranged[$parents_data[1]][$parents_data[2]][$parents_data[3]][$insert] = "";
                    $parents_data[4] = $insert;
                    break;
                case 5:
                    $parent ? $cat_arranged[$parents_data[1]][$parents_data[2]][$parents_data[3]][$parents_data[4]][$insert] = [] : $cat_arranged[$parents_data[1]][$parents_data[2]][$parents_data[3]][$parents_data[4]][$insert] = "";
                    $parents_data[5] = $insert;
                    break;
                case 6:
                    $parent ? $cat_arranged[$parents_data[1]][$parents_data[2]][$parents_data[3]][$parents_data[4]][$parents_data[5]][$insert] = [] : $cat_arranged[$parents_data[1]][$parents_data[2]][$parents_data[3]][$parents_data[4]][$parents_data[5]][$insert] = "";
                    break;
                default:
                    break;
            }
        }
        return $cat_arranged;
    }
}
