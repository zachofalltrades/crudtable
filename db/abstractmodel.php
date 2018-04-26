<?php
abstract class AbstractModel 
{ 
	
	protected $db;
    
    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }

    public function  __destruct() {
        $this->db = null;
    }
    
	public function getCreateStatement (array $parms) 
	{
		die;
	}
	
	public function getReadStatement (array $parms) 
	{
		die;
	}
	
	public function getUpdateStatement (array $parms) 
	{
		die;
	}
	
	public function getDeleteStatement (array $parms) 
	{
		die;
	}

	protected function validateID ($id) {
		if (!is_numeric($id)) {
			exitWithMessage("received non-numeric id: '$id'", 400);
		}
	}
}
?>