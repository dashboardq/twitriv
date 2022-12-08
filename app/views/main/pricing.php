<!DOCTYPE html>                
<html>
    <head>                     
        <?php $res->partial('head'); ?>
    </head>
    <body class="public <?php $res->pathClass(); ?>">
        <div class="base">
            <?php $res->partial('header'); ?>
            <main>
                <section class="box">
                    <h1>Pricing</h1>
                    <div class="notice error">
                        <p>At launch, there are only 10 Premium accounts available on a first come first serve basis. Once initial accounts have been claimed, API usage will be analyzed and if possible more accounts will become available but the price will increase.</p>
                    </div>
                    <ul class="cards">
                        <li>
                            <h2>Free</h2>
                            <h3>$0</h3>
                            <ul>
                                <li>Timeline control options (choose whether or not to see replies and retweets in the main timeline)</li>
                                <li>Mention list</li>
                                <li>Bookmarks list</li>
                            </ul>
                            <p><a href="/login" class="button">Get Started</a></p>
                        </li>
                        <li>
                            <h2>Premium</h2>
                            <h3>$3/mo</h3>
                            <ul>
                                <li>Timeline control options (choose whether or not to see replies and retweets in the main timeline)</li>
                                <li>Mention list</li>
                                <li>Bookmarks list, notes, and search</li>
                                <li>Todo list, notes, and search</li>
                                <li>Cycle system</li>
                                <li>10,000 View Credits - be able to view tweets and timelines (due to API limitations, replies older than 7 days are not available)</li>
                            </ul>
                            <p><a href="/login" class="button">Get Started</a></p>
                        </li>
                    </ul>
                </section>
            </main>
            <?php $res->partial('footer'); ?>
        </div>
    </body>
</html>
