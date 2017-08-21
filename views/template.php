<!DOCTYPE html>
<html lang="<?php h(uri('language')); ?>">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php l('project_name'); ?></title>

    <!-- jQuery -->
    <script src="<?php direct('jquery/jquery.min.js'); ?>"></script>

    <!-- Bootstrap -->
    <link href="<?php direct('bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <script src="<?php direct('bootstrap/js/bootstrap.min.js'); ?>"></script>

    <!-- Flatpickr -->
    <link href="<?php direct('flatpickr/flatpickr.min.css'); ?>" rel="stylesheet">
    <script src="<?php direct('flatpickr/flatpickr.min.js'); ?>"></script>
    <script src="<?php direct('flatpickr.js'); ?>"></script>

    <!-- pieni2 -->
    <link href="<?php direct('pieni2.css'); ?>" rel="stylesheet">
    <link href="<?php direct('application.css'); ?>" rel="stylesheet">
    <script src="<?php direct('pieni2.js'); ?>"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  </head>
  <body class="language_<?php h(uri('language')); ?> actor_<?php h(uri('actor')); ?> class_<?php h(uri('class')); ?> method_<?php h(uri('method')); ?>">
<?php load_view('header', $vars, $class); ?>
<?php load_view('flash', $vars, $class); ?>
<?php load_view($view, $vars, $class); ?>
<?php load_view('footer', $vars, $class); ?>
  </body>
</html>
