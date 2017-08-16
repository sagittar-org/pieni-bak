<?php /* 日時 */ ?>
<?php if (preg_match('/_created$/', $vars['key']) OR preg_match('/_datetime$/', $vars['key'])): ?>
<input type="text" class="form-control datetime" name="<?php h($vars['key']); ?>">
<?php /* 日付 */ ?>
<?php elseif (preg_match('/_date$/', $vars['key'])): ?>
<input type="text" class="form-control date" name="<?php h($vars['key']); ?>">
<?php /* 日付リスト */ ?>
<?php elseif (preg_match('/_datelist$/', $vars['key'])): ?>
<input type="text" class="form-control datelist" name="<?php h($vars['key']); ?>">
<?php /* 時刻 */ ?>
<?php elseif (preg_match('/_time$/', $vars['key'])): ?>
<input type="text" class="form-control time" name="<?php h($vars['key']); ?>">
<?php /* テキスト */ ?>
<?php elseif (preg_match('/_text$/', $vars['key'])): ?>
<textarea type="text" class="form-control" name="<?php h($vars['key']); ?>" rows="20"></textarea>
<?php /* 画像 */ ?>
<?php elseif (preg_match('/_image$/', $vars['key'])): ?>
<input type="hidden" name="<?php h($vars['key']); ?>"><input type="file" onchange="(function(t){
	var reader = new FileReader();
	reader.onload = function(e) {
		t.previousSibling.value = e.target.result;
	};
	reader.readAsDataURL(t.files[0]);
})(this);">
<?php /* ファイル */ ?>
<?php elseif (preg_match('/_file$/', $vars['key'])): ?>
<input type="hidden" name="<?php h($vars['key']); ?>"><input type="file" onchange="(function(t){
	$([name=<?php h(preg_replace('/_file$/', '_name', $vars['key'])); ?>]).val(t.files[0].name);
	var reader = new FileReader();
	reader.onload = function(e) {
		t.previousSibling.value = e.target.result;
	};
	reader.readAsDataURL(t.files[0]);
})(this);">
<?php /* パスワード */ ?>
<?php elseif (preg_match('/_password$/', $vars['key'])): ?>
<input type="password" class="form-control" name="<?php h($vars['key']); ?>">
<?php /* 公開状態 */ ?>
<?php elseif (preg_match('/_public$/', $vars['key'])): ?>
<select name="<?php h($vars['key']); ?>" class="form-control">
<option value="crud_public_open"><?php l('crud_public_open'); ?></option>
<option value="crud_public_closed"><?php l('crud_public_closed'); ?></option>
</select>
<?php /* デフォルト */ ?>
<?php else: ?>
<input type="text" class="form-control" name="<?php h($vars['key']); ?>">
<?php endif; ?>
