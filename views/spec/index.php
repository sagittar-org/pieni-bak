<style>
th { width: 200px; }
</style>

<h3><?php l('config'); ?></h3>
<h4><?php l('package_list'); ?></h4>
<ul>
<?php foreach (config('package_list') as $package): ?>
<li><?php l($package); ?></li>
<?php endforeach; ?>
</ul>

<h4><?php l('uri'); ?></h4>
<table class="table">
<tr>
<th title="<?php h('language_list'); ?>"><?php l('language_list'); ?></th>
<td>
<?php foreach (config('uri')['language_list'] as $language): ?>
<?php if ($language !== config('uri')['language_list'][0]) h(', '); ?>
<span title="<?php h($language); ?>"><?php l($language); ?></span><?php ?>
<?php endforeach; ?>
</td>
</tr>
<tr>
<th title="<?php h('actor_hash'); ?>"><?php l('actor_hash'); ?></th>
<td>
<?php foreach (config('uri')['actor_hash'] as $key => $actor): ?>
<?php if ($key !== array_keys(config('uri')['actor_hash'])[0]) h(', '); ?>
<span title="<?php h($key); ?>"><?php l($key); ?></span><?php ?>
<?php endforeach; ?>
</td>
</tr>
<tr>
<th title="<?php h('class_list'); ?>"><?php l('class_list'); ?></th>
<td>
<?php foreach (config('uri')['class_list'] as $class): ?>
<?php if ($class !== config('uri')['class_list'][0]) h(', '); ?>
<span title="<?php h($class); ?>"><?php l($class); ?></span><?php ?>
<?php endforeach; ?>
</td>
</tr>
<tr>
<th title="<?php h('table_list'); ?>"><?php l('table_list'); ?></th>
<td>
<?php foreach (config('uri')['table_list'] as $table): ?>
<?php if ($table !== config('uri')['table_list'][0]) h(', '); ?>
<span title="<?php h($table); ?>"><?php l($table); ?></span><?php ?>
<?php endforeach; ?>
</td>
</tr>
</table>

<h4><?php l('auth'); ?></h4>
<?php foreach (config('auth') as $actor => $hash): ?>
<h5><?php l($actor); ?></h5>
<table class="table">
<?php foreach ($hash as $key => $value): ?>
<tr>
<th title="<?php h($key); ?>"><?php l("auth_{$key}"); ?></th>
<td><?php h($value); ?></td>
</tr>
<?php endforeach; ?>
</table>
<?php endforeach; ?>

<h4><?php l('db'); ?></h4>
<table class="table">
<tr>
<th title="<?php h('db_debug'); ?>"><?php l('db_debug'); ?></th>
<td><?php h(config('db')['debug'] === TRUE ? 'Yes' : 'No'); ?></td>
</tr>
<tr>
<th title="<?php h('db_host'); ?>"><?php l('db_host'); ?></th>
<td><?php h(config('db')['host']); ?></td>
</tr>
<tr>
<th title="<?php h('db_username'); ?>"><?php l('db_username'); ?></th>
<td><?php h(config('db')['username']); ?></td>
</tr>
<tr>
<th title="<?php h('db_passwd'); ?>"><?php l('db_passwd'); ?></th>
<td><?php h(config('db')['passwd']); ?></td>
</tr>
<tr>
<th title="<?php h('db_dbname'); ?>"><?php l('db_dbname'); ?></th>
<td><?php h(config('db')['dbname']); ?></td>
</tr>
</table>

<h4><?php l('mail'); ?></h4>
<table class="table">
<tr>
<th><?php l('mail_from'); ?></th>
<td><?php h(config('mail')['from']); ?></td>
</tr>
<tr>
<th><?php l('mail_admin'); ?></th>
<td><?php h(config('mail')['admin']); ?></td>
</tr>
</table>

<h4><?php l('session'); ?></h4>
<table class="table">
<tr>
<th><?php l('session_name'); ?></th>
<td><?php h(config('session')['name']); ?></td>
</tr>
</table>

<h4><?php l('password'); ?></h4>
<table class="table">
<tr>
<th><?php l('password_hash'); ?></th>
<td><?php h(config('password')['hash'] === TRUE ? 'Yes' : 'No'); ?></td>
</tr>
</table>
