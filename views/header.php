    <style>body {padding-top: 50px;}</style>
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php href(''); ?>"><?php l('actor_'.uri('actor')); ?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">

<?php /* エンティティ */ ?>
<?php foreach (config('uri')['table_list'] as $table): ?>
<?php   if (fallback(ucfirst($table).'_model.php', 'models') !== NULL && ! in_array('index', array_keys(load_model($table, ['actor' => uri('actor'), 'class' => $table, 'alias' => $table, 'auth' => $_SESSION[uri('actor')]['auth']], "header_{$table}")->action_hash))) continue; ?>
            <li<?php if (uri('class') === $table): ?> class="active"<?php endif; ?>><a href="<?php href($table); ?>"><?php l($table); ?></a></li>
<?php endforeach; ?>

          </ul>
          <ul class="nav navbar-nav navbar-right">
<?php /* 認証 */ ?>
<?php // ログイン済み ?>
<?php if (isset($_SESSION[uri('actor')]['auth']['name'])): ?>
            <li><a href="<?php href(config('uri')['actor_hash'][uri('actor')].'/view/'.$_SESSION[uri('actor')]['auth']['id']); ?>"><?php h($_SESSION[uri('actor')]['auth']['name']); ?></a></li>
            <li><a href="<?php href('auth/logout/'.uri('actor'), TRUE, FALSE); ?>"><?php l('auth_logout'); ?></a></li>
<?php // 未ログイン ?>
<?php else: ?>
<?php   foreach (config('auth') as $key => $auth): ?>
<?php     if (isset($auth['join'])): ?>
            <li><a href="<?php href("auth/join/{$key}", TRUE, FALSE); ?>"><?php l("join_{$key}"); ?></a></li>
<?php     endif; ?>
<?php     if (isset($auth['login'])): ?>
            <li><a href="<?php href($key, TRUE, FALSE); ?>"><?php l("actor_{$key}"); ?></a></li>
<?php       break; ?>
<?php     endif; ?>
<?php   endforeach; ?>
<?php endif; ?>

            <li><a href="<?php href('inquiry'); ?>"><?php l('inquiry'); ?></a></li>

<?php /* 言語 */ ?>
<?php if (count(config('uri')['language_list']) > 1): ?>
<?php $uri_string = uri('language') === config('uri')['language_list'][0] ? uri('uri_string') : preg_replace('#^'.uri('language').'/?#', '', uri('uri_string')); ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Language <span class="caret"></span></a>
              <ul class="dropdown-menu">
<?php   foreach (config('uri')['language_list'] as $language): ?>
                <li><a href="<?php href(($language === config('uri')['language_list'][0] ? '' : "{$language}/").$uri_string, FALSE, FALSE); ?>"><?php l($language); ?></a></li>
<?php   endforeach; ?>
              </ul>
            </li>
<?php endif; ?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
