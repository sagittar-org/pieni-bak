<?php $table = $vars['model']->table; ?>
<?php $alias = $vars['model']->alias; ?>
<?php foreach ($vars['model']->action_hash as $key => $row_action): ?>
<?php if ($row_action !== 'add') continue; ?>
<?php load_model($table, ['actor' => uri('actor'), 'class' => $table, 'alias' => $alias, 'method' => $key, 'auth' => $_SESSION[uri('actor')]['auth']], "{$alias}_{$key}"); ?>
    <form class="modal fade" id="<?php h($alias); ?>Add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" onsubmit="return false;">
      <input type="hidden" name="dummy" value="dummy">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel"><?php l('crud_add'); ?> - <?php l($alias); ?></h4>
          </div>
          <div class="modal-body">
<?php foreach (array_unique(array_merge(array_keys(model("{$alias}_{$key}")->select_hash), model("{$alias}_{$key}")->set_list)) as $select_hash_key): ?>
<?php if (in_array($select_hash_key, model("{$alias}_{$key}")->hidden_list)) continue; ?>
            <div class="form-group">
<?php if (in_array($select_hash_key, model("{$alias}_{$key}")->set_list)): ?>
              <label><?php l($select_hash_key); ?></label>
              <?php load_view('input', ['key' => $select_hash_key, 'form_id' => $alias.ucfirst($vars['key'])], $table); ?>
<?php else: ?>
              <label><?php l($select_hash_key); ?></label>
              <div name="<?php h($select_hash_key); ?>"></div>
<?php endif; ?>
            </div>
<?php endforeach; ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php l('crud_cancel'); ?></button>
            <button type="button" class="btn btn-primary ajax" onclick="<?php h($alias); ?>Add(this.form);"><?php l('crud_button_add'); ?></button>
          </div>
        </div>
      </div>
    </form>
<?php $parent_id = $vars['model']->parent_id !== NULL ? $vars['model']->parent_id : '_'; ?>
<script>
function <?php h($alias); ?>PreAdd(form)
{
<?php foreach ($vars['model']->set_list as $set): ?>
	form.find('[name=<?php h($set); ?>]').val('');
<?php endforeach; ?>
}
function <?php h($alias); ?>Add(form)
{
	$.ajax({
		type: 'POST',
		url: '<?php href($table); ?>/add/-/<?php h($alias); ?>/<?php h($parent_id); ?>',
		data: $(form).serialize(),
		success: function() {
			$(window).scrollTop(0);
			location.href = <?php echo isset($vars['model']->success_hash[$key]) ? "'".href($vars['model']->success_hash[$key], TRUE, TRUE, TRUE)."'" : "location.href.replace(/\?.*/, '')"; ?>;
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert(XMLHttpRequest.responseText);
		},
	});
}
</script>
<?php endforeach; ?>
