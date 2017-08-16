    <div class="jumbotron">
      <div class="container">
        <h1>Welcome to <?php l('project_name'); ?>!</h1>
      </div>
    </div>
    <div class="container">
      <h2><?php l('news'); ?></h2>
<?php load_model('news', ['class' => 'news', 'alias' => 'welcome_news', 'session' => ['limit' => 1]]); ?>
<?php model('welcome_news')->index(); ?>
<?php while (($row = model('welcome_news')->row()) !== NULL): ?>
<?php load_view('card', array_merge(['model' => model('welcome_news')], ['row' => $row]), 'news'); ?>
<?php endwhile; ?>
      <a href="<?php href('news'); ?>" class="btn btn-default" style="width:100%;"><?php l('crud_see_more', [l('news', [], TRUE)]); ?></a>

      <h2><?php l('post'); ?></h2>
<?php load_model('post', ['class' => 'post', 'alias' => 'welcome_post', 'session' => ['limit' => 3]]); ?>
<?php model('welcome_post')->index(); ?>
<?php while (($row = model('welcome_post')->row()) !== NULL): ?>
<?php load_view('card', array_merge(['model' => model('welcome_post')], ['row' => $row]), 'post'); ?>
<?php endwhile; ?>
      <a href="<?php href('post'); ?>" class="btn btn-default" style="width:100%;"><?php l('crud_see_more', [l('post', [], TRUE)]); ?></a>
    </div>
