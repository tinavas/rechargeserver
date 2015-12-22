function showabout(version)
{
    alert('EzzeLoad Recharge Application.\nVersion: '+version+'\nDeveloped by: Ezze Technology\nSupport: support@ezzetech.com\nSkype ID: ezzetech\nWebsite: www.ezzetech.com');
}

function checkallbox(iobj)
{
inputArray = document.getElementsByTagName("input");
for(i=0; i<inputArray.length; i++)
{
if(inputArray[i].type.toLowerCase() == "checkbox" && inputArray[i] != iobj)
{
tempCheckbox = inputArray[i];
if(tempCheckbox.checked)
{
		tempCheckbox.checked = false;
}
else
{
		tempCheckbox.checked = true;
}}}}


function markall(iobj)
{
inputArray = document.getElementsByTagName("input");
for(i=0; i<inputArray.length; i++)
{
if(inputArray[i].type.toLowerCase() == "checkbox" && inputArray[i] != iobj)
{
tempCheckbox = inputArray[i];
if (iobj.checked) {
    tempCheckbox.checked = true;
}
else
{
    tempCheckbox.checked = false;
}}}}


function checkallper(iobj)
{
inputArray = document.getElementsByName("per[]");
for(i=0; i<inputArray.length; i++)
{
if(inputArray[i].type.toLowerCase() == "checkbox" && inputArray[i] != iobj)
{
tempCheckbox = inputArray[i];
if(tempCheckbox.checked)
{
		tempCheckbox.checked = false;
}
else
{
		tempCheckbox.checked = true;
}}}}


function PopUp(itarget, width, height)
{
    try
    {
	    width = (width != null) ? width : 400;
	    height = (height != null) ? height : 400;
	    var top, left;
	    top = (screen.height / 2) - (height / 2);
	    left = (screen.width / 2) - (width / 2);
	    var lwin=window.open(itarget,'','location=no,height='+height+',width='+width+',status=no,resizable=yes,scrollbars=yes,top='+top+',left='+left);
	    lwin.focus();
	}
	catch(oException)
	{}
}

function onlyNumbers(evt)
{
    var e = event || evt; // for trans-browser compatibility
    var charCode = e.which || e.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;

}

function tableSubmit(){
var chk_arr =  document.getElementsByName("id[]");
var chklength = chk_arr.length;             
$total=0
for(k=0;k< chklength;k++)
{
	if (chk_arr[k].checked == "1")
	$total++;
}
if ($total<1) {alert('You must select at least one item'); return false;}
var ans=confirm('Are your sure?');
if (ans==true){document.forms["dataform"].submit();}
}


function pendingSubmit(){
var chk_arr =  document.getElementsByName("id[]");
var chklength = chk_arr.length;             
$total=0
for(k=0;k< chklength;k++)
{
	if (chk_arr[k].checked == "1")
	$total++;
}
var action = document.getElementById('action');
var ass=action.value;
if (ass=="") return false;
else if ($total<1) {alert('You must select at least one item'); $("#action").val(""); return false;}
else if ($total>50) {alert('Warning\r\n-------------------------------------------\r\nPlease select maximum 50 item at a time.'); $("#action").val(""); return false;}
var ans=confirm('Are your sure?');
if (ans==true){document.forms["dataform"].submit();}
else {$("#action").val("");}
}

$(function(){
$('#message').smsCounter('#count');
});


$(document).ready(function () {
	$('#date1,#date2').datepicker({
		format: "yyyy-mm-dd"
	}); 
	$('#date1,#date2').on('changeDate', function(ev){
		$(this).datepicker('hide');
	});
	
	jQuery(".chosen").chosen();
    $(".select").chosen({ width: '100%' });
	
}); 

function explode(delimiter, string, limit) {
  if (arguments.length < 2 || typeof delimiter === 'undefined' || typeof string === 'undefined') return null;
  if (delimiter === '' || delimiter === false || delimiter === null) return false;
  if (typeof delimiter === 'function' || typeof delimiter === 'object' || typeof string === 'function' || typeof string ===
    'object') {
    return {
      0: ''
    };
  }
  if (delimiter === true) delimiter = '1';

  // Here we go...
  delimiter += '';
  string += '';

  var s = string.split(delimiter);

  if (typeof limit === 'undefined') return s;

  // Support for limit
  if (limit === 0) limit = 1;

  // Positive limit
  if (limit > 0) {
    if (limit >= s.length) return s;
    return s.slice(0, limit - 1)
      .concat([s.slice(limit - 1)
        .join(delimiter)
      ]);
  }

  // Negative limit
  if (-limit >= s.length) return [];

  s.splice(s.length + limit);
  return s;
}


function fillBill()
{
var id=$("#receiver").val();

$.get( "main/fillBill/"+id, function( data ) {
if (data!=""){
obj = JSON.parse(data);
$("#ac_title").val(obj.ac_title);
$("#ac_area").val(obj.ac_area);
$("#provider").val(obj.type);
$("#amount").focus();
}
});
}

$("form").submit(function(){
$("button[type=submit]").attr("disabled", "disabled");
$("button[type=submit]").empty().html("<i class='glyphicon glyphicon-record'></i> Please wait...");
});


function changeAmount(amount){
var numItems = $('.tk').length;
if (amount!=""){
    $('.tk').text(amount);
    $('.total').text(numItems*amount);
}}

function eVoucher(id){
    //alert(id);
    if (id>0) {$("#gw_amount").show(); }
    else { $("#gw_amount").hide();  }
}