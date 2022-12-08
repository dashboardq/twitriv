<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="app timeline <?php $res->pathClass(); ?>">
        <div class="base">
            <?php $res->partial('header-app'); ?>
            <?php $res->partial('sidebar'); ?>
            <main>
                <div class="box">
                    <div class="notice error">
                        <?php if($credits_failed): ?>
                        <p>The requested action requires available view credits and your account appears to not have any credits available at this time. View credits are reset at the beginning of each month. If you have any questions about this, please contact support.</p>
                        <?php else: ?>
                        <p>The requested action is only available to premium accounts. Please upgrade your account to perform this action.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
            <?php $res->partial('cycles'); ?>
            <?php $res->partial('footer'); ?>
            <?php if($show_connect): ?>
            <div class="overlay show">
                <div>
                    <?php $res->html->messages(); ?>
                    <p>Please connect your Twitter account by pressing the button below:</p>
                    <p><a href="/twitter/start" class="button">Connect To Twitter</a></p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </body>
</html>

