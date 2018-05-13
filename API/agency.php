<?php

  include ('config/_db.php');

try {

  $json = [];

  if ( !isset($_GET['type']) ) {
    $db = null; // we close db connection
    $json['request']['status'] = 'error';
    $json['request']['message'] = 'Sorry you can\'t access to this page without the right parameters';
    header('Content-type: application/json');
    echo json_encode($json);
    die(); // we kill the script

  } else {

    if ( isset($_GET['idAgency']) ) {
      $idAgency = $_GET['idAgency'];
    }
    $type = $_GET['type'];

  }

  if($type == 'agencyList') {

    $json['request']['status'] = 'success';
    $json['request']['message'] = 'Congrats. You have all the requested information';

    $sqlRequest = "
      SELECT
      `branches`.`id` as idBranch,
      `branches`.`address`,
      `branches`.`postalCode`,
      `branches`.`city`,
      `countries`.`name` as country,
      `branches`.`phone`,
      `branches`.`email`

      FROM `branches`
      LEFT JOIN `countries` ON `branches`.`country` = `countries`.`id`";
    $statement = $db->query($sqlRequest);

    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      $json['branches'][$row['idBranch']] = $row;

      $sqlSchedule = "
      SELECT
      `schedule`.`day`,
      `schedule`.`startAM`,
      `schedule`.`endAM`,
      `schedule`.`startPM`,
      `schedule`.`endPM`

      FROM  `schedule`

      WHERE `schedule`.`branchID` =".$row['idBranch'];
      $statementSchedule = $db->query($sqlSchedule);
      while ($rowSchedule = $statementSchedule->fetch(PDO::FETCH_ASSOC)) {
        $json['branches'][$row['idBranch']]['schedule'][] = $rowSchedule;
      }
      $statementSchedule = null;
    }

    $db = null;
    $statement = null;

    // we return all the information in json
    header('Content-type: application/json');
    echo json_encode($json);

  } elseif ($type == 'agencyInformation') {

      $json['request']['status'] = 'success';
      $json['request']['message'] = 'Congrats. You have all the requested information';

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
      $statement->bindValue(':IDbranch',$idAgency, PDO::PARAM_INT);
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
      $statement->bindValue(':IDbranch',$idAgency, PDO::PARAM_INT);
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
