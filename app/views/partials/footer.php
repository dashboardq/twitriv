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

        <div class="overlay processing" hidden>
            <div class="loading"><span></span></div>
        </div>

        <div class="overlay message" hidden>
            <div class="box">
                <h2>Error</h2>
                <div class="content"></div>
                <button aria-label="Close">&times;</button>
            </div>
        </div>

        <?php if($user): ?>
        <form id="logout" action="/logout" method="POST" class="hidden"></form>
        <?php endif; ?>

        <script src="/assets/js/ao.js?cache-date=<?php esc($cache_date); ?>"></script>
        <script src="/assets/js/_ao.js?cache-date=<?php esc($cache_date); ?>"></script>
        <script src="/assets/js/main.js?cache-date=<?php esc($cache_date); ?>"></script>

        <?php if(ao()->env('APP_ENV') == 'prod'): ?>
        <?php echo ao()->env('APP_ANALYTICS'); ?>
        <?php endif; ?>
