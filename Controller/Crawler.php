<?php

class Crawler extends Controller {

  public function __construct() {
    $this->dom = new DOMDocument();
  }

  // default method if there is no method
  public function index() {
    $data = [
      'title' => 'Let\'s crawl a website',
      'message' => '',
    ];

    $this->view('crawlerForm', $data);
  }

  /**
   * Returns form action url
   */
  public function getAction($action_url) {
    return URL_ROOT. 'crawler/' . strtolower(__CLASS__) . '/' . $action_url;
  }

  /**
   * Function initiates the crawling calculations
   * Form action is redirected to here
   */
  public function calculate() {
    $data = [];
    $error = false;
    $input_url = parse_url($_POST['web_url'], PHP_URL_SCHEME) === null ? 'http://' . $_POST['web_url'] : $_POST['web_url'];

    $validateInput = $this->validateUrl($input_url);
    $data = [
      'message' => $validateInput['message'],
      'valid' =>  $validateInput['valid'],
    ];

    if($validateInput['valid']) {
      $data['status'] = 'success';
      
      $parseUrl = parse_url($input_url);
      $hostDomain = $parseUrl['host'];

      // initiaize
      $limit = $depth = 5;
      $resultArray = $total_imgs = $total_internal_links = $total_external_links = [];
      $total_page_load_time = $total_word_count = $total_title_length = 0;

      // create new CrawlService object with input url
      $CrawlService = new CrawlService($input_url, $hostDomain);
      $resultArray[] = $CrawlService->prepareResults();
      $uniqueInternalLinks = $CrawlService->getUniqueLinks();

      // iterate through internal links untill limit = 0
      foreach($uniqueInternalLinks->internal_links as $internalLink) {
        $limit = $limit - 1;
        if($limit == 0) break;

        $parseUrlinternalLink = parse_url($internalLink);
        $hostDomain = $parseUrl['host'];
        if(isset($parseUrlinternalLink['host']) === false) {
          $internalLink = rtrim($input_url,'/').$internalLink;
        }
        $CrawlServiceinternalLink = new CrawlService($internalLink, $hostDomain);
        $resultArray[] = $CrawlServiceinternalLink->prepareResults();
      }
      
      foreach($resultArray as $result) {
        $total_imgs = array_merge($total_imgs, $result['images']);
        $total_internal_links = array_merge($total_internal_links, $result['links']->internal_links);
        $total_external_links = array_merge($total_external_links, $result['links']->external_links);
        $total_page_load_time = $total_page_load_time + $result['time'];
        $total_word_count = $total_word_count + $result['word_count'];
        $total_title_length = $total_title_length + $result['title_length'];
      }

      $avg_page_load_time = ($total_page_load_time > 0) ? $total_page_load_time/$depth : 0;
      $avg_word_count = ($total_word_count > 0) ? $total_word_count/$depth : 0;
      $avg_title_length = ($total_title_length > 0) ? $total_title_length/$depth : 0;

      $data['number_of_unique_imgs'] = count(array_unique($total_imgs));
      $data['number_of_unique_internal_links'] = count(array_unique($total_internal_links));
      $data['number_of_unique_external_links'] = count(array_unique($total_external_links));
      $data['avg_page_load_time'] = $avg_page_load_time;
      $data['avg_word_count'] = $avg_word_count;
      $data['avg_title_length'] = $avg_title_length;
      $data['results'] = $resultArray;
    } else {
      $data['status'] = 'error';
    }
      
    echo json_encode($data);
  }

  public function validateUrl($url) {
    $valid = FALSE;
    $message = '';

    try {
      //$valid = $this->dom->loadHTMLFile($url);
      $headers = @get_headers($url);
      $valid = is_array($headers) ? true : false;
      $message = (!$valid) ? 'Invalid Web Url' : '';
    }
    catch(Exception $e) {
      $message = $e->getMessage();
    }

    return ['valid' => $valid, 'message' => $message];
  }

}