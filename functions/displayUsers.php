<?php

/**
 * Retrieve a user in table format
 */

function displayUsers($row) {

	global $generic;

	if(isEmpty($row)){
    return false;
	}

	/* Admin user */
	$admin = in_array(1, unserialize($row['user_level'])) ? " <span class='label label-danger'>" . _('admin') . "</span>" : '';

	/* Restricted user */
	$restrict = (int) $row['restricted'] === 0 ? "" : " <span class='label label-warning'>" . _('restricted') . "</span>";

	/* Registered date */
	$timestamp = strtotime($row['timestamp']);
	$reg_date  = date('M d, Y', $timestamp) . ' ' . _('at') . ' ' . date('h:i a', $timestamp);

	/* Last login */
	$params    = array( ':user_id'=> $row['user_id'] );
	$stmt      = $generic->query("SELECT `timestamp` FROM `login_timestamps` WHERE `user_id` = :user_id ORDER BY `timestamp` DESC LIMIT 0,1", $params);
	$timeRow   = $stmt->fetch(PDO::FETCH_NUM);
	$lastLogin = !isEmpty($timeRow) ? date('M d, Y', strtotime($timeRow[0])) . ' ' . _('at') . ' ' . date('h:i a', strtotime($timeRow[0])) : '-';

	/* Email address */
	$email = $row['email'];

	/* Output */
	echo '
    <tr>
      <td>
        <a href="users.php?uid=' . $row['user_id'] . '">' .
          
          $generic->get_gravatar($email, true, 20, 'mm', 'g', array('style' => '1')) . ' ' . $row['username'] . '
        </a>' .
        
        $admin . $restrict . '
      </td>
      
      <td>' . $row['name'] . '</td>
      <td>' . $email . '</td>
      <td>' . $reg_date . '</td>
      <td>' . $lastLogin  . '</td>
    </tr>
  ';
}

?>
