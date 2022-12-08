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
                    <h2>Lists</h2>
                    <?php if(count($list) == 0): ?>
                    <p>Your account does not appear to have any lists. Please create a list on Twitter.</p>
                    <?php endif; ?>

                    <ul>
                    <?php foreach($list as $i => $item): ?>
                    <li><a href="/list/<?php esc($item->id); ?>"><?php esc($item->name); ?></a></li>
                    <?php endforeach; ?>
                    </ul>
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

