        <form action="/ajax/interact/add" class="edit" method="POST">
            <fieldset>
                <?php $res->html->hidden('tweet_id', $item['tweet_id']); ?>
                <textarea name="note" placeholder="Enter your reply..."></textarea>
                <input type="submit" value="Reply" />
                <button class="_cancel">Cancel</button>
            </fieldset>
        </form>
