<?php
require_once('init.php');
class wine {

	private $id;
	private $upc;
	private $type;
	private $name;
	private $cost;
	private $vintage;
	private $sell_price;
	private $inDB = FALSE;
	private $pdo;
	
	public function __construct($pdo) {
		$this->pdo = $pdo;
	}
	
	public function fetchWineByUPC($upc) {
		//Get wine from DB and set all vars
		$getWine = $this->pdo->prepare("SELECT * FROM wine WHERE upc = :upc");
		$getWine->bindValue(':upc', $upc);
		$getWine->execute();
		if($getWine->rowCount() > 0) {
			$row = $getWine->fetch(PDO::FETCH_ASSOC);
			$this->id = $row['id'];
			$this->upc = $row['upc'];
			$this->type = $row['type'];
			$this->name = $row['name'];
			$this->sell_price = $row['sell_price'];
			$this->cost = $row['cost'];
			$this->vintage = $row['vintage'];
			$this->inDB = TRUE;
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function fetchWineById($id) {
		//Get wine from DB and set all vars
		$getWine = $this->pdo->prepare("SELECT * FROM wine WHERE id = :id");
		$getWine->bindValue(':id', $id);
		$getWine->execute();
		if($getWine->rowCount() > 0) {
			$row = $getWine->fetch(PDO::FETCH_ASSOC);
			$this->id = $row['id'];
			$this->upc = $row['upc'];
			$this->name = $row['name'];
			$this->sell_price = $row['sell_price'];
			$this->vintage = $row['vintage'];
			$this->inDB = TRUE;
			return TRUE;
		} else {
			return FALSE;
		}
	}
	public function getID( ) {
		return $this->id;
	}
	public function getUPC( ){
		//Echo UPC
		return $this->upc;
	}
	public function getName( ){
		//Echo Name
		return $this->name;
	}
	public function getCost( ){
		//Echo Cost
		return $this->cost;
	}
	public function getSellPrice( ) {
		//Echo Selling Price
		return $this->sell_price;
	}
	public function getVintage( ) {
		return $this->vintage;
	}
	public function getType( ) {
		return $this->type;
	}
	public function setID($newID) {
		$this->id = $newID;
		$this->inDB = TRUE;
	}
	public function setUPC($upc) {
		//Set UPC
		$this->upc = $upc;
	}
	public function setName($name) {
		//Set Name
		$this->name = $name;
	}
	public function setCost($newCost) {
		//Set Cost
		$this->cost = $newCost;
	}
	public function setSellPrice($newSellPrice) {
		//Set Price
		$this->sell_price = $newSellPrice;
	}
	public function setVintage($newVintage) {
		$this->vintage = $newVintage;
	}
	public function setType($newType) {
		$this->type = $newType;
	}
	public function writeWineToTable() {
		//Add wine to wine table
		if(	!is_null($this->upc) || !is_null($this->name) || 
			!is_null($this->vintage) || !is_null($this->sell_price) ||
			!is_null($this->cost) || !is_null($this->type)) {
			if($this->inDB==TRUE) {
				if(!is_null($this->id)) {
					//Update wine record with new data
					$updateWine = $this->pdo->prepare("UPDATE wine SET upc=:upc, type=:type, name=:name, vintage=:vintage, sell_price=:sell_price, cost=:cost WHERE id=:id");
					$updateWine->bindValue(':upc',$this->upc);
					$updateWine->bindValue(':name',$this->name);
					$updateWine->bindValue(':vintage',$this->vintage);
					$updateWine->bindValue(':sell_price',$this->sell_price);
					$updateWine->bindValue(':cost',$this->cost);
					$updateWine->bindValue(':type',$this->type);
					$updateWine->bindValue(':id',$this->id);
					$updateWine->execute();
					return TRUE;
				} else {
					return 'Error: ID not set. Cannot update record';
				}
			} else {
				//Write new wine record
				//Are all vars set
				$createWine = $this->pdo->prepare("INSERT INTO wine (upc, name, vintage, sell_price, cost, type) VALUES (:upc, :name, :vintage, :sell_price, :cost, :type)");
				$createWine->bindValue(':upc', $this->upc);
				$createWine->bindValue(':name', $this->name);
				$createWine->bindValue(':vintage',$this->vintage);
				$createWine->bindValue(':sell_price',$this->sell_price);
				$createWine->bindValue(':cost',$this->cost);
				$createWine->bindValue(':type',$this->type);
				$createWine->execute();
				$this->inDB = TRUE;
				$this->id = $this->pdo->lastInsertId();
				return $this->pdo->lastInsertId();			
			}
		} else {
			return 'Error: Could not create/update record.  Please ensure that all parameters are set.';
		}
	}
	public function addInventory($args) {
		//Add inventory to wine inventory table
		$addInventory = $this->pdo->prepare("INSERT INTO inventory (wine_id,quantity,cost,notes,location) VALUES (:wine_id,:quantity,:cost,:notes,:location)");
		$addInventory->bindValue(':wine_id',$this->id);
		$addInventory->bindValue(':quantity',$args['quantity']);
		$addInventory->bindValue(':cost',$args['cost']);
		$addInventory->bindValue(':notes',$args['notes']);
		$addInventory->bindValue(':location',$args['location']);
		$addInventory->execute();
		return $this->pdo->lastInsertId();
	}
	public function removeInventory($args) {
		//Remove inventory from inventory table
		//Add inventory to wine inventory table
		$inv = array('location'=>$args['location']);
		$curInv = $this->getCurrentInventory($inv);
		if(($curInv) && ($curInv >= $args['quantity'])) {
			$addInventory = $this->pdo->prepare("INSERT INTO inventory (wine_id,quantity,sell_price,notes,location) VALUES (:wine_id,:quantity,:sell_price,:notes,:location)");
			$addInventory->bindValue(':wine_id',$this->id);
			$addInventory->bindValue(':quantity',(abs($args['quantity'])*-1));
			$addInventory->bindValue(':sell_price',$args['sell_price']);
			$addInventory->bindValue(':notes',$args['notes']);
			$addInventory->bindValue(':location',$args['location']);
			$addInventory->execute();
			return $this->pdo->lastInsertId();
		} else {
			return 'Error: Inventory levels insufficient for transaction.';
		}
		

	}
	
	public function getCurrentInventory( $args = null ) {
		
		//Get current total encompassing inventory
		if(isset($this->id)) {
			$query = "SELECT inventory.datetime, inventory.quantity FROM inventory WHERE wine_id = :id ";
			if(!is_null($args) && isset($args['date'])) {
				$query .= "AND inventory.datetime <= :date";
			}
			if(!is_null($args) && isset($args['location'])) {
				$query .= "AND inventory.location = :location";
			}
			$query .= " order by inventory.datetime";
			$res = $this->pdo->prepare($query);
			$res->bindValue(':id',$this->id);
	
			if(!is_null($args) && isset($args['date'])) {
				$res->bindValue(':date',date('Y-m-d 23:59:59', strtotime(str_replace('-', '/', $args['date']))));
			}
			if(!is_null($args) && isset($args['location'])) {
				$res->bindValue(':location',$args['location']);
			}
			$res->execute();
			
			$total = 0;
			while($row = $res->fetch(PDO::FETCH_ASSOC)) {
				$total = $total + $row['quantity'];
			}
			return $total;
			
		} else {
			return FALSE;
		}
		
	}
	
	public function getDetailInventory( $args = null ) {
		
		//Get current total encompassing inventory
		if(isset($this->id)) {
			$query = "SELECT inventory.datetime, inventory.quantity, locations.locname, inventory.location FROM inventory left join locations ON inventory.location = locations.id WHERE wine_id = :id ";
			if(!is_null($args) && isset($args['date'])) {
				$query .= "AND inventory.datetime <= :date";
			}
			$query .= " order by inventory.datetime";
			$res = $this->pdo->prepare($query);
			$res->bindValue(':id',$this->id);
	
			if(!is_null($args) && isset($args['date'])) {
				$res->bindValue(':date',date('Y-m-d 23:59:59', strtotime(str_replace('-', '/', $args['date']))));
			}
			$res->execute();
			
			$total = array();
			while($row = $res->fetch(PDO::FETCH_ASSOC)) {
				if(isset($total[$row['locname']])) {
					$total[$row['locname']] = $total[$row['locname']] + $row['quantity'];
				} else {
					$total[$row['locname']] = $row['quantity'];
				}
			}
			return $total;
			
		} else {
			return FALSE;
		}
		
	}
	
	public function getInventorySummary($fromDate, $toDate) {
		//Get running inventory from date to date for UPC. Return JSON including starting inventory, ending Inventory, and all transactions
		//check if UPC exists else die
		if(!isset($this->upc)) {
			return 'You must set a UPC';
		}
		//Build query and execute PDO query
		$query = 	"SELECT wine.upc, wine.name, inventory.cost, inventory.sell_price, inventory.quantity, inventory.datetime, locations.locname " .
					"FROM inventory left join wine on inventory.wine_id = wine.id LEFT JOIN locations ON inventory.location=locations.id ";
		$where =	"WHERE wine.upc = :upc";
		
		if(isset($fromDate) && $fromDate != null){
			$from = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $fromDate)));
			$where .= " AND inventory.datetime >= :from";
		}
		if(isset($toDate) && $toDate != null){
			$to = date('Y-m-d 23:59:59', strtotime(str_replace('-', '/', $toDate)));
			$where .= " AND inventory.datetime <= :to";
		}
		
