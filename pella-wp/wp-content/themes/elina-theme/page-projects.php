<?php
/**
 * Template Name: Projects List
 *
 * Description: A page template that displays all custom 'Project' posts.
 */

get_header(); ?>

<main class="site-main projects-list-page">
    <div class="container">
        
        <header class="page-header">
            <h1 class="section-title text-center">C E R T I F I C A T I O N S &nbsp;&nbsp;&amp;&nbsp;&nbsp; P A C K A G E S</h1>
        </header>

        <div class="certifications-grid">
            <?php
            // Query arguments for projects
            $args = array(
                'post_type'      => 'project',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'date',
                'order'          => 'DESC',
                'no_found_rows'  => true, // Optimize query
            );

            $projects_query = new WP_Query( $args );
            
            // Debug: Uncomment to check if query is working
            // echo '<!-- Found ' . $projects_query->found_posts . ' projects -->';

            if ( $projects_query->have_posts() ) :
                while ( $projects_query->have_posts() ) : $projects_query->the_post();
                    
                    $short_desc = get_post_meta( get_the_ID(), '_project_short_description', true );
                    $package_info = get_post_meta( get_the_ID(), '_project_package_info', true );
                    $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
                    ?>
                    
                    <div class="certificate-item scroll-fade-in-item">
                        <a href="<?php the_permalink(); ?>" class="project-image-link">
                            <?php if ( $thumbnail_url ) : ?>
                                <img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
                            <?php else : ?>
                                <div style="min-width: 400px; height: 300px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999;">
                                    <?php _e( 'No Image', 'elina-theme' ); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="project-overlay-info">
                                <h3 class="project-overlay-title"><?php the_title(); ?></h3>
                                <?php if ( $package_info ) : ?>
                                    <div class="project-overlay-package"><?php echo esc_html( $package_info ); ?></div>
                                <?php endif; ?>
                            </div>
                        </a>
                    </div>

                <?php endwhile;
                wp_reset_postdata();
            else : ?>
                <p style="text-align: center; width: 100%; padding: 40px;"><?php _e( 'No projects found. Add projects from the WordPress admin.', 'elina-theme' ); ?></p>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php get_footer(); ?>
