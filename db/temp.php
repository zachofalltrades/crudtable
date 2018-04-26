<?php
	if ($request->method === 'POST' || count($_POST)>0) {
		if (isset($request->parameters['delete'])) {
			debug("DELETE VIA POST");
			$id = $request->parameters['delete'];
			delete($id);
		} else if (isset($request->parameters['update'])) {
			debug("UPDATE VIA POST");
			$id = $request->parameters['update'];
			$name = $request->parameters['name'];
			$genus = $request->parameters['genus'];
			$species = $request->parameters['species'];
			//create an anonymous (classless) object... 
			$obj = (object) array('id' => $id, 'name' => $name, 'genus' => $genus, 'species' => $species);
			update($obj);
		} else {
			$name = $request->parameters['name'];
			$genus = $request->parameters['genus'];
			$species = $request->parameters['species'];
			//create an anonymous (classless) object... 
			$obj = (object) array('name' => $name, 'genus' => $genus, 'species' => $species);
			create($obj);
		}
	}
?>
