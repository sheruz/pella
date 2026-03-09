<?php
/**
 * The template for displaying archive pages for Projects.
 */

get_header(); ?>

<div id="primary" class="content-area pella-projects-archive">
    <main id="main" class="site-main">

    <?php if ( have_posts() ) : ?>

        <header class="page-header">
            <h1 class="page-title"><?php post_type_archive_title(); ?></h1>
        </header>

        <div class="pella-projects-grid">
            <?php
            while ( have_posts() ) :
                the_post();
                
                $id = get_the_ID();
                $package_name = get_post_meta($id, '_pella_package_name', true);
                $package_price = get_post_meta($id, '_pella_package_price', true);
                $basic_info = get_post_meta($id, '_pella_basic_info', true);
                $img = get_the_post_thumbnail_url($id, 'large');
                ?>
                <div class="pella-project-card">
                    <?php if ($img) : ?>
                        <img src="<?php echo esc_url($img); ?>" alt="<?php the_title_attribute(); ?>" class="pella-project-image" />
                    <?php endif; ?>
                    <div class="pella-project-content">
                        <h3><?php the_title(); ?></h3>
                        <?php if ($package_name) : ?>
                            <strong>Package: </strong><?php echo esc_html($package_name); ?><br/>
                        <?php endif; ?>
                        <?php if ($package_price) : ?>
                            <strong>Price: </strong><?php echo esc_html($package_price); ?><br/>
                        <?php endif; ?>
                        <?php if ($basic_info) : ?>
                            <p><?php echo esc_html($basic_info); ?></p>
                        <?php endif; ?>
                        <a href="<?php the_permalink(); ?>" class="pella-project-btn">Read More</a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <?php
        the_posts_navigation();

    else :

        get_template_part( 'template-parts/content', 'none' );

    endif;
    ?>

    </main>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
