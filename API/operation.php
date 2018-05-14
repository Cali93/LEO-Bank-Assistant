<?php

  include ('config/_db.php');

try {

  $json = [];

  if ( !isset($_GET['fromIban']) && !isset($_GET['type']) && !isset($_GET['amount']) && !isset($_GET['communication']) ) {
    $db = null; // we close db connection
    $json['request']['status'] = 'error';
    $json['request']['message'] = 'Sorry you can\'t access to this page without the right parameters';
    header('Content-type: application/json');
    echo json_encode($json);
    die(); // we kill the script

  } else {

    if (isset($_GET['toName'])) {
      $toName = urldecode($_GET['toName']);
    } elseif (isset($_GET['toIban'])) {
      $toIban = $_GET['toIban'];
    }
    $fromUser['iban'] = $_GET['fromIban'];
    $type = $_GET['type'];
    $amount = $_GET['amount'];
    $communication = urldecode($_GET['communication']);

  }

  if ($type == 'bankTransfertWithName') {

    //$json['request']['status'] = 'success';
    //$json['request']['message'] = 'Congrats. You have all the requested information';

    // We collect the ID about the fromUser
    $sqlRequest = "
      SELECT
      `clientsAccounts`.`clientID`
      FROM `accounts`
      LEFT JOIN `clientsAccounts` ON `clientsAccounts`.`accountID` =  `accounts`.`id`
      WHERE `accounts`.`sepaN` LIKE '".$fromUser['iban']."' LIMIT 0,1";
    $statement = $db->query($sqlRequest);
    $result = $statement->fetch(PDO::FETCH_ASSOC); // we don't need a loop because there is only one result
    $fromUser['ID'] = $result['clientID'];
    $statement = null;

    // We collect information about the fromUser
    $sqlRequest = "
      SELECT
      CONCAT(`clients`.`lastname`,' ',`clients`.`firstname`) as name,
      `clients`.`address`,
      `clients`.`postalCode`,
      `clients`.`city`,
      `countries`.`name` as country

      FROM `clients`
      LEFT JOIN `countries` ON `clients`.countryID = `countries`.`id`
      WHERE `clients`.`id` = :idUser LIMIT 0,1";
    $statement = $db->prepare($sqlRequest);
    $statement->bindValue(':idUser', $fromUser['ID'], PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC); // we don't need a loop because there is only one result
    $fromUser['name'] = $result['name'];
    $fromUser['address'] = $result['address'].'\n'.$result['postalCode'].' '.$result['city'].'\n'.$result['country'];
    $statement = null;

    // We find the information of the toUser
    $sqlRequest = "
      SELECT
      `clients`.`id`,
      CONCAT(`clients`.`lastname`,' ',`clients`.`firstname`) as name,
      `clients`.`address`,
      `clients`.`postalCode`,
      `clients`.`city`,
      `countries`.`name` as country

      FROM `clients`
      LEFT JOIN `countries` ON `clients`.countryID = `countries`.`id`
      WHERE CONCAT(`clients`.`lastname`,' ',`clients`.`firstname`) LIKE '".$toName."' LIMIT 0,1";
    $statement = $db->query($sqlRequest);
    $result = $statement->fetch(PDO::FETCH_ASSOC); // we don't need a loop because there is only one result
    $toUser['ID'] = $result['id'];
    $toUser['name'] = $result['name'];
    $toUser['address'] = $result['address'].'\n'.$result['postalCode'].' '.$result['city'].'\n'.$result['country'];
    $statement = null;

    // We find the information about the toIBAN
    $sqlRequest = "
      SELECT
      `accounts`.`sepaN` as iban

      FROM `accounts`

      LEFT JOIN `clientsAccounts` ON `clientsAccounts`.`accountID` = `accounts`.`id`
      LEFT JOIN `accountTypes` ON `accounts`.`typeID` = `accountTypes`.`id`

      WHERE
      `clientsAccounts`.`clientID` = :idUser
      AND `accountTypes`.`name` = 'Current'";

    $statement = $db->prepare($sqlRequest);
    $statement->bindValue(':idUser', $toUser['ID'], PDO::PARAM_INT);
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC); // we don't need a loop because there is only one result
    $toUser['iban'] = $result['iban'];
    $statement = null;

    try  {
        //on launch a transation
        $db->beginTransaction();

        $db->query("UPDATE `accounts` SET `balance` = (`balance` - ".$amount.") WHERE sepaN LIKE '".$fromUser['iban']."'");
        $db->query("UPDATE `accounts` SET `balance` = (`balance` + ".$amount.") WHERE sepaN LIKE '".$toUser['iban']."'");
        $db->query("
          INSERT INTO transactions
          (sendingAccount,receivingAccount,senderInfo,receiverInfo,amount,communication)
          VALUES
          ('".$fromUser['iban']."','".$toUser['iban']."','".$fromUser['name']."\n".$fromUser['address']."','".$toUser['name']."\n".$toUser['address']."',".$amount.",'".$communication."')
        ");

        // if all is ok, we validate the transaction
        $db->commit();

        $json['request']['status'] = 'success';
        $json['request']['message'] = 'The transfert is done. '.$fromUser['name'].' transferred '.$amount.',00 euros to '.$toUser['name'].'.';

    } catch(Exception $e) { // in case of errors
        //we cancel the transaction
        $db->rollback();
        $db = null;
        $statement = null;
        $json['request']['status'] = 'error';
        $json['request']['message'] = '/!\ Error '.$e->getCode().': '.$e->getMessage();
        header('Content-type: application/json');
        echo json_encode($json);
        die();
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
