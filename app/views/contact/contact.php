<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="public">
        <div class="base">
            <?php $res->partial('header'); ?>
            <div class="sidebar">
            </div>
            <main>
                <section class="box">
                    <h1>Contact</h1>

                    <p>If you have any questions, problems, or concerns about anything, please do not hesitate to make contact.</p> 
                    <?php $res->html->messages(); ?>

                    <form method="POST">
                        <?php $res->html->text('Name'); ?>

                        <?php $res->html->text('Email'); ?>

                        <?php $res->html->textarea('Message'); ?>

                        <?php $res->html->submit('Send', 'button button_invert'); ?>
                    </form>

                </section>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
    </body>
</html>

