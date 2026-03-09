<?php
/**
 * Template for displaying Project post type archive
 */

get_header(); ?>

<main class="site-main projects-archive-page">
    <div class="container">
        
        <header class="page-header">
            <h1 class="section-title text-center">P A C K A G E S</h1>
        </header>

        <div class="certifications-grid">
            <?php
            if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    
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
                
                // Pagination
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => __( '← Previous', 'elina-theme' ),
                    'next_text' => __( 'Next →', 'elina-theme' ),
                ) );
                
            else : ?>
                <p style="text-align: center; width: 100%; padding: 40px;"><?php _e( 'No projects found. Add projects from the WordPress admin.', 'elina-theme' ); ?></p>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php get_footer(); ?>
