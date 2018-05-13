<?php

  include ('config/_db.php');

try {

  //echo $_GET['idUser'].'<br>'.$_GET['type'];

  if ( !isset($_GET['idUser']) && !isset($_GET['type']) ) {
    $db = null; // we close db connection
    $json['request']['status'] = 'error';
    $json['request']['message'] = 'Sorry you can\'t access to this page without the right parameters';
    header('Content-type: application/json');
    echo json_encode($json);
    die(); // we kill the script

  } else {

    $idUser = $_GET['idUser'];
    $type = $_GET['type'];

  }

  if ($type == 'accountsList') {

    $json['request']['status'] = 'success';
    $json['request']['message'] = 'Congrats. You have all the requested information';

    $sqlRequest = "
      SELECT
      `accounts`.`id` as idAccount,
      `accounts`.`sepaN` as iban,
      `accounts`.`balance` as balance,
      `accountTypes`.`name` as typeAccount

      FROM `accounts`

      LEFT JOIN `clientsAccounts` ON `clientsAccounts`.`accountID` = `accounts`.`id`
      LEFT JOIN `accountTypes` ON `accounts`.`typeID` = `accountTypes`.`id`

      WHERE `clientsAccounts`.`clientID` = :idUser";

    $statement = $db->prepare($sqlRequest);
    $statement->bindValue(':idUser', $idUser, PDO::PARAM_INT);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      $json['accounts'][] = $row;
    }

    $db = null;
    $statement = null;
    // we return all the information in json
    header('Content-type: application/json');
    echo json_encode($json);

  } elseif ($type == 'cardsList') {

    $json['request']['status'] = 'success';
    $json['request']['message'] = 'Congrats. You have all the requested information';

    $sqlRequest = "
      SELECT
      `cards`.`cardNumber`,
      `cards`.`pinCode`,
      `cardTypes`.`name` as cardType

      FROM `cards`
      LEFT JOIN `cardTypes` ON `cards`.`typeID` = `cardTypes`.`id`

      WHERE `cards`.`clientID` = :idUser";

    $statement = $db->prepare($sqlRequest);
    $statement->bindValue(':idUser', $idUser, PDO::PARAM_INT);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      $json['cards'][] = $row;
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
