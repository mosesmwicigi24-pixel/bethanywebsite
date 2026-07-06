    <div class="ps-page--blog">
        <div class="container">
            <div class="ps-page__header">
                <h1><?php echo $blog_category_name; ?></h1>
                <div class="ps-breadcrumb--2">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>">Home</a></li>
                        <li><a href="<?php echo base_url(); ?>blog">Blog</a></li>
                        <li><?php echo $blog_category_name; ?></li>
                    </ul>
                </div>
            </div>
            <div class="ps-blog--sidebar">
                <div class="ps-blog__left">
                    <?php if ($num_blog_category_articles > 0): ?>
                        <?php foreach ($blog_category_articles as $row): ?>
                            <div class="ps-post ps-post--small-thumbnail">
                                <?php if($row->thumb_image != '' && file_exists("./uploads/blog_article_cover_images/thumbs/" . $row->thumb_image)): ?>
                                    <div class="ps-post__thumbnail">
                                        <a class="ps-post__overlay" href="<?php echo base_url();?>blog/<?php echo $row->blog_article_reference_id; ?>"></a>
                                        <img class="lazyload" data-src="<?php echo base_url(); ?>uploads/blog_article_cover_images/thumbs/<?php echo $row->thumb_image; ?>" src="<?php echo blog_placeholder; ?>">
                                    </div>
                                <?php endif; ?>
                                <div class="ps-post__content">
                                    <div class="ps-post__top">
                                        <!-- <div class="ps-post__meta">
                                            <a href="#">Entertaiment</a><a href="#">Technology</a>
                                        </div> -->
                                        <a class="ps-post__title" href="<?php echo base_url();?>blog/<?php echo $row->blog_article_reference_id; ?>"><?php echo $row->blog_article_title; ?></a>
                                        <p><?php echo character_limiter(strip_tags($row->blog_article_content),200); ?></p>
                                    </div>
                                    <div class="ps-post__bottom">
                                        <p><?php echo date('d/m/Y', strtotime($row->blog_article_date)); ?> by<a href="#"> <?php echo $row->blog_article_author; ?></a></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info text-center"><i class="icon-notification-circle"></i> No blog articles have been added yet</div>
                    <?php endif; ?>
                </div>
                <div class="ps-blog__right">
                    <aside class="widget widget--blog widget--categories">
                        <h3 class="widget__title">Categories</h3>
                        <div class="widget__content">
                            <ul>
                                <?php if ($num_blog_categories > 0): ?>
                                    <?php foreach ($blog_categories as $row): ?>
                                        <li><a class="text-dark" href="<?php echo base_url();?>blog/category/<?php echo $row->blog_category_reference_id; ?>"><?php echo $row->blog_category_name; ?></a></li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p><i>There are no posted categories yet</i></p>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </aside>
                    <?php if ($num_blog_recent_articles > 0): ?>
                        <aside class="widget widget--blog widget--recent-post">
                            <h3 class="widget__title">Recent Posts</h3>
                            <div class="widget__content">
                                <?php foreach ($blog_recent_articles as $row): ?>
                                    <a href="<?php echo base_url();?>blog/<?php echo $row->blog_article_reference_id; ?>"><?php echo $row->blog_article_title; ?></a>
                                <?php endforeach; ?>
                            </div>
                        </aside>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>