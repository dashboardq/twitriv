<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="public <?php $res->pathClass(); ?>">
        <div class="base">
            <?php $res->partial('header'); ?>
            <div class="sidebar">
            </div>
            <main>
                <section class="box">
                    <div class="notice error">
                        <p>The requested page does not exist.</p>
                    </div>
                </section>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
		<?php $res->partial('foot'); ?>
    </body>
</html>
