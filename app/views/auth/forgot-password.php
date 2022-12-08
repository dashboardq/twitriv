<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="<?php $res->pathClass(); ?>">
        <div class="base">
            <header class="app">
                <div class="box">
                    <h2>
                        <span class="logo">
                            <span class="one">~</span>
                            <span class="two">~</span>
                        </span>
                        <a href="/"><?php esc(ao()->env('APP_NAME')); ?></a>
                    </h2>
                    <a href="/login" class="button -small">Login</a>
                </div>
            </header>
            <div class="sidebar">
            </div>
            <main>
                <section class="box">
                    <?php $res->html->messages(); ?>

                    <section class="forgot_password">
                        <h2><?php esc($title); ?></h2>
                        <form method="POST">
                            <p>Please enter your email below to reset your password.</p>
                            <?php $res->html->text('Email'); ?>

                            <?php $res->html->submit('Submit'); ?>
                            
                            <div>
                                <a href="/login">&lt; Back to login</a>
                            </div>
                        </form>
                    </section>

                </section>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
    </body>
</html>

