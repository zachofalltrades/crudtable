<?php
class AnimalModel extends AbstractModel
{ 
	private static $tableName = 'animals';
	
	public function getCreateStatement (array $parms) 
	{
		$statement = $this->db->prepare("INSERT INTO `".self::$tableName."` (name, genus, species) VALUES (:name, :genus, :species)");
		$statement->bindParam(':name', $parms['name'], PDO::PARAM_STR);
		$statement->bindParam(':genus', $parms['genus'], PDO::PARAM_STR);
		$statement->bindParam(':species', $parms['species'], PDO::PARAM_STR);
		return $statement;
	}
	
	public function getReadStatement (array $parms) 
	{
		if (isset($parms['genus'])) {
			$statement = $this->db->prepare("SELECT id, genus, name, species FROM `".self::$tableName."` WHERE genus like :genus");
			$statement->bindParam(':genus', $genus, PDO::PARAM_STR);
		} else {
			$statement = $this->db->prepare("SELECT id, genus, name, species FROM `".self::$tableName."`");
		}
		return $statement;
	}
	
	public function getUpdateStatement (array $parms) 
	{
		$this->validateID($parms['id']);
		$statement = $this->db->prepare("UPDATE `".self::$tableName."` SET name=:name, genus=:genus, species=:species WHERE id=:id");
		$statement->bindParam(':id', $parms['id'], PDO::PARAM_INT);
		$statement->bindParam(':name', $parms['name'], PDO::PARAM_STR);
		$statement->bindParam(':genus', $parms['genus'], PDO::PARAM_STR);
		$statement->bindParam(':species', $parms['species'], PDO::PARAM_STR);
		return $statement;
	}
	
	public function getDeleteStatement (array $parms) 
	{
		$this->validateID($parms['id']);
		$statement = $this->db->prepare("DELETE FROM `".self::$tableName."` WHERE id=:id");
		$statement->bindParam(':id', $parms['id'], PDO::PARAM_INT);
		return $statement;
	}
	
}

?>