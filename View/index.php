<div class="container">
  <div class="row">
    <h2><?php echo $data['title']; ?></h2>
  </div>

  <div class="row">
    <ul>
      <?php foreach($data['posts'] as $value){ ?>
        <li><?php echo $value->title; ?></li>
      <?php } ?>
    </ul>
  </div>
</div>