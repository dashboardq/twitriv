<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="app <?php $res->pathClass(); ?>">
        <div class="base">
            <?php $res->partial('header-app'); ?>
            <?php $res->partial('sidebar'); ?>
            <main>
                <section class="box">
                    <h2>Settings</h2>

                    <?php $res->html->messages(); ?>
                    <form method="POST">
                        <div>
                            <?php $res->html->checkbox('Home Page - Show Replies', 'home_replies'); ?>
                            <?php $res->html->checkbox('Home Page - Show Retweets', 'home_retweets'); ?>
                        </div>

                        <?php $res->html->text('Twitter Links Base Domain', 'twitter_base'); ?>

                        <div>
                            <?php $res->html->checkbox('Open Twitter New Tab', 'twitter_new_tab'); ?>
                        </div>

                        <?php $res->html->submit('Update'); ?>
                    </form>
                </section>
            </main>
            <?php $res->partial('cycles'); ?>
            <?php $res->partial('footer'); ?>
        </div>
    </body>
</html>

