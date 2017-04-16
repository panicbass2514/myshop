<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemModel extends CI_Model {

	// function __construct() {
	// 	parent::_construct();
	// }

	// public function insert($data) {
	// 	if ($this->db->insert("items", $data)) {
	// 		return true;
	// 	}
	// }

	// public function delete($item_no) {
	// 	if ($this->db->delete("items", "item_no =".$item_no)) {
	// 		return true;
	// 	}
	// }

	// public function update($data, $old_item_no) {
	// 	$this->db->set($data);
	// 	$this->db->where("item_no", $old_item_no);
	// 	$this->db->update($"item_no", $data);
	// }

	var $table = 'shop_items';
	var $column_order = array('item_unit', 'item_model', 'item_condition', 'item_p_price', 'item_s_price', 'item_sld_price', 'item_revenue', 'item_a_date', 'item_s_date', 'item_b_contact', null);
	var $column_search = array('item_unit', 'item_condition');
	var $order = array('item_no' => 'desc' );

	public function __construct() {
		parent::__construct();
	}

	private function _get_datatables_query() { 
		$this->db->from($this->table);

		$i = 0;

		// Loop Column
		foreach ($this->column_search as $item) {
			// If datatable send POST for search
			if ($_POST['search']['value']) {
				// Open bracket. query Where with OR clause
				$this->db->group_start();
				$this->db->like($item, $_POST['search']['value']);
			} else {
				$this->db->or_like($item, $_POST['search']['value']);
			}
			// Last Loop
			if(count($this->column_search) - 1 == $i) {
				$this->db->group_end(); //close bracket
			}

			$i++;
		}
		// Order Processing
		if (isset($_POST['order'])) {
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables() {
		$this->_get_datatables_query();
		if ($_POST['length'] != -1) {
			$this->db->limit($_POST['length'], $_POST['start']);
			$query = $this->db->get();
			return $query->result();
		}
	}

	function count_filtered() {
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all() {
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id) {
		$this->db->from($this->table);
		$this->db->where('id', $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data) {
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function udpate($where, $data) {
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id) {
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}
}