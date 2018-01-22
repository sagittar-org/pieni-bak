<style>
html {
	position: relative;
	min-height: 100%;
}
body {
	margin-bottom: 75px;
}
.footer {
	position: absolute;
	bottom: 0;
	width: 100%;
	height: 55px;
}
</style>
    <footer class="footer">
      <nav class="navbar navbar-default" style="margin-bottom:0;">
        <div class="container">
<?php if (in_array(uri('actor'), config('spec')['actor_list'])): ?>
            <ul class="nav navbar-nav">
              <li><a href="<?php href(''); ?>"><?php l('actor_'.uri('actor')); ?></a></li>
              <li><a href="<?php href('spec'); ?>"><?php l('spec'); ?></a></li>
            </ul>
<?php endif; ?>
        </div>
      </nav>
    </footer>
