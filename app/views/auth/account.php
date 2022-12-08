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
                    <h2>Account</h2>

                    <?php if(ao()->env('APP_LOGIN_TYPE') == 'db'): ?>
                        <?php $res->html->messages(); ?>
                        <form method="POST">
                            <?php $res->html->text('Full Name', 'name'); ?>

                            <?php $res->html->text('Email'); ?>

                            <div> 
                                <a href="/change-password">Change Password</a>
                            </div>

                            <?php $res->html->submit('Update'); ?>
                        </form>
                    <?php else: ?>
                        <form method="POST">
                            <?php $res->html->text('Email', '', '', '', 'disabled'); ?>
                        </form>
                    <?php endif; ?>

                </section>
            </main>
            <?php $res->partial('cycles'); ?>
            <?php $res->partial('footer'); ?>
        </div>
    </body>
</html>

