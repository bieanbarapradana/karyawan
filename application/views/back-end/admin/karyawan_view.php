<!DOCTYPE html>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <div id="page-right-content">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
        <br/>
        <button class="btn btn-success" onclick="add_karyawan()"><i class="glyphicon glyphicon-plus"></i> Kontrak</button>
		<button class="btn btn-success" onclick="add_karyawan_tetap()"><i class="glyphicon glyphicon-plus"></i> Tetap</button>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
		<button class="btn btn-danger" onclick="bulk_delete()"><i class="glyphicon glyphicon-trash"></i> Bulk Delete</button>

        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check-all"></th>
					<th>First Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Jalan</th>
					<th>Kota</th>
					<th>Kode Pos</th>
                    <th>Date of Birth</th>
                    <th>Photo</th>
                    <th style="width:150px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
            <tr>
                <th></th>
				<th>First Name</th>
                <th>Last Name</th>
                <th>Gender</th>
                <th>Jalan</th>
				<th>Kota</th>
				<th>Kode Pos</th>
                <th>Date of Birth</th>
                <th>Photo</th>
                <th>Action</th>
            </tr>
            </tfoot>
        </table>
      </div>
      </div>
    </div>
    <div class="footer">
                          <div class="pull-right hidden-xs">
                              Project Completed <strong class="text-custom">39%</strong>.
                          </div>
                          <div>
                              <strong>Simple Admin</strong> - Copyright &copy; 2017
                          </div>
                      </div> <!-- end footer -->
</div>

<script src="<?php echo base_url('assets/back-end/js/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/back-end/plugins/jquery-ui/jquery-ui.min.js')?>"></script>
<script src="<?php echo base_url('assets/back-end/js/bootstrap.min.js')?>"></script>
<!-- Datatable js -->
<script src="<?php echo base_url('assets/back-end/plugins/datatables/pdfmake.min.js')?>"></script>
<script src="<?php echo base_url('assets/back-end/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/back-end/plugins/datatables/dataTables.buttons.min.js')?>"></script>
<script src="<?php echo base_url('assets/back-end/plugins/datatables/buttons.print.min.js')?>"></script>
<script src="<?php echo base_url('assets/back-end/plugins/datatables/buttons.html5.min.js')?>"></script>
<script src="<?php echo base_url('assets/back-end/plugins/datatables/dataTables.bootstrap.js')?>"></script>
<!-- Datepicker js -->
<script src="<?php echo base_url('assets/back-end/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<!-- init -->
<script src="<?php echo base_url('assets/back-end/pages/jquery.datatables.init.js')?>"></script>


<script type="text/javascript">

var save_method; //for save method string
var table;
var base_url = '<?php echo base_url();?>';

//1.# Membangun drop_down select option untuk ditampilkan dalam modal.
var helpers =
{
    buildDropdown: function(result, dropdown, emptyMessage)
    {
        // Remove current options
        dropdown.html('');
        // Add the empty option with the empty message
        dropdown.append('<option value="">' + emptyMessage + '</option>');
        // Check result isnt empty
        if(result != '')
        {
            // Loop through each of the results and append the option to the dropdown
            $.each(result, function(k, v) {
                dropdown.append('<option value="' + v.kode_divisi + '">' + v.nama_divisi + '</option>');
            });
        }
    }
}

//2.# Memberikan value pada drop_down yang diambil dari ajax_source 
function get_divisi(){

  $.ajax({
          type: "POST",
          url: "<?php echo site_url('karyawan/ajax_select')?>",
          success: function(data) //Event saat ajax_source berhasil diambil atau dijalankan
          {
              //Setelah sukses dijalankan maka lakukan pemanggilan function pembangun drop_down, dengan mem-parsing value menjadi format JSON untuk 
              helpers.buildDropdown(
                  jQuery.parseJSON(data),
                  $('#kode_divisi'),
                  'Select an option'
              );
          }
      });

}

//Inisialisasi pada saat halaman/dokumen HTML telah berhasil diload secara keseluruhan.
$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('karyawan/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
            {
                "targets": [ -0 ], //last column
                "orderable": false, //set not orderable
            },
            {
                "targets": [ -1 ], //2 last column (photo)
                "orderable": false, //set not orderable
            },
        ],

    });

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,
    });

    //set input/textarea/select event when change value, remove class error and remove text help block
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

	//check all
    $("#check-all").click(function () {
        $(".data-check").prop('checked', $(this).prop('checked'));
    });

});



function add_karyawan()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

	//Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('karyawan/ambil_kode')?>",
        type: "GET",
	success: function(data)
        {
		var data_new = data.split(" ");
		var id_karyawan = data_new[0];
		var no_kontrak = data_new[1];
            $('#id_karyawan').val(id_karyawan);
			$('#no_kontrak').val(no_kontrak);
			$('#modal_form').modal('show'); // show bootstrap modal
			$('.modal-title').text('Add Employee'); // Set Title to Bootstrap modal title

			$('#photo-preview').hide(); // hide photo preview modal

			$('#label-photo').text('Upload Photo'); // label photo upload





        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Terjadi Kesalahan!');
        }
    });


}

