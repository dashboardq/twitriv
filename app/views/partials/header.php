        <header>
            <div class="box">
                <h2>
                    <img src="/assets/images/logo.svg" alt="Logo" />
                    <a href="/"><?php esc(ao()->env('APP_NAME')); ?></a>
                    <?php /* <span>(A DashboardQ Service)</span> */ ?>
                </h2>
                <nav>
                    <button class="open">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-menu-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                           <line x1="4" y1="6" x2="20" y2="6"></line>
                           <line x1="4" y1="12" x2="20" y2="12"></line>
                           <line x1="4" y1="18" x2="20" y2="18"></line>
                        </svg>
                    </button>
                    <button class="close">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                           <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                           <line x1="18" y1="6" x2="6" y2="18"></line>
                           <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/pricing">Pricing</a></li>
                        <li><a href="/contact">Contact</a></li>
                        <?php if($user): ?>
                        <li><a href="/home">Account</a></li>
                        <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout').submit();">Logout</a></li>
                        <?php else: ?>
                        <li><a href="/login">Login</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </header>
