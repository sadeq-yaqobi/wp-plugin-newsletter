<?php
add_shortcode('news_letter', 'nl_news_letter_layout');
function nl_news_letter_layout()
{
    if (isset($_GET['unsubscribe']) && $_GET['unsubscribe'] == 1) {
        ?>
        <div class="unsubs-news-overlay">
            <div class="wrapper">
                <span class="unsubs-close">&times</span>
                <p>لغو عضویت خبرنامه</p>
                <div class="form-wrapper">
                    <form action="" class="unsubs-form">
                        <input type="email" id="unsubs_email" class="unsubs-email" placeholder="ایمیل خود را وارد کنید..."
                               required="required">
                        <button type="submit" class="btn btn-theme unsubs-btn">لغو عضویت</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    <section class="bg-cover newsletter inverse-theme"
             style="background:url(<?php echo NL_PLUGIN_ASSETS_URL . '/img/banner-2.webp' ?>);" data-overlay="9">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-8 col-sm-12">
                    <div class="text-center">
                        <h2>به جامعه هزاران دانشجو بپیوندید!</h2>

                        <p>به جامعه میلیونی دانشجویان ما بپیوندید و به هزاران ساعت آموزش در حوزه‌های گوناگون دسترسی
                            داشته باشید.</p>
                        <form class="sup-form" id="nl_form">
                            <input type="email" id="nl_email" class="form-control sigmup-me"
                                   placeholder="ایمیل خود را وارد کنید..." required="required">
                            <button type="submit" class="btn btn-theme nl-btn">عضویت</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
}