function add_karyawan_tetap()
{
  //Memanggil function get_divisi yang telah didefinisikan di awal, sebelum dokumen telah berhasil dimuat.
  get_divisi();
    save_method = 'add_karyawan_tetap';
    $('#form_tetap')[0].reset(); // reset form on modals
    $('.form-group-tetap').removeClass('has-error'); // clear error class
    $('.help-block-tetap').empty(); // clear error string

	//Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('karyawan/ambil_kode_tetap')?>",
        type: "GET",
	success: function(data)
        {
		var data_new = data.split(" ");
		var id_karyawan = data_new[0];
		var nip = data_new[1];


            // language=JQuery-CSS

		   $('#id_karyawan_tetap').val(id_karyawan);
			$('#nip').val(nip);

			$('#modal_form_tetap').modal('show'); // show bootstrap modal
			$('.modal-title').text('Add Employee'); // Set Title to Bootstrap modal title

			$('#photo-preview').hide(); // hide photo preview modal

			$('#label-photo').text('Upload Photo'); // label photo upload





        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Terjadi Kesalahan!');
        }
    });


}

function edit_karyawan(id_karyawan)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('karyawan/ajax_edit')?>/" + id_karyawan,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id_karyawan"]').val(data.id_karyawan);
            $('[name="firstName"]').val(data.firstName);
            $('[name="lastName"]').val(data.lastName);
            $('[name="gender"]').val(data.gender);
            $('[name="jalan"]').val(data.jalan);
			$('[name="kota"]').val(data.kota);
			$('[name="kode_pos"]').val(data.kode_pos);
            $('[name="dob"]').datepicker('update',data.dob);
			$('#modal_form_edit').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Employee'); // Set title to Bootstrap modal title


			$('#photo-preview').show(); // show photo preview modal

            if(data.photo)
            {
                $('#label-photo').text('Change Photo'); // label photo upload
                $('#photo-preview div').html('<img src="'+base_url+'upload/'+data.photo+'" class="img-responsive">'); // show photo
                $('#photo-preview div').append('<input type="checkbox" name="remove_photo" value="'+data.photo+'"/> Remove photo when saving'); // remove photo

            }
            else
            {
                $('#label-photo').text('Upload Photo'); // label photo upload
                $('#photo-preview div').text('(No photo)');
            }


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}



function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('karyawan/ajax_add')?>";
    }
	else {
        url = "<?php echo site_url('karyawan/ajax_update')?>";
    }

    // ajax adding data to database

    var formData = new FormData($('#form')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
    });
}

function save_tetap()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;
    // ajax adding data to database

    var formData = new FormData($('#form_tetap')[0]);
    $.ajax({
        url : "<?php echo site_url('karyawan/ajax_add_tetap')?>",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_tetap').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
    });
}

function edit_data()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;

    url = "<?php echo site_url('karyawan/ajax_update')?>";


    // ajax adding data to database

    var formData = new FormData($('#form_edit')[0]);
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_edit').modal('hide');
                reload_table();
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++)
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Terjadi Kesalahan');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        }
    });
}

function delete_karyawan(id_karyawan)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('karyawan/ajax_delete')?>/"+id_karyawan,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

function bulk_delete()
{
    var list_id_karyawan = [];
    $(".data-check:checked").each(function() {
            list_id_karyawan.push(this.value);
    });
    if(list_id_karyawan.length > 0)
    {
        if(confirm('Are you sure delete this '+list_id_karyawan.length+' data?'))
        {
            $.ajax({
                type: "POST",
                data: {id_karyawan:list_id_karyawan},
                url: "<?php echo site_url('karyawan/ajax_bulk_delete')?>",
                dataType: "JSON",
                success: function(data)
                {
                    if(data.status)
                    {
                        reload_table();
                    }
                    else
                    {
                        alert('Failed.');
                    }

                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error deleting data');
                }
            });
        }
    }
    else
    {
        alert('no data selected');
    }
}

</script>

