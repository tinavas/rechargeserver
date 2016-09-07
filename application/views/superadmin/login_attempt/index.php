<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Login attempts
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-offset-1 col-sm-10">
                        <table class="table table-responsive">
                            <thead>
                                <tr class="table_heading_title">
                                    <th>
                                        <label for=""  class="control-label requiredField">
                                            Id
                                        </label>
                                    </th>
                                    <th>
                                        <label for=""  class="control-label requiredField">
                                            IP address
                                        </label> 
                                    </th>
                                    <th>
                                        <label for=""  class="control-label requiredField">
                                            Login
                                        </label>
                                    </th>
                                    <th>
                                        <label for=""  class="control-label requiredField">
                                            Time
                                        </label>
                                    </th>
                                    <th>
                                        <label for=""  class="control-label requiredField">
                                            Delete
                                        </label>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($login_attempt_list as $login_attempt) { ?>
                                    <tr class="table_content">
                                        <td><?php echo $login_attempt['id'] ?></td>
                                        <td><?php echo $login_attempt['ip_address'] ?></td>
                                        <td><?php echo $login_attempt['login'] ?></td>
                                        <td><?php echo $login_attempt['time'] ?></td>
                                        <td><a class="cursor_pointer" onclick="open_modal_delete_confirm(<?php echo $login_attempt['id'] ?>)">Delete</a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$this->load->view('superadmin/login_attempt/modal_delete_login_attempt_confirm');
