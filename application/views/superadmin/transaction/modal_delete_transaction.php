<script type="text/javascript">
    $(function () {
        $("#button_delete_transaction").on("click", function () {
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: '<?php echo base_url(); ?>' + "superadmin/transaction/delete_transaction",
                data: {
                    transction_id: $("#input_trnasction_id").val()
                },
                success: function (data) {
                    if (data.status == 1) {
                        $("#modal_delete_transaction").modal('hide');
                        window.location.reload();
                    } else {
                        alert("Fail to Delete License key !!!");
                    }
                }
            });
        });
    });

    function open_modal_delete_transaction(transction_id) {
        $("#input_trnasction_id").val(transction_id);
        $("#modal_delete_transaction").modal('show');
    }
</script>
<div class="modal fade" id="modal_delete_transaction" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Transaction</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="row form-group">
                        <div class ="col-sm-2"></div>
                        <label class="col-sm-10 control-label">Are you sure to delete this transaction?</label>
                        <input id="input_trnasction_id" name="input_trnasction_id" value="" type="hidden" class="form-control"/>
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <div class ="col-md-6">

                </div>
                <div class ="col-md-3">
                    <button style="width:100%" id="button_delete_transaction" name="button_delete_transaction" value="" class="btn button-custom btn_custom_button">Delete</button>
                </div>
                <div class ="col-md-3">
                    <button style="width:100%" type="button" class="btn button-custom btn_custom_button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->