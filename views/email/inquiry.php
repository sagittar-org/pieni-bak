<?php h($vars['name']); ?> 様

下記内容でお問い合わせを受け付けました。
折り返し連絡させていただきます。
よろしくお願いいたします。

<?php l('inquiry_email'); ?>:
<?php h($vars['email']."\n"); ?>

<?php l('inquiry_message'); ?>:
<?php h($vars['message'])."\n"; ?>
