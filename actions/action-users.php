<?php

/**
* Performing functions on the database
*/
class users
{

  /**
  * Create user
  */
  public function CREATE($name, $unit, $user, $pass, $level)
  {

    try
    {
      require 'connect.php';

      $new_pass = md5($pass);
      $data = date('Y-m-d');

      $create = $db->prepare('INSERT INTO users (data, name, unit, user, pass, level) VALUES(:data, :name, :unit, :user, :pass, :level)');
      $create->bindParam(':data', $data);
      $create->bindParam(':name', $name);
      $create->bindParam(':unit', $unit);
      $create->bindParam(':user', $user);
      $create->bindParam(':pass', $new_pass);
      $create->bindParam(':level', $level);

      if($create->execute()) :

        return 'SUCSSES';

      else :

        return 'ERROR';

      endif;

    }
    catch( PDOException $e )
    {

      return 'ERROR' . $e->getMessage();

    }
  }

  /**
  * Select users
  */
  public function SELECT()
  {

    try
    {
      require 'connect.php';

      $select = $db->prepare("SELECT id, data, name, unit, user, level FROM users ");
      if($select->execute()) :

        return json_encode($select->fetchAll(PDO::FETCH_ASSOC));

      else :

        return 'ERROR';

      endif;

    }
    catch( PDOException $e )
    {

      return 'ERROR' . $e->getMessage();

    }
  }

  /**
  * Update user
  */
  public function UPDATE($id_user, $name, $unit, $user, $pass)
  {

    try
    {

      require 'connect.php';

      $update = $db->prepare("UPDATE users SET name = :name, unit = :unit, user = :user, pass = :pass WHERE id = :id");
      $update->bindParam(':name', $name);
      $update->bindParam(':unit', $unit);
      $update->bindParam(':user', $user);
      $update->bindParam(':pass', $pass);
      $update->bindParam(':id', $id_user);

      if($update->execute()) :

        return 'SUCSSES';

      endif;

    }
    catch( PDOException $e )
    {

      return 'ERROR' . $e->getMessage();

    }
  }

  /**
  * Delete user
  */
  public function DELETE($id_user)
  {

    try
    {

      require 'connect.php';

      $delete = $db->prepare("DELETE FROM users WHERE id = :id");
      $delete->bindParam(':id', $id_user);

      if($delete->execute()) :

        return 'SUCSSES';

      endif;

    }
    catch( PDOException $e )
    {

      return 'ERROR' . $e->getMessage();

    }
  }
}
