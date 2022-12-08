<!DOCTYPE html>                
<html>
    <head>                     
        <meta charset="utf-8">     
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <?php $res->partial('head'); ?>
    </head>
    <body class="public page_login">
        <div class="base">
            <?php $res->partial('header'); ?>
            
            <main>
                <section class="full">
                    <?php $res->html->messages(); ?>
                </section>

                <section class="login">
                    <h2>Login</h2>
                    <form method="POST">
                        <?php $res->html->text('Email', 'login_email'); ?>

                        <?php $res->html->password('Password', 'login_password'); ?>

                        <?php $res->html->submit('Login'); ?>
                    </form>
                </section>

                <?php if(ao()->env('APP_REGISTER_ALLOW') && ao()->env('APP_LOGIN_TYPE') == 'db'): ?>
                <section class="register">
                    <h2>Register</h2>
                    <form action="<?php uri('register'); ?>" method="POST">
                        <?php $res->html->text('Full Name', 'name'); ?>

                        <?php $res->html->text('Email'); ?>

                        <?php $res->html->password('Password'); ?>

                        <div>
                            <p>By submitting this form you are agreeing to the <a href="<?php uri('terms'); ?>">Terms of Service</a> and the <a href="<?php uri('privacy'); ?>">Privacy Policy</a>.</p>
                        </div>

                        <?php $res->html->submit('Register'); ?>
                    </form>
                </section>
                <?php endif; ?>

            </main>
            
                    <footer>
            <div class="box">
                <p>&copy; <?php esc(date('Y')); ?> <?php esc(ao()->env('APP_NAME')); ?></p>
                <nav>
                    <ul>
                        <li><a href="/terms">Terms of Service</a></li>
                        <li><a href="/privacy">Privacy Policy</a></li>
                    </ul>
                </nav>
            </div>
        </footer>

        <div class="overlay -processing" hidden>
            <div class="loading"><span></span></div>
        </div>

                <form id="logout" action="/logout" method="POST" class="hidden"></form>
        
        <script src="/assets/js/ao.js?cache-date=2022-07-15"></script>
        <script src="/assets/js/_ao.js?cache-date=2022-07-15"></script>
        <script src="/assets/js/main.js?cache-date=2022-07-15"></script>

                            </div>
    </body>
</html>
