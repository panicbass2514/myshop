<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE-edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>My Shop</title>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/dataTables.bootstrap.css') ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap-datepicker3.min.css') ?>">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->
  </head>
  <body>
  	<div class="container">
  		<h1 style="font-size:20pt">My Shop</h1>
  		<h3>Item Data</h3>
  		<br>
  		<button class="btn btn-success" onclick="add_item()"><i class="glyphicon glyphicon-plus"></i>Add Item</button>
  		<button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i>Reload</button>
  		<br>
  		<br>
  		<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
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
  			<thead>
  				<tr>
  					<th>Unit</th>
  					<th>Model</th>
  					<th>Condition</th>
  					<th>Purchase Price</th>
  					<th>Selling Price</th>
  					<th>Sold Price</th>
  					<th>Revenue</th>
  					<th>Aquired Date</th>
  					<th>Sold Date</th>
  					<th>Buyer Contact</th>
  					<th style="width:125px;">Action</th>
  				</tr>
  			</thead>
  			<tbody>
  			</tbody>
  		</table>
  	</div>
  	<script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js')?>"></script>
  	<script src="<?php echo base_url('assets/js/bootstrap.min.js')?>"></script>
  	<script src="<?php echo base_url('assets/js/jquery.dataTables.min.js')?>"></script>
  	<script src="<?php echo base_url('assets/js/dataTables.bootstrap.js')?>"></script>
  	<script src="<?php echo base_url('assets/js/bootstrap-datepicker.min.js')?>"></script>

  	<script type="text/javascript">
  		var save_method; //for save method string
  		var table;

  		$(document).ready(function() {
  			// datatables
  			table = $('#table').Datatable({
  				"processing": true, //Feature control the processing indicator.
  				"serverSide": true, //Feature control DataTables' server-side processing mode.
  				"order": [], //Initial no order

  				// Load data for the tables's content from an Ajax source
  				"ajax" : {
  					"url": "<?php echo site_url('itemController/item_list') ?>",
  					"type": "POST"
  				},

  				// Set column definition initialisation properties
  				"columnDefs": [
  				{
  					"targets": [-1], //last column
  					"orderable": false, //set not orderable
  				}		
  				],
  			});

  			// datepicker
  			$('.datepicker').datepicker({
  				autoclose: true,
  				format: "yyyy-mm-dd",
  				todayHighlight: true,
  				orientation: "top auto",
  				todayBtn: true,
  				todayHighlight: true,
  			});

  			// Set input/textarea/select event when change value, remove class error and remove text help
  			$("input").change(function() {
  				$(this).parent().parent().removeClass('has-error');
  				$(this).next().empty();
  			});
  			$("textarea").change(function() {
  				$(this).parent().parent().removeClass('has-error');
  				$(this).next().empty();
  			});
  			$("select").change(function() {
  				$(this).parent().parent().removeClass('has-error');
  			});
  		});

	function add_item() {
		save_method = 'add';
		$('#form')[0].reset();
		$('.form-group').removeClass('has-error');
		$('.help-block').empty(); // clear error string
		$('#modal-form').modal('show'); // show bootstrap modal
		$('.modal-title').text('Add Item'); // Set Title to Bootstrap modal title
	}

	function edit_item() {
		save_method = 'update';
		$('#form')[0].reset(); // reset form on mdals
		$('.form-group').removeClass('has-error'); // clear error class
		$('.help-block').empty(); // clear error string

		// Ajax Load data from ajax
		$.ajax({
			url: "<?php echo site_url('itemClass/item_edit')?>/" + id,
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('[name="item_no"').val(data.item_no);
				$('[name="item_unit"').val(data.item_unit);
				$('[name="item_model"').val(data.item_model);
				$('[name="item_condition"').val(data.item_condition);
				$('[name="item_p_price"').val(data.item_p_price);
				$('[name="item_s_price"').val(data.item_s_price);
				$('[name="item_sld_price"').val(data.item_sld_price);
				$('[name="item_revenue"').val(data.item_revenue);
				$('[name="item_s_date"').datepicker('update', data.item_s_date);
				$('[name="item_a_date"').datepicker('update', data.item_a_date);
				$('[name="item_b_contact"').val(data.item_b_contact);
				$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
				$('.modal-title').text('Edit Item'); // Set title to Bootstrap modal title
			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}

	function reload_table() {
		table.ajax.reload(null, false); //reload datatale ajax
	}  		

	function save() {
		$('#btnSave').text('saving...'); //change button text
		$('#btnSave').attr('disabled', true); //set button disable
		var url;

		if (save_method == 'add') {
			url = "<?php echo site_url('itemController/item_add') ?>";
		} else {
			url = "<?php echo site_url('itemController/item_update') ?>";
		}

		// Ajax adding data to database
		$.ajax({
			
		});
	}
  	</script>
  </body>
  </html>

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