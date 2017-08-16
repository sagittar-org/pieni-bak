<h2>vendor/sagittar-org/pieni2/<?php h(implode('/', uri('param_arr'))); ?></h2>
<div class="docs-file">
<?php if ( ! isset($vars['contents'])): ?>
<table class="table">
<tr>
  <th style="width:0; text-align:right;"">Size</th>
  <th>Name</th>
<tr>
<?php if ($vars['dir'] !== ''): ?>
<tr>
  <td style="text-align:right;">-</td>
  <td><span class="glyphicon glyphicon-menu-up"></span> <a href="<?php href('docs/file/'.dirname($vars['dir'])); ?>">Parent Directory</a></td>
</tr>
<?php endif; ?>
<?php foreach ($vars['dir_list'] as $dir): ?>
<tr>
  <td style="text-align:right;">-</td>
  <td><span class="glyphicon glyphicon-folder-close"></span> <a href="<?php href("docs/file/{$vars['dir']}{$dir}"); ?>"><?php h($dir); ?></a></td>
</tr>
<?php endforeach; ?>
<?php foreach ($vars['file_list'] as $file): ?>
<tr>
  <td style="text-align:right;"><?php echo number_format(filesize("./vendor/sagittar-org/pieni2/{$vars['dir']}{$file}")); ?></td>
  <td><span class="glyphicon glyphicon-file"></span> <a href="<?php href("docs/file/{$vars['dir']}{$file}"); ?>"><?php h($file); ?></a></td>
</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<span class="glyphicon glyphicon-folder-close"></span>
<a href="<?php href("docs/file/{$vars['dir']}"); ?>"><?php h("vendor/sagittar-org/pieni2/{$vars['dir']}"); ?></a>
<pre>
<?php h($vars['contents']); ?>
</pre>
<?php endif; ?>
</div>
