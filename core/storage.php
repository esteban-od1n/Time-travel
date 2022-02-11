<?php 

interface StorageDatabase {
	function connect(array $args): object;
	function close(): bool;
	function driver(): StorageDriver;
}

interface StorageDriver {
	function prepare(string $query): bool;
	function execute(): bool;
	function setValues(...$values): bool;
	function results(): array;
	function commit(): bool;
	function rollback(): bool;
	function close(): bool;
}

interface StorageQuery {
	function condition(string $column, mixed $value, string $operator = '=');
}

interface Storage {
	function get(int $id): object;
	function getAll(int ...$ids): array;
	function set(object $object): bool;
	function delete(int $id): bool;
	function query(): StorageQuery;
	function save(): bool;
}


/*

class Storage {
	private StorageDriver $driver;
	public string $objClass;

	public function __construct(StorageDriver $storageDriver, string $objectClass) {
		$this->driver = $storageDriver;
		$this->objClass = $objectClass;
	}

	public function get(int $id): object {
		$query = "SELECT * FROM \"{$this->objClass}\" WHERE id=$id";
		$this->driver->prepare($query);
		$this->driver->execute();
		$this->driver->results();
	}

	public function getMultiple(int ...$ids): bool {
		$ids_in = implode(",", $ids);
		$query = "SELECT * FROM \"{$this->objClass}\" WHERE id IN($ids_in)";
		return $this->driver->query($query);
	}

	public function delete(int $id): bool {
		$query = "DELETE FROM \"{$this->objClass}\ WHERE id=$id";
		return $this->driver->query($query);
	}

	public function deleteAll(int ...$ids): bool {
		$ids_in = implode(",", $ids);
		$query = "DELETE FROM \"{$this->objClass}\" WHERE id IN($ids_in)";
		return $this->driver->query($query);
	}

	public function set(object $entity): bool {
		if(!($entity instanceof $this->objClass))
			return false;
		$class_vars = get_class_vars($this->objClass);
		$query = "INSERT INTO \"{$this->objClass}\" SET ";
		foreach($class_vars as $v)
			$query .= "$v=?,";
		$query = substr($query, 0, strlen($query) - 1);
		$this->driver->prepare($query);
		$this->driver->setValues($entity);
		return $this->driver->execute();
	}

	public function save() {
		$this->driver->commit();
	}

	public function query() {}

	public function __destruct() {
		$this->driver->rollback();
		$this->driver->close();
	}
}

class SelectQuery {

}
*/