		$query .= $where . ' ORDER BY inventory.datetime ASC';
		$data = $this->pdo->prepare($query);
		$data->bindValue(':upc',$this->upc);
		
		if(isset($fromDate) && $fromDate != null){
			$data->bindValue(':from', $from);
		}
		if(isset($toDate) && $toDate != null){
			$data->bindValue(':to',$to);
		}
		$data->execute();
		$results = $data->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode($results);
		return $json;
	
	}
	
	public function getInventoryRecord($id){ 
		if(is_null($id) || $id=='') {
		 return FALSE;
		}
		$query = 'select inventory.*, location.locname from inventory left join locations on inventory.location = locations.id where id=:id';
		$res = $this->pdo->prepare('select inventory.notes,inventory.datetime, inventory.cost, inventory.sell_price, inventory.quantity, locations.locname, wine.upc, wine.name from inventory left join wine on inventory.wine_id = wine.id left join locations on inventory.location = locations.id where inventory.id=:id');
		$res->bindValue(':id',$id);
		$res->execute();
		
		$row = $res->fetch(PDO::FETCH_ASSOC);
		$json = json_encode($row);
		return $json;
	}
	
	public function jsonEncode($location) {
		$args = array('location'=>$location);
		$arr = array(	"id" => $this->id,
						"upc" => $this->upc,
						"name" => $this->name,
						"vintage" => $this->vintage,
						"cost" => $this->cost,
						"sell_price" => $this->sell_price,
						"inDB" => $this->inDB,
						"inventory" => $this->getCurrentInventory($args)
					);
		$json = json_encode($arr);
		return $json;
	}
	public function fetchAllWines() {
		$query = "SELECT * FROM wine ORDER BY Type,Name";
		$res = $this->pdo->query($query);
		$results = $res->fetchAll(PDO::FETCH_ASSOC);
		$json = json_encode($results);
		return $json;
	}


}