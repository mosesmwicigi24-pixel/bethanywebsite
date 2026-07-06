<!-- FAQ Schema for Rich Snippets -->
<script type="application/ld+json">
    {
          "@context": "https://schema.org",
            "@type": "FAQPage",
              "mainEntity": [
                      <?php foreach ($faqs as $faq): ?>
                          {
                                    "@type": "Question",
                                          "name": "<?php echo htmlspecialchars($faq->faq_heading); ?>",
                                                "acceptedAnswer": {
                                                            "@type": "Answer",
                                                                    "text": "<?php echo htmlspecialchars(strip_tags($faq->faq_description)); ?>"
                                                                          }
                                                                              }<?php if ($faq !== end($faqs)) echo ','; ?>
                                                                                  <?php endforeach; ?>
                                                                                    ]
                                                                                    }
                                                                                    </script>
                                                                                    <div class="ps-page--single">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li>Frequently Asked Questions</li>
                </ul>
            </div>
        </div>
        <div class="ps-faqs">
            <div class="container">
                <div class="ps-section__header">
                    <h1>Frequently Asked Questions</h1>
                </div>
                <div class="ps-section__content">
                    <div class="table-responsive">
                        <table class="table ps-table--faqs">
                            <tbody>
                                <?php foreach ($faqs as $row): ?>
                                    <tr>
                                        <td class="question"><?php echo $row->faq_heading; ?></td>
                                        <td class="dynamic-data"><?php echo $row->faq_description; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>