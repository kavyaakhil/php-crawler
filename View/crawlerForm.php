<div class="container">
  <div class="row">
    <h2><?php echo $data['title']; ?></h2>
  </div>
  
  <div class="row">
    <form id="crawlerForm" action="<?php echo Crawler::getAction('calculate'); ?>" method="post">
      <div class="form-group">
        <label for="exampleInput">Enter the website address</label>
        <input type="url" name="web_url" class="form-control" id="urlInput" aria-describedby="urlHelp" placeholder="Enter url">
        <small id="urlHelp" class="form-text text-muted">Enter the web url you would like to crawl</small>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
      <div id="formSpinner" class="spinner-border d-none" role="status">
        <span class="sr-only">Loading...</span>
      </div>
    </form>
    <div id="result" class="container"></div>
  </div>
</div>