// JavaScript Document
var site_url;


site_url="http://localhost/power/";
// site_url="http://ilbsm.itcurrent.com/index.php/";
/*Make Delete*/
///....................


function makedelete(id,table)
{

    if(confirm("Are you sure you want to delete this item ?"))
    {
        window.location=site_url+"Admin/delete/"+table+"/"+id;
    }
}

// JavaScript Document
var site_url;


site_url="http://localhost/power/";
// site_url="http://ilbsm.itcurrent.com/index.php/";
/*Make Delete*/
///....................

function makedelete(id,table)
{

    if(confirm("Are you sure you want to delete this item ?"))
    {
        window.location=site_url+"Admin/delete/"+table+"/"+id;
    }
}
function searchsingle(table)
{

	$("#content").html("<tr><td align='center' colspan='18'><img src='images/loading.gif'/></td></tr>");

	var data=$("#"+table).serialize();
	$.ajax({
			type: "POST",
			url : site_url+"Main/searchsingle/"+table,
			data : data,
			success : function(e)
			{
			 $("#content").html(e);
			}
		});
}
function collateralcloneform(arg)
{
		arg.preventDefault();
		 var cloneCount = 1;
		 var clone=$( "div.collateralclone:last-child" ).clone().appendTo( "#collateral" );
		 clone.find("option:selected").prop("selected", false)
        clone.find("input[name='collateral[]']").val("");
        clone.find("input[name='collateral_qty[]']").val("");

}
function budgetcloneform(arg)
{
		arg.preventDefault();
		 var cloneCount = 1;
		 var clone=$( "div.budgetclone:last-child" ).clone().appendTo( "#budget" );
		 clone.find("option:selected").prop("selected", false)
      clone.find("input[name='sign[]']").val("");
        clone.find("input[name='category[]']").val("");
        clone.find("input[name='income_amt[]']").val("");
}
function outbudgetcloneform(arg)
{
		arg.preventDefault();
		 var cloneCount = 1;
		 var clone=$( "div.outbudgetclone:last-child" ).clone().appendTo( "#outbudget" );
		 clone.find("option:selected").prop("selected", false)
     clone.find("input[name='sign[]']").val("");
        clone.find("input[name='category[]']").val("");
        clone.find("input[name='outcome_amt[]']").val("");
}
function removerlgn(event)
{
    var target = $(event.target);
    var cl=$(".collateralclone").length;
    if(cl==1)
    {
    alert("You can not remove");
    }
    else{
    target.parent().parent().remove();
    }
}
function remover(event)
{
    var target = $(event.target);
    var cl=$(".budgetclone").length;
    if(cl==1)
    {
    alert("You can not remove");
    }
    else{
    target.parent().parent().remove();
    }
}
function outremover(event)
{
    var target = $(event.target);
    var cl=$(".outbudgetclone").length;
    if(cl==1)
    {
    alert("You can not remove");
    }
    else{
    target.parent().parent().remove();
    }
}

function loanamtsearch(voucher)
{
    data="voucher="+voucher;
    $.ajax({
            type: "POST",
            url : "Main/searchloanamt/",
            data : data,

            success : function(e)
            {
             var v=JSON.parse(e);
             $("#getmoney").val(v.loan_amt);
             $("#getname").val(v.customer_name);
             $("#getaddress").val(v.address);
            }
        });
}
function categorysearch(sign,arg)
{
    data="sign="+sign;
    $.ajax({
            type: "POST",
            url : "Main/searchcategory/",
            data : data,

            success : function(e)
            {
             var v=JSON.parse(e);
             // $("#categ").val(v.category);
             $(arg.target).parent().parent().find("input[name='category[]']").val(v.category);
            }
        });
}
function budgetcategorysearch(sign)
{       
    data="sign="+sign;
        $.ajax({
                type: "POST",
                url : "Main/earchbudgetscategory",
                data : data,

                success : function(e)
                {              

                 $("#categoryresult").val(e);
                }
            });
}
function outcategorysearch(sign,arg)
{
    data="sign="+sign;
    $.ajax({
            type: "POST",
            url : "Main/outsearchcategory/",
            data : data,
            success : function(e)
            {
              var v=JSON.parse(e);
              // $("#categ").val(v.category);
              $(arg.target).parent().parent().find("input[name='category[]']").val(v.category);
            }
        });
}

function checkvoucher(voucher)
{
    $vrtype=$("#vrtype").val();
    data="vrno="+$vrtype+voucher;
    $.ajax({
        type:"POST",
        url:"Main/checkvoucher",
        data:data,

        success : function(e)
        {
            if(e==($vrtype+voucher))
            {
                alert("Already Insert Voucher");
                $("#vrno").val("");
            }
            else{
            }
        }
    });
}
function checkredeemvoucher(voucher)
{
    // $vrtype=$("#vrtype").val();
    data="voucher="+voucher;
    $.ajax({
        type:"POST",
        url:"Main/checkredeemvoucher",
        data:data,

        success : function(e)
        {
            if(e==(voucher))
            {
                alert("Already Insert Voucher");
                $("#voucher").val("");
                $("#getmoney").val("");
                $("#getname").val("");
                $("#getaddress").val("");
                $("#voucher").focus();
            }
            else{
            }
        }
    });
}
function changetonumber(number)
{
    number=number.replace(/[^\d\.\-]/g,"");
    var number=parseFloat(number);
    // num=number.toLocaleString();
    $("#number").val(number.toLocaleString());
}
function outchangetonumber(number)
{
    number=number.replace(/[^\d\.\-]/g,"");
    var number=parseFloat(number);
    // num=number.toLocaleString();
    $("#outnumber").val(number.toLocaleString());
}
function changetonumber(number)
{
    number=number.replace(/[^\d\.\-]/g,"");
    var number=parseFloat(number);
    // num=number.toLocaleString();
    $("#number").val(number.toLocaleString());
}
function changetonum(number2)
{

number=number2.replace(/[^\d\.\-]/g,"");
var number=parseFloat(number);

    // num=number.toLocaleString();
    $("#number2").val(number.toLocaleString());
}
