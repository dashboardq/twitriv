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
                    <form class="search">
                        <input type="text" name="q" value="<?php esc($query['q']); ?>" placeholder="Search" />
                        <input type="submit" value="Search" />
                    </form>

                    <?php foreach($list as $i => $item): ?>
                    <?php $previous_extra = $next_extra = '&q=' . _esc(urlencode($query['q'])); ?>
                    <?php $res->partial('tweet', compact('item', 'previous_extra', 'next_extra')); ?>
                    <?php endforeach; ?>
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

