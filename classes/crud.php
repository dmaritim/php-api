<?php
include("database.php");

class Crud extends Database
{

  private $currentObj = null;

  public function __construct()
 {
	  parent::__construct();
 }
 

  public function create($this->currentObj->create())
{
  $insert = $this->conn->query($data) or die();

  if($insert)
  {
    return $insert;
  }
  else 
  {
    echo "Query failed...";
  }
}
 
 public function read($data)
{
  $view =  $stmt->execute();

  if ($view->num_rows > 0)
  {
    return $view;
  }
  else
  {
	 return $view;
  }
}

  public function update($data)
{
  $update = $this->conn->query($data) or die();

  if($update)
  {
   return $update;
  }
  else 
  {
    echo "Query failed...";
  }
}

 public function deletes($stmt)
{
  $delete = $this->conn->query($data) or die();

  if($delete)
  {
    return $delete;
  }
  else
  {
    echo "Query failed...";
  }
}
}
?>