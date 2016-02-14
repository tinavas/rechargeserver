<div class="loader"></div>
<div class="ezttle"><span class="text">bKash History</span></div>
<div class="mypage">
    <!--<div class="top10">&nbsp;</div>-->
    <!--    <form accept-charset="utf-8" method="post" role="form" class="form-inline filter" action="">
            <input type="hidden" style="display:none;" value="" name="elctkn">
            <input type="hidden" value="" name="uri">
            <div class="form-group">
                <label for="row">Show</label><br>
                <select name="rows" class="form-control input-xs">
                    <option>10</option>
                    <option selected="">50</option>
                    <option>100</option>
                    <option>150</option>
                    <option>200</option>
                    <option>400</option>
                    <option>500</option>
                </select>
            </div>
            <div class="form-group">
                <label for="id">Req.ID</label><br>
                <select class="form-control input-xs" style="height:20px;float:left;padding:0px;" name="logic">
                    <option value="&gt;">&gt;</option>
                    <option value="=">=</option>
                    <option value="&lt;">&lt;</option>
                </select>
                <input type="text" style="width:50px;margin-left:5px;" value="" placeholder="" id="id" name="id" class="form-control input-xs">
            </div>
            <div class="form-group">
                <label for="number">Number</label><br>
                <input type="text" value="" size="18" placeholder="Number" id="number" name="number" class="form-control input-xs">
            </div>
            <div class="form-group">
                <label for="reseller">Reseller</label><br>
                <select style="width:120px;" id="reseller" name="reseller" class="form-control input-xs">
                    <option value="">--View All--</option>
                    <option value="271">My Account</option>
                    <optgroup label="Reseller III">
                        <option value="424">test</option></optgroup>
                    <optgroup label="Reseller II">
                        <option value="425">test2</option>
                    </optgroup>
                    <optgroup label="Reseller I">
                        <option value="426">test3</option>
                    </optgroup>
                </select>
            </div>
            <div class="form-group">
                <label for="service">Services</label><br>
                <select id="service" name="service" class="form-control input-xs">
                    <option value="all">--Any--</option>
                    <option value="8">Flexiload</option> 								
                    <option selected="" value="32">bKash</option> 								
                    <option value="64">DBBL</option> 								
                    <option value="128">M-Cash</option> 								
                    <option value="256">U-Cash</option> 								
                    <option value="512">Global Topup</option> 								
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status</label><br>
                <select id="status" name="status" class="form-control input-xs">
                    <option value="">--Any--</option>
                    <option value="0">Pending</option>
                    <option value="1">Processed</option>
                    <option value="2">Failed</option>
                    <option value="3">Canceled</option>
                    <option value="4">Completed</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date1">Date From</label><br>
                <input type="text" value="2015-12-19" size="18" placeholder="Date From" id="date1" name="from" class="form-control input-xs">
            </div>
            <div class="form-group">
                <label for="date2">Date To</label><br>
                <input type="text" value="2015-12-19" size="18" placeholder="Date To" id="date2" name="to" class="form-control input-xs">
            </div>
            <div class="form-group">
                <label>&nbsp;</label><br>
                <button class="btn btn-danger btn-xs" type="submit"><span class="glyphicon glyphicon-search"></span> Filter</button>
            </div>
        </form>			-->
    <!--<div class="top10">&nbsp;</div>-->
    <ul class="list-unstyled paymentHistorySearch">
        <li>Start Date</li>
        <li><input id="date5" type="text" size="18" placeholder="Start Date"  name="from" class="form-control input-xs customInputMargin"></li>
        <li>End Date</li>
        <li><input id="date6" type="text" size="18" placeholder="End Date"  name="from" class="form-control input-xs customInputMargin"></li>
        <li>Type</li>
        <li><select id="type" name="type" class="form-control input-xs">
                <option value="">--Any--</option>
                <option value="transfer">Payment</option>
                <option value="return">Return</option>
                <option value="canceled">Cancelled</option>
            </select>
        </li>
        <li><input id="submit" type="submit" size="18" value="Submit" class="btn btn-xs btn-default"></li>
    </ul>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th><a href="">ID</a></th>
                <th><a href="">Number</a></th>
                <th><a href="">Amount</a></th>
                <th><a href="">Status</a></th>
                <th><a href="">Trans.ID</a></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <?php
            $counter = 1;
            foreach ($transaction_list as $transaction_info) {
                ?>
                <tr>
                    <th><?php echo $counter++; ?></th>
                    <th><?php echo $transaction_info['cell_no'] ?></th>
                    <th><?php echo $transaction_info['amount'] ?></th>
                    <th><?php echo $transaction_info['status'] ?></th>
                    <th><?php echo $transaction_info['transaction_id'] ?></th>
                </tr>
            <?php } ?>
        </tfoot>
    </table>
    <div class="form-group">
        <div class="col-md-12 fleft">
            <div class="summery">
                <p>Summary</p>
                <table>
                    <tbody>
                        <tr><td>Total Payment :</td><td class="amt">0.00</td></tr>
                        <tr><td>Total Return :</td><td class="amt">0.00</td></tr>
                        <tr><td>Total Canceled :</td><td class="amt">0.00</td></tr>
                        <tr><td>Total :</td><td class="amt">0.00</td></tr>
                    </tbody>
                </table>
            </div>
        </div> 
    </div> 
    <ul class="pagination pull-right">
        <li>
            <a href="#" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
        <li><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li>
            <a href="#" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
    <!--    <div style="margin-top:5px;" class="row">
            <div class="col-md-6"><p class="pageinfo">Page 1 of 0 (Showing 1 to 0 of 0 records)</p></div> 
            <div class="col-md-6">
            </div>
        </div>-->
</div>