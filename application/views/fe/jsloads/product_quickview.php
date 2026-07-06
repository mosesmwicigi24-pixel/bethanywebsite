<?php
/**
 * Auto-versioning for static assets.
 *
 * @param string $file Path to the file.
 * @return string File path with version query parameter, or original path if file doesn't exist.
 */
function auto_version($file) {
    if (!file_exists($file)) {
        return $file;
    }
    $mtime = filemtime($file);
    return preg_replace('{\\.([^./]+)$}', ".\$1?v=\$mtime", $file); // Using 'v' for version, more standard.
}
?>

<?php foreach ($product as $row) : ?>
    <form id="frm_product_quick_view" name="frm_product_quick_view" method="post" action="<?php echo base_url('cart/add'); ?>" class="product-quick-view-form">
        <article class="ps-product--detail ps-product--fullwidth ps-product--quickview">
            <div class="ps-product__header">
                <div class="ps-product__thumbnail" data-vertical="false">
                    <div class="ps-product__images" data-arrow="true">
                        <?php if (!empty($row->product_image_thumb) && file_exists("./uploads/product_images/thumbs/" . $row->product_image_thumb)) : ?>
                            <div class="item">
                                <img src="<?php echo base_url("uploads/product_images/thumbs/" . html_escape($row->product_image_thumb)); ?>" alt="<?php echo html_escape($row->product_name); ?> Thumbnail">
                            </div>
                        <?php endif; ?>

                        <?php foreach ($product_images as $img) : ?>
                            <?php if (!empty($img->image_filename_thumb) && file_exists("./uploads/product_images/thumbs/" . $img->image_filename_thumb)) : ?>
                                <div class="item">
                                    <img src="<?php echo base_url("uploads/product_images/thumbs/" . html_escape($img->image_filename_thumb)); ?>" alt="<?php echo html_escape($row->product_name); ?> Image">
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="ps-product__info">
                    <h1><?php echo html_escape($row->product_name); ?></h1>
                    <div class="ps-product__meta">
                        <?php if (!empty($row->brand_name)) : ?>
                            <p>Brand: <a href="<?php echo base_url("brand/" . $row->brand_reference_id); ?>"><?php echo html_escape($row->brand_name); ?></a></p>
                        <?php endif; ?>
                    </div>
                    <?php if ($row->sale_price > 0) : ?>
                        <h4 class="ps-product__price"><?php echo html_escape($default_currency); ?> <?php echo number_format($row->sale_price, 2); ?> <del><?php echo html_escape($default_currency); ?> <?php echo number_format($row->regular_price, 2); ?></del></h4>
                    <?php else : ?>
                        <h4 class="ps-product__price"><?php echo html_escape($default_currency); ?> <?php echo number_format($row->regular_price, 2); ?></h4>
                    <?php endif; ?>
                    <div class="ps-product__desc pr-20">
                        <?php if (!empty($row->product_short_description)) : ?>
                            <?php echo html_escape($row->product_short_description); ?>
                        <?php endif; ?>

                        <?php if ($row->product_type !== 'Simple') : ?>
                            <div class="ps-product__variations">
                                <figure>
                                    <figcaption>Variations: <strong>Select an option</strong></figcaption>
                                    <?php foreach ($product_variations as $variation) : ?>
                                        <div class="ps-variant ps-variant--other p-4 mb-3">
                                            <div class="row">
                                                <?php if (!empty($variation->product_variation_image) && file_exists("./uploads/product_images/" . $variation->product_variation_image)) : ?>
                                                    <div class="col-sm-12">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" name="product_variation_id" id="product_variation_id<?php echo $variation->product_variation_id; ?>" value="<?php echo $variation->product_variation_id; ?>" required>
                                                            <label class="custom-control-label font-14" for="product_variation_id<?php echo $variation->product_variation_id; ?>">
                                                                <strong><img class="nav-tabs-icon3" src="<?php echo base_url("uploads/product_images/" . html_escape($variation->product_variation_image)); ?>" alt="Variation Image"></strong>
                                                                <?php
                                                                $variation_attributes = '';
                                                                if (!empty($variation->attributes)) {
                                                                    foreach ($variation->attributes as $attr) {
                                                                        $variation_attributes .= '<span class="badge border">' . html_escape($attr->product_attribute_name) . ':</span> <span class="font-weight-bold">' . html_escape($attr->product_attribute_value) . '</span>, ';
                                                                    }
                                                                    $variation_attributes = rtrim($variation_attributes, ', ') . '<br>';
                                                                }
                                                                echo $variation_attributes;
                                                                ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php else : ?>
                                                    <div class="col-sm-12">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" name="product_variation_id" id="product_variation_id<?php echo $variation->product_variation_id; ?>" value="<?php echo $variation->product_variation_id; ?>" required>
                                                            <label class="custom-control-label font-14" for="product_variation_id<?php echo $variation->product_variation_id; ?>">
                                                                <?php
                                                                $variation_attributes = '';
                                                                if (!empty($variation->attributes)) {
                                                                    foreach ($variation->attributes as $attr) {
                                                                        $variation_attributes .= '<span class="badge border">' . html_escape($attr->product_attribute_name) . ':</span> <span class="font-weight-bold">' . html_escape($attr->product_attribute_value) . '</span>, ';
                                                                    }
                                                                    $variation_attributes = rtrim($variation_attributes, ', ') . '<br>';
                                                                }
                                                                echo $variation_attributes;
                                                                ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </figure>
                            </div>
                        <?php endif; ?>

                        <div class="ps-product__shopping mt-20">
                            <figure>
                                <div class="form-group--number">
                                    <button type="button" class="down" onclick="adjustQuantity(-1, 'product_qty')"><i class="fa fa-minus"></i></button>
                                    <input id="product_qty" name="product_qty" class="form-control qty-input" type="number" value="1" min="1">
                                    <button type="button" class="up" onclick="adjustQuantity(1, 'product_qty')"><i class="fa fa-plus"></i></button>
                                    <input type="hidden" id="product_id" name="product_id" value="<?php echo $row->product_id; ?>">
                                </div>
                            </figure>
                            <button type="submit" id="btn_quick_view_add_to_cart" class="ps-btn ps-btn--md ps-btn--rounded"><i class="icon-cart"></i> Add to cart</button>
                            <div class="ps-product__actions">
                                <a href="javascript:void(0);" class="btn-product-favorite" data-product-id="<?php echo $row->product_id; ?>" data-toggle="tooltip" data-placement="top" title="Add to Favorites"><i class="icon-heart"></i></a>
                                <a href="javascript:void(0);" class="btn-product-compare" data-product-id="<?php echo $row->product_id; ?>" data-toggle="tooltip" data-placement="top" title="Compare"><i class="icon-chart-bars"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </form>
