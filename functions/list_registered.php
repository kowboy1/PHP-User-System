<?php

/**
 * List recently registered users
 */

function list_registered() {
  
  global $generic;
  global $sql;
  global $query;
  
  $pagination = pagination('login_users','ORDER BY timestamp DESC');

  /** Check that at least one row was returned. */
  $query = $generic->query($sql);
  
  if($query->rowCount() > 0) {
    
    /**
     * Display table of recently registered users.
     */
    
    echo '
      <table class="table">
        
        <thead>
          <tr>
            <th>' . _('Username') . '</th>
            <th>' . _('Name') . '</th>
            <th>' . _('Email') . '</th>
            <th>' . _('Registered Date') . '</th>
            <th>' . _('Last Login') . '</th>
          </tr>
        </thead>
        
        <tbody>';
          while($row = $query->fetch(PDO::FETCH_ASSOC)) {
            echo displayUsers($row);
          }
          echo '
        </tbody>
        
      </table>
    ';
    
    echo $pagination;
    
  } else {
    echo _('Sorry, there are no recently registered users.');
  }
}

?>
