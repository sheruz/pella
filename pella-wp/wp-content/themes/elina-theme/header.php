<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Glancyr font - if custom font files are available, add @font-face here -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <div class="site-logo">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                Elina Szmrtyka
            </a>
        </div>
        <nav class="site-nav">
            <?php
            // Only show Projects link if plugin is active and post type exists
            if (post_type_exists('project')) {
                // Find the page using "Projects List" template
                $projects_page = get_pages(array(
                    'meta_key' => '_wp_page_template',
                    'meta_value' => 'page-projects.php'
                ));
                
                if (!empty($projects_page)) {
                    $projects_url = get_permalink($projects_page[0]->ID);
                    echo '<a href="' . esc_url($projects_url) . '">Projects</a>';
                } else {
                    // Fallback: try to find by slug or use archive URL
                    $projects_page = get_page_by_path('projects');
                    if ($projects_page) {
                        echo '<a href="' . esc_url(get_permalink($projects_page->ID)) . '">Projects</a>';
                    } else {
                        // Use post type archive as fallback
                        $archive_url = get_post_type_archive_link('project');
                        if ($archive_url) {
                            echo '<a href="' . esc_url($archive_url) . '">Projects</a>';
                        }
                    }
                }
            }
            ?>
            <a href="#">Blog</a>
        </nav>
    </div>
</header>