<!-- Bootstrap modal Kontrak -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" style="width:980px; right:28%;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Person Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">

                    <div class="form-body">
                <div class="row">
					<div class="col-md-12">
					<div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-md-3">ID Karyawan</label>
                            <div class="col-md-9">
                                <input name="id_karyawan" placeholder="ID" id="id_karyawan" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">First Name</label>
                            <div class="col-md-9">
                                <input name="firstName" placeholder="First Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Last Name</label>
                            <div class="col-md-9">
                                <input name="lastName" placeholder="Last Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Gender</label>
                            <div class="col-md-9">
                                <select name="gender" class="form-control">
                                    <option value="">--Select Gender--</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jalan</label>
                            <div class="col-md-9">
                                <input name="jalan" placeholder="Street" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">Kota</label>
                            <div class="col-md-9">
                                <input name="kota" placeholder="City" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">Kode Pos</label>
                            <div class="col-md-9">
                                <input name="kode_pos" placeholder="Postal Code" class="form-control" type="number">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Date of Birth</label>
                            <div class="col-md-9">
                                <input name="dob" placeholder="yyyy-mm-dd" class="form-control datepicker" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group" id="photo-preview">
                            <label class="control-label col-md-3">Photo</label>
                            <div class="col-md-9">
                                (No photo)
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" id="label-photo">Upload Photo </label>
                            <div class="col-md-9">
                                <input name="photo" type="file">
                                <span class="help-block"></span>
                            </div>
                        </div>
						</div>
						<div class="col-md-6">

						<div class="form-group">
                            <label class="control-label col-md-3" id="label_kontrak">No Kontrak</label>
                            <div class="col-md-9">
                                <input name="no_kontrak" placeholder="No Kontrak" id="no_kontrak" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3" id="label_honor">Honor</label>
                            <div class="col-md-9">
                                <input name="honor" placeholder="......" id="honor" class="form-control" type="number">
                                <span class="help-block"></span>
                            </div>
                        </div>

						</div>
						</div>
                    </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" id="btnCancel" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Bootstrap modal Tetap -->
<div class="modal fade" id="modal_form_tetap" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" style="width:980px; right:28%;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Person Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_tetap" class="form-horizontal">

                    <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-md-3">ID</label>
                            <div class="col-md-9">
                                <input name="id_karyawan_tetap" placeholder="ID" id="id_karyawan_tetap" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">First Name</label>
                            <div class="col-md-9">
                                <input name="firstName" placeholder="First Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Last Name</label>
                            <div class="col-md-9">
                                <input name="lastName" placeholder="Last Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Gender</label>
                            <div class="col-md-9">
                                <select name="gender" class="form-control">
                                    <option value="">--Select Gender--</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jalan</label>
                            <div class="col-md-9">
                                <input name="jalan" placeholder="Street" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kota</label>
                            <div class="col-md-9">
                                <input name="kota" placeholder="City" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Kode Pos</label>
                            <div class="col-md-9">
                                <input name="kode_pos" placeholder="Postal Code" class="form-control" type="number">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Date of Birth</label>
                            <div class="col-md-9">
                                <input name="dob" placeholder="yyyy-mm-dd" class="form-control datepicker" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group" id="photo-preview">
                            <label class="control-label col-md-3">Photo</label>
                            <div class="col-md-9">
                                (No photo)
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" id="label-photo">Upload Photo </label>
                            <div class="col-md-9">
                                <input name="photo" type="file">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-6">

                        <div class="form-group">
                            <label class="control-label col-md-3" id="label_nip">NIP</label>
                            <div class="col-md-9">
                                <input name="nip" placeholder="NIP" id="nip" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Divisi</label>
                            <div class="col-md-9">
                                <select name="kode_divisi" id="kode_divisi"  class="form-control">

                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                                        <label class="control-label col-md-3">Gaji</label>
                                        <div class="col-md-9">
                                            <input name="gaji" placeholder="EX:10000" class="form-control" type="number">
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                        </div>
                        </div>   
                </div>
					
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save_tetap()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal Tetap -->

<!-- Bootstrap modal Edit Data -->
<div class="modal fade" id="modal_form_edit" role="dialog">
    <div class="modal-dialog" >
        <div class="modal-content" style="width:800px; right:28%;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Person Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_edit" class="form-horizontal">

                    <div class="form-body">



                        <div class="form-group">
                            <label class="control-label col-md-3">ID Karyawan</label>
                            <div class="col-md-9">
                                <input name="id_karyawan" placeholder="ID" id="id_karyawan" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">First Name</label>
                            <div class="col-md-9">
                                <input name="firstName" placeholder="First Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Last Name</label>
                            <div class="col-md-9">
                                <input name="lastName" placeholder="Last Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Gender</label>
                            <div class="col-md-9">
                                <select name="gender" class="form-control">
                                    <option value="">--Select Gender--</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Jalan</label>
                            <div class="col-md-9">
                                <input name="jalan" placeholder="Street" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">Kota</label>
                            <div class="col-md-9">
                                <input name="kota" placeholder="City" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
                            <label class="control-label col-md-3">Kode Pos</label>
                            <div class="col-md-9">
                                <input name="kode_pos" placeholder="Postal Code" class="form-control" type="number">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Date of Birth</label>
                            <div class="col-md-9">
                                <input name="dob" placeholder="yyyy-mm-dd" class="form-control datepicker" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group" id="photo-preview">
                            <label class="control-label col-md-3">Photo</label>
                            <div class="col-md-9">
                                (No photo)
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" id="label-photo">Upload Photo </label>
                            <div class="col-md-9">
                                <input name="photo" type="file">
                                <span class="help-block"></span>
                            </div>
                        </div>



                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="edit_data()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" id="btnCancel" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->


