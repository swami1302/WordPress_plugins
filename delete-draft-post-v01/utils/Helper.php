<?php
namespace Dd\utils;

class Helper {
    /**
     * Check if the current admin page matches the given slug.
     *
     * @param string $slug The slug to check.
     * @return bool True if the current page matches the slug, false otherwise.
     */
    public static function isCurrentPage($slug) {
        return isset($_GET['page']) && $_GET['page'] === $slug;
    }
}
