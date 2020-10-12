<?php

/**
 * Find users in the current role
 */

function in_level() {

  global $generic; // todo: fix this dependency

  if( ! isEmpty($_GET['lid'])) {

    $lid = $_GET['lid'];
    $page = ( ! isEmpty($_GET['page']) && $_GET['page'] > 0) ? (int) $_GET['page'] : 1;
    $limit = 10;
    $StartIndex = $limit * ($page - 1);

    $sql = "SELECT * FROM login_users";
    $stmt = $generic->query($sql);

    $count = 0;
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      
      if (array_intersect(array($lid), unserialize($row['user_level']))) {
        $count++;
      }
    }

    if ($count < 1) {
      echo '<p>' . _('No users found!') . '</p>';
      return false;
    }
    
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
          /* Print out each user of this role */
          $params = [
            ':user_level' => "%:\"$lid\";%"
          ];
          
          $sql = "
            SELECT *
            FROM login_users
            WHERE user_level
            LIKE :user_level
            ORDER BY timestamp DESC
            LIMIT $StartIndex,$limit
          ";
          
          $stmt = $generic->query($sql, $params);
          
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo displayUsers($row);
          }
          
          echo '
        </tbody>
      </table>
    ';
    
    echo pagination('login_users', 'ORDER BY timestamp DESC', $count);
  }
}

?>
