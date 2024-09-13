<?php
namespace Dd\src;
use Dd\src\control\DeleteDraftControl;
defined("ABSPATH") or exit();

class Router{

    public function init()
    {
        add_action('init',[DeleteDraftControl::class, 'scheduleDraftDeletion']);
        add_action('admin_menu', [DeleteDraftControl::class, 'showMenu']);
        add_action('delete_draft_posts_daily_v01', [DeleteDraftControl::class, 'deleteDraftPosts']);
    }


}
