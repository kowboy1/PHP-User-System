<?php

function pagination($table, $args = '', $total_pages = '') {

  global $sql, $query, $generic;
  
  /** Hashtags, a workaround for when switching pages and not being redirected to the tab. */
  $hash  = '';

  /** Desired rows per page. */
  $limit = 10;

  /* Setting the page limit and hash. */
  if($table == 'login_levels') {
    
    $hash = '#level-control';
    
    if (!empty($_SESSION['jigowatt']['levels_page_limit'])){
      
      $limit = $_SESSION['jigowatt']['levels_page_limit'];
    }
  }

  if($table == 'login_users') {
    
    $hash = '#user-control';
    
    if (!empty($_SESSION['jigowatt']['users_page_limit'])){
      
      $limit = $_SESSION['jigowatt']['users_page_limit'];
    }
  }

  /** The page number to retrieve. */
  $page = (!empty($_GET['page']) && $_GET['page'] > 0) ? (int)$_GET['page'] : 1;

  if (!empty($_GET['info'])) {
    
    if ($_GET['info'] != $table){
      $page = 1;
    }
  }

  $StartIndex = $limit*($page-1);
  $stages = 3;

  $sql = "SELECT * FROM $table $args LIMIT $StartIndex, $limit";
  $query = "SELECT COUNT(*) as num FROM $table $args";

  $next = $page + 1; $previous = ($page - 1 != 0) ? $page - 1 : $page;

  if (empty($total_pages)) {
    
    $stmt = $generic->query($query);
    
    if ( $stmt ) {
      $total_pages = $stmt->fetch();
      $total_pages = $total_pages['num'];
    }
  }
  
  $lastPage = ceil($total_pages/$limit);
  $lastPage1 = $lastPage - 1;

  $paginate = '';
  if($lastPage > 0) {

    $paginate = '<div class=""><ul class="pagination">';

    // Previous.
    $paginate .= ($page > 1) ? '<li class="prev"><a href="?' . http_build_query(array_merge($_GET, array('info' => $table, "page" => "$previous"))) . $hash . '">&larr; '._('Previous').'</a></li>' : '<li class="prev disabled"><a href="#">&larr; '._('Previous').'</a></li>';

    if($lastPage < 7 + ($stages * 2)) {
      
      for ($counter = 1; $counter <= $lastPage; $counter++) {
        $paginate .= ($counter == $page) ? "<li class='active'><a href='#'>$counter</a></li>" : "<li><a href='?" . http_build_query(array_merge($_GET, array('info' => $table, "page" => "$counter"))) . "$hash'>$counter</a></li>";
      }
    } else if ($lastPage > 5 + ($stages * 2)) {

      /** Hide end pages. */
      if($page < 1 + ($stages * 2)) {
        
        for ($counter = 1; $counter < 4 + ($stages * 2); $counter++) {
          $paginate .= ($counter == $page) ? "<li class='active'><a href='#'>$counter</a></li>" : "<li><a href='?" . http_build_query(array_merge($_GET, array('info' => $table, "page" => "$counter"))) . "$hash'>$counter</a></li>";
        }
        
        $paginate .= "
          <li><a href='#'>&hellip;</a></li>
          <li><a href='?" . http_build_query(array_merge($_GET, array('info' => $table, "page" => "$lastPage1"))) . "$hash'>$lastPage1</a></li>
          <li><a href='?" . http_build_query(array_merge($_GET, array('info' => $table, "page" => "$lastPage"))) . "$hash'>$lastPage</a></li>
        ";
      } else if ($lastPage - ($stages * 2) > $page && $page > ($stages * 2)) {
        
        /** Hide start & end pages. */
        
        $paginate .= "
          <li><a href='?" . http_build_query(array_merge($_GET, array('info' => $table, "page" => "1"))) . "$hash'>1</a></li>
          <li><a href='?" . http_build_query(array_merge($_GET, array('info' => $table, "page" => "2"))) . "$hash'>2</a></li>
          <li><a href='#'>&hellip;</a></li>
        ";

        for ($counter = $page - $stages; $counter <= $page + $stages; $counter++){
          $paginate .= ($counter == $page) ? "<li class='active'><a href='#'>$counter</a></li>" : "<li><a href='?" . http_build_query(array_merge($_GET, array('info' => $table, "page" => "$counter"))) . "$hash'>$counter</a></li>";
        }
        
        $paginate .= "
          <li><a href='#'>&hellip;</a></li>
          <li><a href='?" . http_build_query(array_merge($_GET, array('info' => $table, "page" => "$lastPage1"))) . "$hash'>$lastPage1</a></li>
          <li><a href='?" . http_build_query(array_merge($_GET, array('info' => $table, "page" => "$lastPage1"))) . "$hash'>$lastPage</a></li>
        ";
      } else {
        
        /** Hide start pages. */
        
        $paginate .= "
          <li><a href='?" . http_build_query(array_merge($_GET, array('info' => $table, "page" => "1"))) . "$hash'>1</a></li>
          <li><a href='?" . http_build_query(array_merge($_GET, array('info' => $table, "page" => "2"))) . "$hash'>2</a></li>
          <li><a href='#'>&hellip;</a></li>
        ";
        
        for ($counter = $lastPage - (2 + ($stages * 2)); $counter <= $lastPage; $counter++) {
          $paginate .= ($counter == $page) ? "<li class='active'><a href='#'>$counter</a></li>" : "<li><a href='?" . http_build_query(array_merge($_GET, array('info' => $table, "page" => "$counter"))) . "$hash'>$counter</a></li>";
        }
      }
    }

    /** Next button. */
    $paginate .= ($lastPage != $page) ? '<li class="next"><a href="?' . http_build_query(array_merge($_GET, array('info' => $table, "page" => "$next"))) . $hash . '">'._('Next').' &rarr;</a></li>' : '<li class="next disabled"><a href="#">'._('Next').' &rarr;</a></li>';
    $paginate .= '</ul></div>';

  }

  return $paginate;

}

?>
