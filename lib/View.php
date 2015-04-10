<?php
class View {
    private $path;
    private $view_path;
    protected $data = array();

    public function __construct($view_path) {
        $path = APPLICATION_PATH . '/view/';
        $view_path = APPLICATION_PATH . '/view/' . $view_path . '.html';

		if (!is_file($view_path))
			throw new InvalidArgumentException("No view found " . $view_path);

		$this->path = $path;
        $this->view_path = $view_path;
    }

    public function render() {
        require ($this->path . 'header.html');
        require ($this->view_path);
        require ($this->path . 'footer.html');

    }

    public function setVariable($variable, $value) {
        $this->data[$variable] = $value;
    }
} 