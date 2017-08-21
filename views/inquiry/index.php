    <div class="container">
      <form method="post" action="<?php href(uri('uri_string'), FALSE, TRUE); ?>" style="max-width:400px; margin:0 auto;">
        <h2><?php l('inquiry'); ?></h2>
<?php if (isset($_SESSION[uri('actor')]['auth']['id'])): ?>
        <div class="form-group">
          <label><?php l('inquiry_email'); ?></label>
          <input type="email" name="email" class="form-control" readonly value="<?php h($_SESSION[uri('actor')]['auth']['email']); ?>">
          <input type="hidden" name="email" value="<?php h($_SESSION[uri('actor')]['auth']['email']); ?>">
        </div>
        <div class="form-group">
          <label><?php l('inquiry_name'); ?></label>
          <input type="text" class="form-control" readonly value="<?php h($_SESSION[uri('actor')]['auth']['name']); ?>">
          <input type="hidden" name="name" value="<?php h($_SESSION[uri('actor')]['auth']['name']); ?> (<?php h(uri('actor')); ?><?php h($_SESSION[uri('actor')]['auth']['id']); ?>)">
        </div>
<?php else: ?>
        <div class="form-group">
          <label><?php l('inquiry_email'); ?></label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
          <label><?php l('inquiry_name'); ?></label>
          <input type="text" name="name" class="form-control" required>
        </div>
<?php endif; ?>
        <div class="form-group">
          <label><?php l('inquiry_message'); ?></label>
          <textarea name="message" class="form-control" rows="10" required></textarea>
        </div>
        <button type="submit" class="btn btn-lg btn-primary btn-block"><?php l('inquiry_submit'); ?></button>
      </form>
    </div>
