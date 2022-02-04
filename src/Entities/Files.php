<?php namespace Entities;

class Files {
	private int $id = 0;
	public string $file_name;
	public string $footer;
	public function __constructor(int $id, string $file_name, string $footer) {
		$this->id = $id;
		$this->file_name = $file_name;
		$this->footer = $footer;
	}
}