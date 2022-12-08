<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="<?php $res->pathClass(); ?>">
        <?php $res->partial('header'); ?>
        <main>
            <?php $res->html->messages(); ?>
            <section class="welcome">
                <!-- Place everything in a box because it allows easier control of backgrounds and page widths. -->
                <div class="box">
                    <div class="primary">
                        <h1>This is the primary and most important text on the page.</h1>
                        <p>This is some additional follow up text to further explain the primary text.</p>
                        <p>
                        <a href="/login" class="button">Get Started</a>
                        </p>
                    </div>
                    <div class="preview">
                        <!-- Place an image or video here. -->
                    </div>
                </div>
            </section>
            <section class="details">
                <div class="box">
                    <h2>These are some secondary details to provide more information.
                        <br>You may choose to separate it out to two lines.</h2>
                    <div class="questions">
                        <div class="question icon_need">
                            <h3>Are you needing something?</h3>
                            <p>This is how the service solves that need.</p>
                        </div>
                        <div class="question icon_else">
                            <h3>What about something else?</h3>
                            <p>That is solved too.</p>
                        </div>
                        <div class="question icon_more">
                            <h3>Have more questions?</h3>
                            <p>Can go on and on...</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="testimonials">
                <div class="box">
                    <p>This is a great testimonial!</p>
                    <div class="bio">
                        <!--
                        <img src="/assets/images/profile.jpg" alt="Profile Image of Example User" />
                        -->
                        <cite><strong>Example User</strong><br>Founder of Example.com</cite>
                    </div>
                </div>
            </section>
            <section class="services">
                <div class="box">
                <h2>Here is a list of services that may be helpful in deciding to use the product.</h2>
                <ul>
                    <li class="icon_one">One is really important.</li>
                    <li class="icon_two">Two is important too!</li>
                    <li class="icon_three">Three cannot be forgotten.</li>
                </ul>
                <h2>This is a small list of what is possible. Don't see your service, have a custom service, or have something in mind? Just <a href="/contact">get in touch</a>.</h2>
                </div>
            </section>
            <section class="ready">
                <h2>Ready to get started?</h2>
                <p>Get started with the service today.</p>
                <p><a href="/login" class="button">Get Started</a>
            </section>
        </main>
		<?php $res->partial('footer'); ?>
    </body>
</html>
