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
        <button class="btn btn-success" onclick="add_divisi()"><i class="glyphicon glyphicon-plus"></i> Divisi</button>

        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
		<button class="btn btn-danger" id="bulk" onclick="bulk_delete()"><i class="glyphicon glyphicon-trash"></i> Bulk Delete</button>

        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th><input type="checkbox" id="check-all"></th>
                    <th>Nama Divisi</th>
                    <th>Gaji Dasar</th>
                    <th style="width:150px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
            <tr>
                <th></th>
                <th>Nama Divisi</th>
                <th>Gaji Dasar</th>
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

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('divisi/ajax_list')?>",
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


function add_divisi()
{
  save_method = 'add';
  $('#form')[0].reset(); // reset form on modals
  $('.form-group').removeClass('has-error'); // clear error class
  $('.help-block').empty(); // clear error string
  $('#modal_form').modal('show'); // show bootstrap modal
  $('.modal-title').text('Add Divisi'); // Set Title to Bootstrap modal title
}

function edit_divisi(kode_divisi)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string


    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('divisi/ajax_edit')?>/" + kode_divisi,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="kode_divisi"]').val(data.kode_divisi);
            $('[name="nama_divisi"]').val(data.nama_divisi);
            $('[name="gaji_dasar"]').val(data.gaji_dasar);

			$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Divisi'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Terjadi Kesalahan');
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
        url = "<?php echo site_url('divisi/ajax_add')?>";
    }
	else {
        url = "<?php echo site_url('divisi/ajax_update')?>";
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



function delete_divisi(kode_divisi)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('divisi/ajax_delete')?>/"+kode_divisi,
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
    var list_id_divisi = [];
    $(".data-check:checked").each(function() {
            list_id_divisi.push(this.value);
    });
    if(list_id_divisi.length > 0)
    {

        if(confirm('Are you sure delete this '+list_id_divisi.length+' data?'))
        {
            $.ajax({
                type: "POST",
                data: {kode_divisi:list_id_divisi},
                url: "<?php echo site_url('divisi/ajax_bulk_delete')?>",
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


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Divisi Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="kode_divisi"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Nama Divisi</label>
                            <div class="col-md-9">
                                <input name="nama_divisi" placeholder="Departments Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Gaji Dasar</label>
                            <div class="col-md-9">
                                <input name="gaji_dasar" placeholder="Basic Salary" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
