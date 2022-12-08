<!DOCTYPE html>                
<html>
    <head>                     
        <meta charset="utf-8">     
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <title>TwitRiv.com - Take Control of your Twitter Experience</title>

        <link href="/assets/css/ao.css?cache-date=2022-07-15" rel="stylesheet">
        <link href="/assets/css/main.css?cache-date=2022-07-15" rel="stylesheet">
        <link href="/assets/css/home.css?cache-date=2022-07-15" rel="stylesheet">

        <link rel="icon" href="/favicon.svg" type="image/svg+xml">

        <meta property="og:title" content="TwitRiv.com - Take Control of your Twitter Experience" />
        <meta property="og:description" content="TwitRiv puts you in the driver seat and allows you pull up the content that you want to see when you want to see it. Take control of your Twitter experience and stop fighting with the algorithm." />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://twitriv.com" />
        <meta property="og:image" itemprop="image" content="https://twitriv.com/assets/images/share_1200x630.png" />
        <meta property="og:image:secure_url" itemprop="image" content="https://twitriv.com/assets/images/share_1200x630.png" />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />

        <link itemprop="thumbnailUrl" href="https://twitriv.com/assets/images/share_1200x630.png"> 

        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="https://twitriv.com/assets/images/share_1200x600.png">
    </head>
    <body class="public page_main">
        <div class="base">
            <?php $res->partial('header'); ?>
				
			<main>
				<?php $res->html->messages(); ?>
				<section class="welcome">
					<div class="box">
						<div class="primary">
							<h1>Do you get tired of missing the content you want to see on Twitter?</h1>
							<p>TwitRiv puts you in the driver seat and allows you pull up the content that you want to see when you want to see it. Take control of your Twitter experience and stop fighting with the algorithm.</p>
							<p>
							<a href="/login" class="button">Get Started</a>
							</p>
						</div>
						<div class="preview">
                            <img src="/assets/images/screenshot.png" alt="App Screenshot" />
						</div>
					</div>
				</section>
            <section class="details">
                <div class="box">
                    <h2>TwitRiv focuses on giving you the tools you need to filter what you see
                        <br>and quickly find, interact, and communicate with friends and followers.</h2>
                    <div class="features">
                        <div class="feature icon_controls">
                            <h3>Choose your timeline details.</h3>
                            <p>Display your chronological timeline but choose whether to include replies and retweets.</p>
                            <img src="/assets/images/controls.png" alt="Controls Screenshot" />
                        </div>
                        <div class="feature icon_bookmark">
                            <h3>Enhanced Bookmarks allows you to save tweets and notes.</h3>
                            <p>Share your bookmarks between TwitRiv and Twitter but also be able to search and add additional notes.</p>
                            <img src="/assets/images/bookmark.png" alt="Bookmark Screenshot" />
                        </div>
                        <div class="feature icon_todo">
                            <h3>Keep track of tasks you need to complete with the Todo system.</h3>
                            <p>When you are scrolling through timelines, you may come across a tweet that you want to interact with later. You can add it to your todo list.</p>
                            <img src="/assets/images/todo.png" alt="Todo Screenshot" />
                        </div>

                        <div class="feature icon_cycle">
                            <h3>Quickly cycle through user timelines, searches, and lists.</h3>
                            <p>A simple yet very powerful system is the "Cycle" list. It allows you to quickly see what content you have viewed most recently and what content you have not visited in a while. You can keep track of high priority timelines using the cycle list.</p>
                            <img src="/assets/images/cycles.png" alt="Cycles Screenshot" />
                        </div>

                        <div class="feature icon_mention">
                            <h3>Mention list allows you to quickly respond.</h3>
                            <p>Interacting with friends and followers doesn't have to be difficult. The mention list makes it easy to see your latest interactions. It shows the previous tweet so it is easy to see what your friend is responding to.</p>
                            <img src="/assets/images/mentions.png" alt="Mentions Screenshot" />
                        </div>

                        <div class="feature icon_more">
                            <h3>Not seeing a feature you need?</h3>
                            <p>Get in touch to see if what you are wanting can be added. Always open to hearing feedback and feature requests.</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="testimonials">
                <div class="box">
                    <p>My approach to Twitter is to promote others, share helpful content, and connect with others. TwitRiv was built as tool to help me accomplish these goals. I hope you find it useful!</p>
                    <div class="bio">
                        <img src="/assets/images/profile.jpg" alt="Profile Image of Anthony Graddy" />
                        <cite><strong>Anthony Graddy</strong>Founder of TwitRiv</cite>
                    </div>
                </div>
            </section>
            <section class="services">
                <div class="box">
                <h2>There are a limited number of premium TwitRiv accounts available.</h2>
                <p>The Twitter API has limitations on the number of calls that can be made by TwitRiv. In this initial launch phase, there are only 10 premium accounts available.</p>
                <p>The premium accounts are available on a first come first serve basis. Once the initial accounts have been claimed, the API usage will be analyzed and then if possible, more premium accounts will become available but this will also coincide with a price increase for the new accounts so make sure you claim your premium account early.</p>
                </div>
            </section>
            <section class="ready">
                <div class="box">
                    <h2>Ready to take control of your Twitter experience?</h2>
                    <p>Get started today and start seeing the content that you want to see.</p>
                    <p><a href="/login" class="button">Get Started</a></p>
                </div>
            </section>
			</main>
				
			<footer>
				<div class="box">
					<p>&copy; <?php esc(date('Y')); ?> <?php esc(ao()->env('APP_NAME')); ?><br>A DashboardQ Service</p>
					<nav>
						<ul>
							<li><a href="/terms">Terms of Service</a></li>
							<li><a href="/privacy">Privacy Policy</a></li>
						</ul>
					</nav>
				</div>
			</footer>

			<div class="overlay -processing" hidden>
				<div class="loading"><span></span></div>
			</div>

			<form id="logout" action="/logout" method="POST" class="hidden"></form>
		</div>
		<script src="/assets/js/ao.js?cache-date=2022-07-15"></script>
		<script src="/assets/js/main.js?cache-date=2022-07-15"></script>

        <?php if(ao()->env('APP_ENV') == 'prod'): ?>
        <?php echo ao()->env('APP_ANALYTICS'); ?>
        <?php endif; ?>
    </body>
</html>
