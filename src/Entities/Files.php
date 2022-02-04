<?php namespace Entities;

/* File entity, this entity helps with the file handling
 * of the site and adds methods to save and delete the files
 * TODO:
 * - Implement the save method
 * - Implement the delete method
 * */
class File {
	private int $id = 0;
	public string $file_name;
	public string $footer;

	function __constructor(int $id, string $file_name, string $footer) {
		$this->id = $id;
		$this->file_name = $file_name;
		$this->footer = $footer;
	}

	function save() {}
	function delete() {}
}