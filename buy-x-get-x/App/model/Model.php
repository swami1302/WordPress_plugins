<?php

namespace BXGX\App\model;

class Model
{
    public static function getProducts()
    {
        $search_query = isset($_POST['query']) ? sanitize_text_field($_POST['query']) : '';
        $args = [
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            's' => $search_query,
            'fields' => 'ids'
        ];


        return get_posts($args);

    }

    public static function getSelectedProducts()
    {
        $args = [
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => '_bxgx_products',
                    'value' => 'yes',
                    'compare' => '='
                ]
            ],
            'fields' => 'ids'
        ];

        return get_posts($args);
    }
}