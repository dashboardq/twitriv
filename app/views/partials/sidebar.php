
        <div class="sidebar">
            <div class="box">
                <?php if(!$show_connect): ?>
                <div class="profile">
                    <a href="/<?php esc($user->profile()->data['twitter_username']); ?>"><img src="<?php esc($user->profile()->data['twitter_profile_image_url']); ?>" alt="Profile Image" /></a>
                    <ul class="meta">
                        <li class="author_name"><a href="/<?php esc($user->profile()->data['twitter_username']); ?>"><?php esc($user->profile()->data['twitter_name']); ?></a></li>
                        <li class="author_username"><a href="/<?php esc($user->profile()->data['twitter_username']); ?>">@<?php esc($user->profile()->data['twitter_username']); ?></a></li>
                    </ul>
                </div>
                <?php endif; ?>
                <nav>
                    <ul>
                        <li><a href="/home">Home</a></li>
                        <li><a href="/todo">Todo</a></li>
                        <li><a href="/mentions">Mentions</a></li>
                        <li><a href="/bookmarks">Bookmarks</a></li>
                        <li><a href="/lists">Lists</a></li>
                        <li><a href="/search">Search</a></li>
                        <li><a href="/account">Account</a></li>
                        <li><a href="/settings">Settings</a></li>
<?php /*
                        <li><a href="/support">Support</a></li>
 */ ?>
                        <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout').submit();">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </div>
