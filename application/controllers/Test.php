<?php 
	defined('BASEPATH') OR exit('No direct script access allowed');

	class Test extends CI_Controller {

		public function index() {
			echo "Hello Ging2x";
		}

		public function hello() {

			// echo "Hello" .$name. "Nako";
          $this->load->view('test');

		}
	}