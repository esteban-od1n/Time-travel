<?php 
interface StorageDriver {
	function query(string $query): ?array;
	// Prepared statements
	function prepare(string $query): bool;
	function execute(): bool;
	function results(string $class): array;
	function row(string $class): object;
	// Transaction
	function commit(): bool;
	function rollback(): bool;
	function close(): bool;
}

class Storage {
	private StorageDriver $driver;
	public string $objClass;

	public function __construct(StorageDriver $storageDriver, string $objectClass) {
		$this->driver = $storageDriver;
		$this->objClass = $objectClass;
	}

	public function load(int $id) {
		$query = "SELECT * FROM {$this->objClass} WHERE id=$id";
		return $this->driver->query($query)[0] ?? null;
	}

	public function loadMultiple(int ...$ids) {
		$ids_in = implode(",", $ids);
		$query = "SELECT * FROM {$this->objClass} WHERE id IN($ids_in)";
	}

	public function save() {
		$this->driver->commit();
	}

	public function __destruct() {
		$this->driver->rollback();
		$this->driver->close();
	}
}

class SelectQuery {

}