<?php

function getOption($option, $check = false, $profile = false, $id = 1){
  
  global $dbh;
  
  if(isEmpty($option)){
    return false;
  }
  
  $option = trim($option);
  
  if($profile){
    
    $params = array(
      ':option' => $option,
      ':id'     => $id
    );
    
    $sql = "
      SELECT `profile_value`
      FROM `login_profiles`
      WHERE `pfield_id` = :option
      AND `user_id` = :id
    ";
  }else{
    $params = array(
      ':option' => $option
    );
    
    $sql = "
      SELECT `option_value`
      FROM `login_settings`
      WHERE `option_name` = :option
    ";
  }
  
  $stmt = $dbh->prepare($sql);
  $stmt->execute($params);
  
  if(!$stmt) return false;
  
  $result = $stmt->fetch(PDO::FETCH_NUM);
  $result = $result ? $result[0] : false;
  
  if($check)
    $result = !empty($result) ? 'checked="checked"' : '';
  
  return $result;
  
}

?>
