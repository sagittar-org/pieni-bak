<?php $table = $vars['model']->table; ?>
<?php $alias = $vars['model']->alias; ?>
<?php $row = $vars['model']->row; ?>
<?php $id = $row[$vars['model']->primary_key]; ?>
    <div class="container">
      <h1><?php h($row[$vars['model']->display]); ?></h1>
<?php if (in_array('edit', $vars['model']->action_list) OR in_array('delete', $vars['model']->action_list)): ?>
      <div class="text-right" style="margin-top:-46px">
<?php foreach (array_merge(array_combine($vars['model']->action_list, $vars['model']->action_list), $vars['model']->row_action_hash) as $key => $row_action): ?>
<?php if ($key === 'view') continue; ?>
<?php if ($row_action !== 'view') continue; ?>
            <a href="<?php href("{$table}/{$key}/{$id}"); ?>" class="btn btn-default"><?php l("crud_{$key}"); ?></a>
<?php endforeach; ?>
<?php foreach (array_merge(array_combine($vars['model']->action_list, $vars['model']->action_list), $vars['model']->row_action_hash) as $key => $row_action): ?>
<?php if ($row_action !== 'edit') continue; ?>
            <button type="button" class="btn btn-default" data-toggle="modal" id="<?php h($alias); ?><?php h(ucfirst($key)); ?>Show<?php h($id); ?>" data-target="#<?php h($alias); ?><?php h(ucfirst($key)); ?>" onclick="<?php h($alias); ?>Pre<?php h(ucfirst($key)); ?>('<?php h($id); ?>');"><?php l("crud_{$key}"); ?></button>
<?php endforeach; ?>
<?php foreach (array_merge(array_combine($vars['model']->action_list, $vars['model']->action_list), $vars['model']->row_action_hash) as $key => $row_action): ?>
<?php if ($row_action !== 'delete') continue; ?>
            <button type="button" class="btn btn-default" data-toggle="modal" id="<?php h($alias); ?><?php h(ucfirst($key)); ?>Show<?php h($id); ?>" data-target="#<?php h($alias); ?><?php h(ucfirst($key)); ?>" onclick="<?php h($alias); ?>Pre<?php h(ucfirst($key)); ?>('<?php h($id); ?>');"><?php l("crud_{$key}"); ?></button>
<?php endforeach; ?>
      </div>
<?php endif; ?>
      <table class="table">
<?php foreach ($vars['model']->select_hash as $key => $select): ?>
<?php if (in_array($key, $vars['model']->hidden_list)) continue; ?>
<?php if ($key === $vars['model']->display) continue; ?>
        <tr>
          <th style="white-space:nowrap; width:0; text-align:right;"><?php l($key); ?></th>
          <td><?php load_view('col', ['row' => $row, 'key' => $key], $vars['model']->table); ?></td>
        </tr> 
<?php endforeach; ?>
      </table>
    </div>
<?php load_view('edit', $vars, $table); ?>
<?php load_view('delete', $vars, $table); ?>
<?php foreach ($vars['model']->has_hash as $key => $has): ?>
<?php if ( ! in_array('index', model($key)->action_list)) continue; ?>
<?php load_view('index', $has, $has['model']->table); ?>
<?php endforeach; ?>
<?php /* ?>
    <div class="container">
      <button type="button" class="btn btn-default" onclick="history.back();" style="width:100%;"><?php l('crud_back'); ?></button>
    </div>
<?php */ ?>
