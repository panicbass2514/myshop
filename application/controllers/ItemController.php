<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class ItemController extends CI_Controller {

	// function __construct() {
	// 	parent::__construct();
	// }

	// public function index() {
	// 	$query = $this->db->get('shop_items');
	// 	$data['items'] = $query->result();

	// 	// $this->load->view('ItemView', $data);
	// }

	// public function add_item_view() {
	// 	$this->load->helper('form');
	// 	$this->load->view('ItemView');
	// }

	// public function add_item() {
	// 	$this->load->model('ItemModel');

	// 	$data = array(
	// 		'item_unit' => '',
	// 		'item_model' => '',
	// 		'item_condition' => '',
	// 		'item_p_price' => '',
	// 		'item_s_price' => '',
	// 		'item_sld_price' => '',
	// 		'item_revenue' => '',
	// 		'item_a_date' => '',
	// 		'item_s_date' => '',
	// 		'item_b_contact' => ''
	// 		);

	// 	$db = $this->ItemModel->insert($data);

	// 	$query = $this->db->get('shop_items');
	// 	$data['items'] = $query->result();
	// 	$this->load->view('ItemView', $data);
	// }

	// public function update_item_view() {
	// 	$this->load->helper('form');
	// 	$item_no = $this->uri->segment('3');
	// 	$query = $this->db->get_where('shop_items', array('item_no' => $item_no));
	// 	$data['items'] = $query->result();
	// 	$data['old_item_no'] = $item_no;
	// 	$this->load->view('ItemEdit', $data);
	// }

	// public function update_item() {
	// 	$this->load->model('ItemModel');

	// 	$data = array(
	// 		'item_unit' => $this->input->post('item_unit'),
	// 		'item_model' => $this->input->post('item_model'),
	// 		'item_condition' => $this->input->post('item_condition'),
	// 		'item_p_price' => $this->input->post('item_p_price'),
	// 		'item_s_price' => $this->input->post('item_s_price'),
	// 		'item_sld_price' => $this->input->post('item_sld_price'),
	// 		'item_revenue' => $this->input-post('item_revenue'),
	// 		'item_a_date' => $this->input->post('item_a_date'),
	// 		'item_s_date' => $this->input->post('item_s_date'),
	// 		'item_b_contact' => $this->input->post('item_b_contact')
	// 		);

	// 	$old_item_no = $this->input->post('old_item_no');
	// 	$this->ItemModel->update($data, $old_item_no);

	// 	$query = $this->db->get('shop_items');
	// 	$data['items'] = $query->result();
	// 	$this->load->view('ItemView', $data);
	// }

	// public function delete_item() {
	// 	$this->load->model('ItemModel');
	// 	$item_no = $this->uri->segment('3');
	// 	$this->ItemModel->delete($item_no);

	// 	$query->$this->db->get('shop_items');
	// 	$data['items'] = $query->result();
	// 	$this->load->view('ItemView', $data);
	// }

	public function __construct() {
		parent::__construct();
		$this->load->model('itemModel', 'item');
	}

	public function index() {
		$this->load->view('item_view');
	}

	public function item_list() {
		$list = $this->item->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach($list as $item) {
			$no++;
			$row = array();
			$row[] = $item->item_unit;
			$row[] = $item->item_model;
			$row[] = $item->item_condition;
			$row[] = $item->item_p_price;
			$row[] = $item->item_s_price;
			$row[] = $item->item_sld_price;
			$row[] = $item->item_revenue;
			$row[] = $item->item_a_date;
			$row[] = $item->item_s_date;
			$row[] = $item->item_b_contact;

			// Add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_item('."'".$item->item_no."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				      <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete" onclick="delete_item('."'".$item->item_no."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

			$data[] = $row;	
		}

		$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->item->count_all(),
				"recordsFiltered" => $this->item->count_filtered(),
				"data" => $data,
			);
		// Outeput to Json format
		echo json_encode($output);
	}

	public function item_edit($id) {
		$data = $this->item->get_by_id($id);
		$data->item_a_date = ($data->item_a_date == '0000-00-00') ? '' : $data->item_a_date; // if 0000-00-00 set to empty
		$data->item_s_date = ($data->item_s_date == '0000-00-00') ? '' : $data->item_s_date; // if 0000-00-00 set to empty

		echo json_encode($data);
	}

	public function item_add() {

		$this->_validate();
		$data = array(
				'item_unit' => $this->input->post('item_unit'),
				'item_model' => $this->input->post('item_model'),
				'item_condition' => $this->input->post('item_condition'),
				'item_p_price' => $this->input->post('item_p_price'),
				'item_s_price' => $this->input->post('item_s_price'),
				'item_sld_price' => $this->input->post('item_sld_price'),
				'item_revenue' => $this->input->post('item_revenue'),
				'item_a_date' => $this->input->post('item_a_date'),
				'item_s_date' => $this->input->post('item_s_date'),
				'item_b_contact' => $this->input->post('item_b_contact'),
			);
	
		$insert = $this->item->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function item_update() {
		$this->_validate();
		$data = array(
				'item_unit' => $this->input->post('item_unit'),
				'item_model' => $this->input->post('item_model'),
				'item_condition' => $this->input->post('item_condition'),
				'item_p_price' => $this->input->post('item_p_price'),
				'item_s_price' => $this->input->post('item_s_price'),
				'item_sld_price' => $this->input->post('item_sld_price'),
				'item_revenue' => $this->input->post('item_revenue'),
				'item_a_date' => $this->input->post('item_a_date'),
				'item_s_date' => $this->input->post('item_s_date'),
				'item_b_contact' => $this->input->post('item_b_contact'),
			);
		$this->item->update(array('item_no' => $this->input->post('item_no')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function item_delete($id) {
		$this->item->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	} 

	private function _validate() {
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('item_unit') == '') {
			$data['inputerror'][] = 'item_unit';
			$data['error_string'][] = 'Item Unit is Required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}
}
/*TODO*/
//http://localhost/mycodeigniter/index.php/itemController/item_list