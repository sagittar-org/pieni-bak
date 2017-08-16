<?php if (isset($_SESSION['flash'])): ?>
    <div class="container">
<?php foreach ($_SESSION['flash'] as $flash): ?>
      <div class="alert alert-<?php echo $flash['class']; ?> alert-dismissible" style="margin-top:20px;">
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        <?php echo $flash['message']; ?>
      </div>
<?php endforeach; ?>
    </div>
<?php unset($_SESSION['flash']); ?>
<?php endif; ?>
