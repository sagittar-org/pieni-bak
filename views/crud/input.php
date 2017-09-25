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
<?php /* HTML */ ?>
<?php elseif (preg_match('/_html$/', $vars['key'])): ?>
<textarea class="form-control summernote" name="<?php h($vars['key']); ?>"></textarea>
<link rel="stylesheet" href="<?php direct('summernote/summernote.css'); ?>">
<script type="text/javascript" src="<?php direct('summernote/summernote.js'); ?>"></script>
<script type="text/javascript" src="<?php direct('summernote/summernote-ja-JP.js'); ?>"></script>
<script type="text/javascript">
$(function() {
  $('.summernote').summernote({
    styleTags: ['p', 'h1', 'h2', 'h3'],
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'italic', 'underline', 'clear']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['table', ['table']],
      ['insert', ['link', 'picture', 'video']],
    ],
    height: 300,
    tabsize: 2,
    lang: 'ja-JP'
  });
});
</script>
<?php /* 画像 */ ?>
<?php elseif (preg_match('/_image$/', $vars['key'])): ?>
<canvas class="<?php h($vars['key']); ?>_canvas" width="800" height="600" style="display:none"></canvas>
<input name="<?php h($vars['key']); ?>" style="display:none">
<div><img class="<?php h($vars['key']); ?>_preview" style="width:200px;"></div>
<input type="file" accept="image/*" capture class="<?php h($vars['key']); ?>_file">
<script>
$('#<?php h($vars['form_id']); ?>').find('.<?php h($vars['key']); ?>_file').on('change', function(){
	var reader = new FileReader();
	reader.readAsDataURL(this.files[0]);
	reader.onloadend = function() {
		var image = new Image();
		var canvas = $('#<?php h($vars['form_id']); ?>').find('.<?php h($vars['key']); ?>_canvas')[0];
		image.src = reader.result;
		image.onload = function() {
			var landscape = image.width / image.height >= canvas.width / canvas.height;
			sw = landscape ? image.height * canvas.width / canvas.height : image.width;
			sh = landscape ? image.height : image.width * canvas.height / canvas.width;
			sx = landscape ? (image.width - sw) / 2 : 0;
			sy = landscape ? 0 : (image.height - sh) / 2;
			canvas.getContext('2d').drawImage(image, sx, sy, sw, sh, 0, 0, canvas.width, canvas.height);
			$('#<?php h($vars['form_id']); ?>').find('.<?php h($vars['key']); ?>_preview').attr('src', canvas.toDataURL());
			$('#<?php h($vars['form_id']); ?>').find('[name=<?php h($vars['key']); ?>]').val(canvas.toDataURL());
		};
	};
});
</script>
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
