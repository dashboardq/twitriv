        <div class="view"><?php echo nl2br(_esc($note)); ?></div>
        <form action="/ajax/bookmark/add" class="edit" method="POST">
            <fieldset>
                <?php $res->html->hidden('tweet_id', $item['tweet_id']); ?>
                <?php $res->html->hidden('tweet_name', $item['tweet_name']); ?>
                <?php $res->html->hidden('tweet_username', $item['tweet_username']); ?>
                <?php $res->html->hidden('tweet_content', $item['tweet_content']); ?>
                <textarea name="note" placeholder="Enter your bookmark notes..."><?php esc($note); ?></textarea>
                <input type="submit" value="Save" />
                <button class="_cancel">Cancel</button>
            </fieldset>
        </form>
