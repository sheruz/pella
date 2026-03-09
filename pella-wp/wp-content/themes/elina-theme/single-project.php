<?php
/**
 * Template for displaying single Project posts
 */

get_header(); ?>

<main class="site-main single-project-page">
    <div class="container">
        
        <?php while ( have_posts() ) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('project-single'); ?>>
                
                <header class="project-single-header">
                    <h1 class="project-single-title"><?php the_title(); ?></h1>
                    <?php 
                    $package_info = get_post_meta( get_the_ID(), '_project_package_info', true );
                    if ( $package_info ) : ?>
                        <div class="project-single-package">
                            <strong>Package / Basic Info:</strong> <?php echo esc_html( $package_info ); ?>
                        </div>
                    <?php endif; ?>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="project-single-image">
                        <?php the_post_thumbnail( 'large', array( 'class' => 'project-featured-image' ) ); ?>
                    </div>
                <?php endif; ?>

                <div class="project-single-content">
                    <?php 
                    $short_desc = get_post_meta( get_the_ID(), '_project_short_description', true );
                    if ( $short_desc ) : ?>
                        <div class="project-single-description">
                            <?php echo wp_kses_post( $short_desc ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( get_the_content() ) : ?>
                        <div class="project-single-body">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <footer class="project-single-footer">
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'project' ) ); ?>" class="back-to-projects">
                        ← Back to Projects
                    </a>
                </footer>

            </article>

        <?php endwhile; ?>

    </div>
</main>

<?php get_footer(); ?>
