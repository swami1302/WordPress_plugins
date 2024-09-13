<?php

namespace Dd\src\model;

defined("ABSPATH") or exit();
class DeleteDraftModel
{
    public static function deleteAllDrafts()
    {

        $args=[
            'post_type'=>'post',
            'post_status'=>'draft',
            'posts_per_page'=>-1,
        ];
        $draft = get_posts($args);
        foreach ($draft as $post) {
            wp_delete_post($post->ID, true);
        }


    }
}
