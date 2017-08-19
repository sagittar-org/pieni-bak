<?php $table = $vars['model']->table; ?>
<?php $alias = $vars['model']->alias; ?>
    <div class="container">
<?php if ($alias === $table): ?>
      <h1><?php l($table); ?></h1>
<?php else: ?>
      <h2><?php l($alias); ?></h2>
<?php endif; ?>
<?php if (in_array('add', array_keys($vars['model']->action_hash))): ?>
      <div class="text-right" style="margin-top:-46px">
        <button type="button" class="btn btn-default" data-toggle="modal" id="<?php h($alias); ?>AddShow" data-target="#<?php h($alias); ?>Add" onclick="<?php h($alias); ?>PreAdd($('#<?php h($alias); ?>Add'));"><?php l('crud_add'); ?></button>
      </div>
<?php endif; ?>
<?php load_view('search', $vars, $table); ?>
<?php load_view('pagination1', $vars, $table); ?>
<?php if ($vars['model']->use_card === TRUE): ?>
<?php while (($row = $vars['model']->row()) !== NULL): ?>
    <div>
<?php load_view('card', array_merge($vars, ['row' => $row]), $table); ?>
    </div>
<?php endwhile; ?>
<?php else: ?>
<?php load_view('result', $vars, $table); ?>
<?php endif; ?>
<?php load_view('pagination2', $vars, $table); ?>
    </div>
<?php load_view('add', $vars, $table); ?>
<?php load_view('edit', $vars, $table); ?>
<?php load_view('delete', $vars, $table); ?>
