<?php

abstract class Service {
	private function __construct(string $id, Core $app) {
		$app->addService($id, $this);
	}
}


class Core {
	private array $services = [];

	public function getService(string $id): ?Service {
		return $this->servies[$id] ?? null;
	}

	public function addService(string $id, Service $service): bool {
		if(isset($this->services[$id])) {
			error_log("Service $id exists!");
			return false;
		}
		$this->services[$id] = $service;
		return true;
	}
}

class Template {
	private string $file_name;
	private array $__template_data = [];
	function __construct(string $file, array $data) {
		$this->__template_data = $data;
		$this->file_name = $file;
	}

	function render() {
		$template_path = "../src/templates/{$this->file_name}";
		if(!is_file($template_path))
			$template_path .= ".php";
		if(!is_file($template_path))
			throw "Error: The template does not exists";
		include $template_path;
	}

	function __get(string $key) {
		return $this->__template_data[$key] ?? null;
	}
}
