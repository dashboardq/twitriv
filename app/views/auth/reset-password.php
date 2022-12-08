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

                    <section class="reset_password">
                        <h2><?php esc($title); ?></h2>
                        <form method="POST">
                            <?php $res->html->hidden('user_id', $user_id); ?>
                            <?php $res->html->hidden('token', $token); ?>

                            <?php $res->html->password('New Password'); ?>

                            <?php $res->html->submit('Submit'); ?>
                        </form>
                    </section>

                </section>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
    </body>
</html>