<?php endforeach; ?>

<script>
    $('[data-toggle="tooltip"]').tooltip(); 

    $('.ps-product--quickview .ps-product__images').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        fade: true,
        dots: false,
        arrows: true,
        infinite: false,
        prevArrow: "<a href='#'><i class='fa fa-angle-left'></i></a>",
        nextArrow: "<a href='#'><i class='fa fa-angle-right'></i></a>",
    });

    function adjustQuantity(change, inputId) {
        const input = document.getElementById(inputId);
        let qty = parseInt(input.value);
        if (!isNaN(qty)) {
            qty += change;
            if (qty < 1) {
                qty = 1;
            }
            input.value = qty;
        }
    }

    $(document).ready(function() {
        $(".product-quick-view-form").validate({
            errorPlacement: function(error, element) {
                if (element.parent().hasClass("form-group--number")) {
                    error.insertAfter(element.parent().parent().parent());
                } else {
                    error.insertAfter(element.parent().parent());
                }
            },
            rules: {
                product_variation_id: { // Assuming this is the correct name for the radio button group
                    required: true
                },
                product_qty: {
                    required: true,
                    min: 1
                }
            },
            messages: {
                product_variation_id: {
                    required: "Please select a variation"
                },
                product_qty: {
                    required: "Please enter a quantity",
                    min: "Quantity must be at least 1"
                }
            },
            success: function(label) {
                label.parent().removeClass('error');
                label.remove();
            }
        });
    });
</script>
