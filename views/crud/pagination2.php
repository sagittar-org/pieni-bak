<?php $alias = $vars['model']->alias; ?>
<?php $limit = $vars['model']->session['limit']; ?>
<?php $offset = $vars['model']->session['offset']; ?>
<?php if ($vars['model']->num_rows > $limit): ?>
      <form method="post" action="<?php href(uri('uri_string'), FALSE, FALSE); ?>">
        <div class="text-center">
          <span style="display:inline-block; width:32px; text-align:left;">
<?php if ($offset - $limit < 0): ?>
            <span class="text-muted"><?php l('crud_prev'); ?></span>
<?php else: ?>
            <a href="?<?php h($alias); ?>_offset=<?php h($offset - $limit); ?>"><?php l('crud_prev'); ?></a>
<?php endif; ?>
          </span>
<?php for ($i = max(0, min($vars['model']->num_rows - ($vars['model']->num_rows % $limit) - $limit * 10, $offset - $limit * 4)); $i < min($vars['model']->num_rows, max($limit * 10, $offset + $limit * 6)); $i += $limit): ?>
          <span style="display:inline-block; width:16px; text-align:center;">
<?php if (intval($i) === intval($offset)): ?>
            <strong><?php h($i / $limit + 1); ?></strong>
<?php else: ?>
            <a href="?<?php h($alias); ?>_offset=<?php h($i); ?>"><?php h($i / $limit + 1); ?></a>
<?php endif; ?>
          </span>
<?php endfor; ?>
          <span style="display:inline-block; width:32px; text-align:right;">
<?php if ($offset + $limit >= $vars['model']->num_rows): ?>
            <span class="text-muted"><?php l('crud_next'); ?></span>
<?php else: ?>
            <a href="?<?php h($alias); ?>_offset=<?php h($offset + $limit); ?>"><?php l('crud_next'); ?></a>
<?php endif; ?>
          </span>
        </div>
      </form>
<?php endif; ?>
