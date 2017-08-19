<?php $table = $vars['model']->table; ?>
<?php $alias = $vars['model']->alias; ?>
<?php foreach ($vars['model']->action_hash as $key => $row_action): ?>
<?php if ($row_action !== 'delete') continue; ?>
<?php load_model($table, ['actor' => uri('actor'), 'class' => $table, 'alias' => $alias, 'method' => $key, 'auth' => $_SESSION[uri('actor')]['auth']], "{$alias}_{$key}"); ?>
    <form class="modal fade" id="<?php h($alias); ?><?php h(ucfirst($row_action)); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" onsubmit="return false;">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"><?php l("crud_{$key}"); ?> - <?php l($alias); ?></h4>
          </div>
          <div class="modal-body">
<?php foreach (array_keys(model("{$alias}_{$key}")->select_hash) as $select_hash_key): ?>
<?php if (in_array($select_hash_key, model("{$alias}_{$key}")->hidden_list)) continue; ?>
            <div class="form-group">
              <label><?php l($select_hash_key); ?></label>
              <div name="<?php h($select_hash_key); ?>"></div>
            </div>
<?php endforeach; ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php l('crud_cancel'); ?></button>
            <button type="button" class="btn btn-danger ajax" onclick="<?php h($alias); ?><?php h(ucfirst($row_action)); ?>(this.form);"><?php l("crud_button_{$key}"); ?></button>
          </div>
        </div>
      </div>
    </form>
<?php $parent_id = $vars['model']->parent_id !== NULL ? $vars['model']->parent_id : '_'; ?>
<script>
function <?php h($alias); ?>Pre<?php h(ucfirst($row_action)); ?>(id)
{
	$.ajax({
		dataType: 'json',
		url: '<?php href($table); ?>/<?php h($key); ?>/' + id + '/<?php h($alias); ?>/<?php h($parent_id); ?>',
		success: function(json) {
			$('#<?php h($alias); ?><?php h(ucfirst($row_action)); ?>').attr('affectId', id);
<?php foreach (array_keys(model("{$alias}_{$key}")->select_hash) as $select_hash_key): ?>
			$('#<?php h($alias); ?><?php h(ucfirst($row_action)); ?> [name=<?php h($select_hash_key); ?>]').html(json.<?php h($select_hash_key); ?>);
<?php endforeach; ?>
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert(XMLHttpRequest.responseText);
		},
	});
}
function <?php h($alias); ?><?php h(ucfirst($row_action)); ?>(form)
{
	$.ajax({
		type: 'POST',
		url: '<?php href($table); ?>/<?php h($key); ?>/' + $(form).attr('affectId') + '/<?php h($alias); ?>/<?php h($parent_id); ?>',
		data: 'dummy=dummy',
		success: function() {
			$(window).scrollTop(0);
			location.href = <?php echo isset($vars['model']->success_hash[$key]) ? "'".href($vars['model']->success_hash[$key], TRUE, TRUE, TRUE)."'" : "'".href($table, TRUE, TRUE, TRUE)."'"; ?>;
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert(XMLHttpRequest.responseText);
		},
	});
}
</script>
<?php endforeach; ?>
