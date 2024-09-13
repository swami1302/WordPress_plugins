<?php
namespace Dd\src\control;
use Dd\src\model\DeleteDraftModel;
use Dd\utils\Helper;

defined("ABSPATH") or exit();
defined('MY_PLUGIN_PATH') or define('MY_PLUGIN_PATH', plugin_dir_path(__FILE__));

class DeleteDraftControl {

    public static function deleteDraftPosts()
    {
        DeleteDraftModel::deleteAllDrafts();
    }
    public static function scheduleDraftDeletion()
    {
        if (!wp_next_scheduled('delete_draft_posts_daily_v01')) {
            // Gets current time in mysql format as string
            $current_time = current_time('mysql');
            $next_10am = date('Y-m-d 10:00:00', strtotime($current_time));

            if ($next_10am <= $current_time) {
                $next_10am = date('Y-m-d 10:00:00', strtotime('+1 day', strtotime($current_time)));
            }

            // Convert the local date and time to GMT/UTC
            $gmt_time = get_gmt_from_date($next_10am);
            $scheduled = wp_schedule_event(strtotime($gmt_time), 'daily', 'delete_draft_posts_daily_v01');
            error_log('Scheduled Event: ' . ($scheduled ? 'Success' : 'Failure'));
        }
    }

    public static function showMenu(){
        add_menu_page(
            __('Delete Draft Posts','dd-delete-draft'),
            __('Delete Draft Posts','dd-delete-draft'),
            'manage_options',
            'delete-draft-posts',
            [self::class, 'DeleteDraftPostsPage'],
            'dashicons-trash',
            6
        );
    }

    public static function DeleteDraftPostsPage(){
        if (Helper::isCurrentPage('delete-draft-posts')) {
            $current_time = current_time('mysql');
            $current_timestamp = current_time('timestamp');
            $next_10am = strtotime('10:00:00', $current_timestamp);

            if ($next_10am <= $current_timestamp) {
                $next_10am = strtotime('tomorrow 10:00:00', $current_timestamp);
            }

            $nextSchedule = date('Y-m-d H:i:s', $next_10am);
//            echo MY_PLUGIN_PATH . 'src/view/DeleteDraftView.php';
//            exit();
            require MY_PLUGIN_PATH . 'src/view/DeleteDraftView.php';
        }
    }
}
