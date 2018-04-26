<?php
class CrudHandler 
{

    private $db;
    
    public function __construct(PDO $pdo) {
        $this->db = $pdo;
    }
    
    public function  __destruct() {
        $this->db = null;
    }
    
    public function handleCreate($statement) {
    	$success = $statement->execute();
    	if ($success === true) {
	        $lastID = $this->db->lastInsertId();
    		header("Location: $lastID");
    		exitWithHttpCode(201);//Created
    	} else {
    		debug($statement->errorInfo());
    		exitWithHttpCode(422);//Unprocessable Entity
    	}
    }
    
    
    public function handleRead($statement) {
    	$success = $statement->execute();
    	if ($success === true) {
    		$resultSet = $statement->fetchALL();
    		$rows=$this->simplifyRows($resultSet);
    		//send output to browser
    		header('Cache-Control: no-cache, must-revalidate');
    		header('Content-type: application/json');
    		print json_encode($rows);
    		exitWithHttpCode(200);
    	} else {
    		exitWithHttpCode(422);//Unprocessable Entity
    	}
    }
    
    public function handleUpdate($statement) {
    	$success = $statement->execute();
    	if ($success === true) {
    		if ($statement->rowCount() === 1) {
    			exitWithHttpCode(204);//No Content
    		} else {
    			//rowCount will be zero in two cases: 
    			// 1) no item matches WHERE clause
    			// 2) data was not actually changed
    			debug($statement);
    			debug($statement->errorInfo());
    			exitWithHttpCode(404);//Not Found
    		}
    	} else {
    		debug($statement->errorInfo());
    		exitWithHttpCode(422);//Unprocessable Entity
    	}
    }
    
    public function handleDelete($statement) {
    	$success = $statement->execute();
    	if ($success === true) {
    		if ($statement->rowCount() === 1) {
    			exitWithHttpCode(204);//No Content
    		} else {
    			exitWithHttpCode(404);//Not Found
    		}
    	} else {
    		debug($statement->errorInfo());
    		exitWithHttpCode(422);//Unprocessable Entity
    	}
    }
    
    
    
    //remove pseudo-duplicate numeric columns from array of arrays
    private function simplifyRows($results) {
    	$clean = array();
    	foreach ($results as $index => $record) {
    		foreach ($record as $key => $value) {
    			if (is_int($key)) {
    				unset($record[$key]);
    			}
    		}
    		$clean[]=$record;
    	}
    	return $clean;
    }

}
?>