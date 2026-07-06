    <div class="ps-page--blog">
        <div class="container">
            <div class="ps-page__header">
                <h1><?php echo $blog_article_title; ?></h1>
                <div class="ps-breadcrumb--2">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url(); ?>">Home</a></li>
                        <li><a href="<?php echo base_url(); ?>blog">Blog</a></li>
                        <li><?php echo $blog_article_title; ?></li>
                    </ul>
                </div>
            </div>
            <div class="ps-blog--sidebar">
                <div class="ps-blog__left">
                    <?php foreach ($blog_article as $row): ?>
                        <?php if($row->cover_image != '' && file_exists("./uploads/blog_article_cover_images/" . $row->cover_image)): ?>
                            <div class="ps-post__thumbnail">
                                <img class="lazyload" data-src="<?php echo base_url(); ?>uploads/blog_article_cover_images/<?php echo $row->cover_image; ?>" src="<?php echo blog_placeholder; ?>">
                            </div>
                        <?php endif; ?>
                        <div class="ps-post--detail sidebar">
                            <div class="ps-post__header mt-3">
                                <p><?php echo date('d/m/Y', strtotime($row->blog_article_date)); ?> / By <?php echo $row->blog_article_author; ?></p>
                            </div>
                            <div class="ps-post__content dynamic-data">
                                <?php echo $row->blog_article_content; ?>
                            </div>
                        </div>
                        <div class="sharethis-inline-share-buttons"></div>
                    <?php endforeach; ?>
                    <hr>
                    <div id="disqus_thread"></div>
                    <script>

                    /**
                    *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                    *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
                    /*
                    var disqus_config = function () {
                    this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
                    this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                    };
                    */
                    (function() { // DON'T EDIT BELOW THIS LINE
                    var d = document, s = d.createElement('script');
                    s.src = 'https://bethany-gift-shop.disqus.com/embed.js';
                    s.setAttribute('data-timestamp', +new Date());
                    (d.head || d.body).appendChild(s);
                    })();
                    </script>
                    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
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