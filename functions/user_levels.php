<?php

function user_levels() {
  
	$pagination = pagination('login_levels');

	global $sql, $query, $generic;

	/* Check that at least one row was returned */
	$stmt = $generic->query($sql);
	
	if($stmt->rowCount() < 1){
    return false;
	}
	
	echo '
    <table class="table table-hover">
			<thead>
				<tr>
					<th>' . _('Name') . '</th>
					<th>' . _('Active Users') . '</th>
					<th>' . _('Redirect') . '</th>
				</tr>
			</thead>
			
			<tbody>';
				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          
          /* Count of users in this role */
          $lid = $row['id'];
          
          $params = [
            ':user_level' => "%:\"$lid\";%"
          ];
          
          $query = $generic->query("
            SELECT COUNT(user_level) as num
            FROM login_users
            WHERE user_level LIKE :user_level
            ",
            $params
          );
          
          $count = $query->fetch(PDO::FETCH_ASSOC);
          $count = $count['num'];
          
          /* Admin role? */
          $admin = ($row['id'] == 1) ? ' <span class="label label-danger">*</span>' : '';
          
          /* Disabled role? */
          $status = !empty($row['level_disabled']) ? ' <span class="label label-warning">' . _('Disabled') . '</span>' : '';
        
          echo '
            <tr>
              <td><a href="levels.php?lid=' . $lid . '">' . $row['level_name'] . '</a>' . $status . '</td>
              <td width="15%">' . $count . '</td>
              <td><a href="' . $row['redirect'] . '">' . $row['redirect'] . '</a></td>
            </tr>
          ';
        }
        echo '
			</tbody>
    </table>
	';
	
	echo $pagination;
}

?>
