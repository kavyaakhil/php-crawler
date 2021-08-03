<?php

class CrawlService {

  protected $url;

  /**
   * Construct new CrawlService
   */
  public function __construct($url, $hostDomain) {
    $this->url = rtrim($url,'/');
    $this->domCrawl = new DOMDocument('1.0');
    $this->domCrawl->loadHTMLFile($this->url);
    $this->hostDomain = $hostDomain;
    $this->visited = [];
  }

  public function getUniqueImages() {
    $imgs = [];
    foreach($this->domCrawl->getElementsByTagName('img') as $img) {
      $imgs[] = $img->getAttribute('src');
    }
    return $imgs;
  }

  public function getUniqueLinks() {
    $internal_links = [];
    $external_links = [];
    foreach($this->domCrawl->getElementsByTagName('a') as  $aTag) {
      // trim the last slash
      $current_url = rtrim($aTag->getAttribute('href'),'/');
      
      // skip # urls or if it is website main url or already added in our list
      if (strpos($current_url,'#') === true || $current_url == '' || $current_url == $this->url || in_array($current_url, $internal_links)) {
        continue;
      }

      if((substr($aTag->getAttribute('href'), 0, 1) == '/') || (strpos($current_url, $this->hostDomain) !== false)) {
        $internal_links[] = $current_url;
      }
      else {
        if(!in_array($current_url, $external_links)) {
          $external_links[] = $current_url;
        }
      }
    }
    return (object)[
      'internal_links' => $internal_links,
      'external_links' => $external_links
    ];
  }

  public function getTotalWordCount() {
    $xpath = new DOMXPath($this->domCrawl);
    $nodes = $xpath->query('//text()');
    
    $textNodeContent = '';
    foreach($nodes as $node) {
        $textNodeContent .= strip_tags($node->nodeValue);
    }
    return str_word_count( $textNodeContent);
  }

  public function getTitleLength() {
    $title = '';
    $list = $this->domCrawl->getElementsByTagName("title");
    if ($list->length > 0) {
        $title = $list->item(0)->textContent;
    }
    return strlen($title);
  }

  public function getStatus() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $total_time = curl_getinfo($ch, CURLINFO_TOTAL_TIME);

    return [$http_code, $total_time];
  }

  public function prepareResults() {
    list($status_code, $total_time) = $this->getStatus();
    $this->visited = [
      'url' => $this->url,
      'status_code' => $status_code,
      'time' => $total_time,
      'word_count' => $this->getTotalWordCount(),
      'title_length' => $this->getTitleLength(),
      'images' => $this->getUniqueImages(),
      'links' => $this->getUniqueLinks()
    ];
    return $this->visited;
  }
}