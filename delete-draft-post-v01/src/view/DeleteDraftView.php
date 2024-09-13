<div class="wrap">
    <h1>Delete Draft Posts</h1>
    <p>Current time: <?php echo esc_html($current_time); ?></p>
    <h2>Deleted Draft Posts</h2>
    <p>Next scheduled deletion: <?php echo esc_html($nextSchedule); ?></p>

    <ul>
        <?php if (!empty($deleted_drafts)) : ?>
            <?php foreach ($deleted_drafts as $draft_name) : ?>
                <li><?php echo esc_html($draft_name); ?></li>
            <?php endforeach; ?>
        <?php else : ?>
            <li>No drafts were deleted.</li>
        <?php endif; ?>
    </ul>
</div>

