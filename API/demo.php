<?php

  include ('config/_db.php');

try {

  //echo $_GET['idUser'].'<br>'.$_GET['type'];

  if ( !isset($_GET['type']) ) {
    $db = null; // we close db connection
    $json['request']['status'] = 'error';
    $json['request']['message'] = 'Sorry you can\'t access to this page without the right parameters';
    header('Content-type: application/json');
    echo json_encode($json);
    die(); // we kill the script

  } else {

    $type = $_GET['type'];

  }

  if ($type == 'accountsList') {

    $json['request']['status'] = 'success';
    $json['request']['message'] = 'Congrats. You have all the requested information';

    $sqlRequest = "
      SELECT
      CONCAT(`clients`.`lastname`,' ',`clients`.`firstname`) as name,
      `accountTypes`.`name` as typeAccount,
      `accounts`.`sepaN` as iban,
      `accounts`.`balance` as balance

      FROM `accounts`

      LEFT JOIN `clientsAccounts` ON `clientsAccounts`.`accountID` = `accounts`.`id`
      LEFT JOIN `accountTypes` ON `accounts`.`typeID` = `accountTypes`.`id`
      LEFT JOIN `clients` ON `clientsAccounts`.`clientID` = `clients`.`id`";

    $statement = $db->query($sqlRequest);
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      $json['accounts'][] = $row;
    }

    $db = null;
    $statement = null;
    // we return all the information in json
    header('Content-type: application/json');
    echo json_encode($json);

  } else {

    $json['request']['status'] = 'error';
    $json['request']['message'] = 'Sorry you can\'t access to this page without the right parameters';
    header('Content-type: application/json');
    echo json_encode($json);
    $db = null; // we close db connection
    die(); // we kill the rest of the code

  }



} catch (PDOException $e) {

  print_r ("/!\ Error: " . $e->getMessage() . "<br>");
  die();

}


?>
