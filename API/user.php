<?php

  include ('config/_db.php');

try {

  $json = [];

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

  if ($type == 'informationAboutUser') {

    $json['request']['status'] = 'success';
    $json['request']['message'] = 'Congrats. You have all the requested information';

    // We collect information about the user
    $sqlRequest = "
      SELECT
      `clients`.`id` as idClient,
      `clients`.`lastname`,
      `clients`.`firstname`,
      `clients`.`birthdate`,
      `clients`.`address`,
      `clients`.`postalCode`,
      `clients`.`city`,
      `countries`.`name` as country,
      `clients`.`phone`,
      `clients`.`nationalID`,
      `clients`.`language`,
      `clients`.`email`,
      `clients`.`branchID`

      FROM `clients`
      LEFT JOIN `countries` ON `clients`.countryID = `countries`.`id`
      WHERE `clients`.`id` = :idUser LIMIT 0,1";
    $statement = $db->prepare($sqlRequest);
    $statement->bindValue(':idUser', $idUser, PDO::PARAM_INT);
    $statement->execute();
    $json['user'] = $statement->fetch(PDO::FETCH_ASSOC); // we don't need a loop because there is only one result

    // we link the accounts of the user
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

    // we link the cards of the user
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

    // we link the agency of the user
    $sqlRequest = "
      SELECT
      `branches`.`address`,
      `branches`.`postalCode`,
      `branches`.`city`,
      `countries`.`name`,
      `branches`.`phone`,
      `branches`.`email`

      FROM `branches`
      LEFT JOIN `countries` ON `branches`.country = `countries`.`id`

      WHERE `branches`.`id` = :IDbranch LIMIT 0,1";

    $statement = $db->prepare($sqlRequest);
    $statement->bindValue(':IDbranch', $json['user']['branchID'], PDO::PARAM_INT);
    $statement->execute();

    $json['branch'] = $statement->fetch(PDO::FETCH_ASSOC); // we don't need a loop because there is only one result

    $sqlRequest = "
    SELECT
    `schedule`.`day`,
    `schedule`.`startAM`,
    `schedule`.`endAM`,
    `schedule`.`startPM`,
    `schedule`.`endPM`

    FROM  `schedule`

    WHERE `schedule`.`branchID` = :IDbranch";
    $statement = $db->prepare($sqlRequest);
    $statement->bindValue(':IDbranch',$json['user']['branchID'], PDO::PARAM_INT);
    $statement->execute();

    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      $json['branch']['schedule'][] = $row;
    }

    $db = null;
    $statement = null;
    // we return all the information in json
    header('Content-type: application/json');
    echo json_encode($json);

  } elseif ($type == 'agencyUser') {

     $json['request']['status'] = 'success';
     $json['request']['message'] = 'Congrats. You have all the requested information';

     $sqlRequest = "
       SELECT
       `clients`.`branchID`

       FROM `clients`
       LEFT JOIN `countries` ON `clients`.countryID = `countries`.`id`
       WHERE `clients`.`id` = :idUser LIMIT 0,1";
     $statement = $db->prepare($sqlRequest);
     $statement->bindValue(':idUser', $idUser, PDO::PARAM_INT);
     $statement->execute();
     $branch = $statement->fetch(PDO::FETCH_ASSOC);

     $sqlRequest = "
       SELECT
       `branches`.`address`,
       `branches`.`postalCode`,
       `branches`.`city`,
       `countries`.`name`,
       `branches`.`phone`,
       `branches`.`email`

       FROM `branches`
       LEFT JOIN `countries` ON `branches`.country = `countries`.`id`

       WHERE `branches`.`id` = :IDbranch LIMIT 0,1";

     $statement = $db->prepare($sqlRequest);
     $statement->bindValue(':IDbranch',$branch['branchID'], PDO::PARAM_INT);
     $statement->execute();

     $json['branch'] = $statement->fetch(PDO::FETCH_ASSOC); // we don't need a loop because there is only one result

     $sqlRequest = "
     SELECT
     `schedule`.`day`,
     `schedule`.`startAM`,
     `schedule`.`endAM`,
     `schedule`.`startPM`,
     `schedule`.`endPM`

     FROM  `schedule`

     WHERE `schedule`.`branchID` = :IDbranch";
     $statement = $db->prepare($sqlRequest);
     $statement->bindValue(':IDbranch',$branch['branchID'], PDO::PARAM_INT);
     $statement->execute();

     while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
       $json['branch']['schedule'][] = $row;
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

  $json['request']['status'] = 'error';
  $json['request']['message'] = 'Error: ' . $e->getMessage();
  header('Content-type: application/json');
  echo json_encode($json);
  die();

}

?>
