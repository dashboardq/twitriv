
        <div class="cycles">
            <div class="box">
                <h2>Cycles</h2>
                <nav>
                    <ul>
                        <?php foreach($cycles as $cycle): ?>
                        <li class="icon <?php esc('icon_' . $cycle['type']); ?>"><a href="<?php esc($cycle['link']); ?>"><?php esc($cycle['name']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </div>
        </div>
