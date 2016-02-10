<script>
    function create_service(serviceInfo) {

        if (typeof serviceInfo.title == "undefined" || serviceInfo.title.length == 0) {
            $("#content").html("Please give a Service Title !");
            $('#common_modal').modal('show');
            return;
        }
        angular.element($("#submit_create_service")).scope().createService(function (data) {

            $("#content").html(data.message);
            $('#common_modal').modal('show');
            $('#modal_ok_click_id').on("click", function () {
                window.location = '<?php echo base_url() ?>superadmin/service/show_services';
            });
        });

    }

</script>

<div class="panel panel-default" ng-controller="serviceController">
    <div class="panel-heading">Create Service</div>
    <div class="panel-body">
        <form>
            <div class="form-background top-bottom-padding">
                <div class="row">
                    <div class ="col-md-8 margin-top-bottom">

                        <div class=" row form-group">
                            <label for="service_title" class="col-md-6 control-label requiredField">
                                Title
                            </label>
                            <div class ="col-md-6">
                                <input type="text" value="" class="form-control" placeholder="service title"  ng-model="serviceInfo.title">
                            </div> 
                        </div>
                        <div class="row form-group">
                            <label for="submit_create_service" class="col-md-6 control-label requiredField">

                            </label>
                            <div class ="col-md-3 pull-right" style="padding-left: 35px">
                                <input id="submit_create_service" name="submit_create_service" class="btn btn_custom_button" type="submit" onclick="create_service(angular.element(this).scope().serviceInfo)" value="Add Service"/>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>