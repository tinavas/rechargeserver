<script>
    function add_company_info(companyInfo) {
        if(typeof companyInfo.companyName == "undefined" || companyInfo.companyName == ""){
            $("#content").html("Please give a Company Name !");
            $('#common_modal').modal('show'); 
            return;
        };
        angular.element($("#submit_company_name")).scope().addCompanyInfo(function (data) {
           $("#content").html(data.message);
           $('#common_modal').modal('show');
        });
    }
</script>
<div class="panel panel-default">
    <div id="company_info_show">
        <div class="panel-heading">Configure Your Company Name & Logo</div>
        <div class="panel-body">
            <div class="row form-group" ng-controller="CompanyInfoConfigurationController">
                <div class="col-md-3">
                    <div class="panel-heading table_row_style">Company Name </div>
                </div>
                <div class="col-md-8">
                    <input type="text" value="" class="form-control" placeholder=""  id="" ng-model="companyInfo.companyName">
                </div>
                <div class="col-md-1">
                    <input id="submit_company_name" name="submit_company_name" type="button" class="btn btn-sm company_name_save_button" onclick="add_company_info(angular.element(this).scope().companyInfo)" value="Save">
                </div>
            </div>
            <div class="row form-group" ng-controller="ImageCopperController">
                <div class="col-md-3">
                    <div class="panel-heading table_row_style">Logo </div>
                </div>
                <div class="col-md-9">
                    <div class="image_crop_section_background"   ng-clock >
                        <div ng-show="imageCropStep == 1" class="img-circle">
                            <input class="image_crop_browse_input" type="file" name="fileInput" id="fileInput" onchange="angular.element(this).scope().fileChanged(event)" />
                            <input type="hidden" value="<?php echo COMPANY_LOGO_NAME ?>.png" class="form-control" ng-model="companyInfo.logo">
                        </div>	
                        <div ng-show="imageCropStep == 2" class="image_crop_step_2">
                            <image-crop			 
                                data-height="44"
                                data-width="333"
                                data-shape="square"
                                data-step="imageCropStep"
                                src="imgSrc"
                                data-result="result"
                                data-result-blob="resultBlob"
                                crop="initCrop"
                                padding="0"
                                max-size="1012"
                                imagepath = "<?php echo base_url(); ?>superadmin/configuration/add_company_logo/"
                                ></image-crop>		   
                        </div>
                        <div ng-show="imageCropStep == 2">
                            <button class="btn btn-xs image_crop_button" ng-click="initCrop = true">Crop</button>		
                            <button class="btn btn-xs image_crop_cancel_button" ng-click="clear()">Cancel</button>
                        </div>		  
                        <div  ng-show="imageCropStep == 3" class="image_crop_step_3">
                            <img class="img-responsive" ng-src="{{result}}"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


