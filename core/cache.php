<?php 

class CacheManager {
	private SQLite3 $cacheDB;

	public function __construct(string $fileName) {
		$this->cacheDB = new SQLite3($fileName);
	}

	public function storage(string $table) {
		
	}
}
