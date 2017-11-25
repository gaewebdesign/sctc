<h2></h2><html>
<meta content="text/html; charset=UTF-8" http-equiv="content-type" />
<head>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js" type="text/javascript"></script>

<script language="javascript">


function SingleSelect(regex,current)
{

 re = new RegExp(regex);
 forms =  document.forms.length;
 len =  document.forms[0].elements.length;

 if( current.checked == true){
//     alert("checked");
//     current.checked = false;
//   return
 }

 for(i=0 ; i<forms; i++)
         for(j = 0; j < len; j++) {
                elm = document.forms[i].elements[j];

               if (elm.type == 'checkbox') {
                        if (re.test(elm.name)) {
                                   elm.checked = false;
                                }
               }
          }
  
  current.checked = true;

  if(current.value=="RF" | current.value=="NRF") 
     $( "#dialog" ).show();
  else
     $( "#dialog" ).hide();


// Removes error message which comes up when Waiver button is selected
// can only do this on the checkboxes (but not when filling out text)
  $("#ERROR").hide("" );

}
function Debug(t){

  $("#Debug").text(t)


}

var mode = "pay"

function Initialize(){


  // use .htaccess to hide FREE mode via
  // either membership.php?mode=free

  //javascript returns  -1 (no mode)
   var r =  window.location.search.indexOf("mode")  


  var js = '<?php echo "".$_GET["mode"]; ?>'



  var text = " Please fill out a  membership form for each additional family member. For each family member at a single address, "  
  text += " $.99 is charged (via Paypal). Be sure to use the same exact address as previously entered"

  // this overrides pay is the default mode

// if(js.includes("free") ){
  if(js.indexOf("free") !== -1){
         // has to be ?mode=free
            mode = "free"
            text = "Please fill out a membership form for each additional family.  There are no addditional charges for each family member at a single address."
            text += " Be sure to use the same exact address as previously entered."
           $("#LINE2").text("There is no membership charge for additional family members living at the same address")
           $("#LINE3").text("Fill out the form, and each family member will be be added to the membership without going to Paypal")
           $('#PaypalButton','#pptext').val("Submit application")
   }


  $('#dialog').text(text);

  
  if(js.includes("free")){
            Debug(".")
  }else{
            Debug("..")
  }

// DETERMINE WHETHER NON-RESIDENTS CAN APPLY  ***************
  var request = $.ajax({
    url: "ajax.php",
    type:  "GET",
    dataType: "html"

   });

  request.done( function(msg){


       if( msg == "no"){
          $("#NONRES_OK").hide();            
          $("#NONRES_NO").show();            
       }else{

          $("#NONRES_NO").hide();
          $("#NONRES_OK").show();
       }
  });

  request.fail(function( jqXHR, textStatus){

//   alert("Request failed; " + textStatus);

  });

// ********************************************************

//    defaultParameters()
      clearParameters()
}


function clearParameters(){

/*
  $('#FIRST').val("")
  $('#LAST').val("")
  $('#ADDRESS').val("")
  $('#CITY').val("")
  $('#EMAIL').val("")
  $('#URL').val("")
  $('#ZIP').val("")
  $('#MALE').attr('checked',false)
  $('#FEMALE').attr('checked',false)
  $('#NTRP').val("")
  $('#_phonecode').val("")
  $('#_phonepre').val("")
  $('#_phonepost').val("")
*/

  $('#NEW').attr('checked',false)
  $('#RENEW').attr('checked',false)


  $('#paypal').attr('checked', false)


  $('#RS').attr('checked', false )
  $('#RF').attr('checked', false )
  $('#RJ').attr('checked', false )

  $('#NRS').attr('checked', false )
  $('#NRF').attr('checked', false )
  $('#NRJ').attr('checked', false )

}




function defaultParameters(){

  $('#FIRST').val("Josie")
  $('#LAST').val("Bell")

  $('#RENEW').attr('checked',true)
  $('#FEMALE').attr('checked',true)


  $('#NTRP').val("4.0")

  $('#ADDRESS').val("1244 Maryann Drive")
  $('#CITY').val("Santa Clara")
  $('#EMAIL').val("josie.bell")
  $('#URL').val("gmail.com")
  $('#ZIP').val("95051")


  $('#_phonecode').val("408")
  $('#_phonepre').val("883")
  $('#_phonepost').val("8231")

//  $('#Debug').text("Josie")
}


function validateEmail(email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
} 

function CheckFields(){
 fname = $("#FIRST").val();
 lname = $("#LAST").val();
 gender = $("#GENDER").val();
// ntrp = $("#NTRP").val();
 address = $("#ADDRESS").val();
 city = $("#CITY").val();
 zip = $("#ZIP").val();
 email = $("#EMAIL").val();
 url = $("#URL").val();

 mtype = $("#RS").prop("checked") | $("#RF").prop("checked") | $("#RJ").prop("checked");
 mtype |= $("#NRS").prop("checked") | $("#NRF").prop("checked") | $("#NRJ").prop("checked");;

 err=0;
 if( fname.length<2 | (lname.length < 2)  )                        err |= 1
 if( $("#MALE").prop("checked") ==  $("#FEMALE").prop("checked") ) err |= 2
 if(( address.length<2) | (city.length < 2) |  (zip.length<2 )  )  err |= 4
 if(email.length < 2 | url.length<2)                               err |= 8

// TODO check email address (.net, etc)
 if( !validateEmail( email+"@"+url))  err |=0x8;

 if(!mtype)  err |= 16


// Santa Clara resident  (single,family,junior)
if(  $("#RS").prop("checked")  ||   $("#RF").prop("checked")  || $("#RJ").prop("checked")  ){
        santa = city.match(/santa/i)
        clara = city.match(/clara/i)
        if( (santa == null) && (clara==null) ) err |= 32 

}

   message = ""
   if(err ) message = "ERROR "
   if(err & 1) message += " Name "
   if(err & 2) message += " Gender "
   if(err & 4) message += " Address "
   if(err & 8) message += " Email "
   if(err & 16) message += " Membership type "
   if(err & 32) message += " Santa Clara address "


//   message = "PLEASE COMPLETE MEMBERSHIP FORM FOR THE PAYPAL BUTTON TO APPEAR"

   if(err){
         $("#ERROR").text(message + " required");
         $("#ERROR").show();

         $("#paypal").prop("checked", false);
         $("#PaypalButton").hide()
         $("#Signature").show()


   }else{
         $("#ERROR").text("" );
         $("#ERROR").hide("" );
   }

        return err
   
}

function Paypal(){

//return;   // Turn off Paypal option until its tested

    err = CheckFields();

    if(err) return

    $("#familymembers").hide() ;


    if( $("#paypal:checked").val() != undefined ){
             $("#PaypalButton").show() 
             $("#Signature").hide() 

             fn = $("#FIRST").val();
             ln = $("#LAST").val();


             $("#WAIVER #NAME").text(fn + "  " + ln);
             $("#WAIVER #NAME").show();


       }else{
             $("#PaypalButton").hide() ;
             $("#Signature").show() 
             $("#WAIVER #NAME").hide();
           }

}


window.onload = Initialize;

</script>

</head>


<body>
</body>
<style>

body,html{
      margin-top:1;
      margin-left:30;
      margin-right:25;
}

#title { font-size:24 px; font-weight: bold;}
#app   { font-size:22 px; font-weight: bold;}
#inst  { font-size:16 px; }

.eline{
        font-family: "Comic Sans MS", "Brush Script MT", cursive;
        font-size:18 px;
        background: #fefefe;
        background-color: #ffeeff;
        background-color: rgb(255,255,255);
        border: none;
        border-bottom: 1px solid black;
        height : 27 px;
        width: 20%;
}


#bar_tr{ border-top: 2px solid black;}

.pntrp  { width : 7%; }
.paddress  { width : 35%; }
.pphone  { width : 10%; }
.pzip  { width : 10%; }
.pteam  { width : 11.5%; }
.paddress  { width : 35%; }
.psign  { width : 30%; }
.pwhite  { 
        width : 35%; 
        background-color: #ffff00;
}

.button{ background-color: #ffeeff; width:50% ; height:2em ; font-size:12px; font-weight:bold}
.dialog{ background-color: #99ccff; width:85% ; height:3.5em ; font-weight:bold}
.ERROR{ background-color: #99ccff; width:85% ; height:20px ; font-weight:bold}

</style>

<center>
<div id="title"> Santa Clara Tennis Club</div>
<div id="app"> 
<?php 
    include("kotoshi.inc");
    echo($KOTOSHI); 
?>
 Membership Application</div>


</center>
<?php include "includes.inc" ?>

INSTRUCTIONS:
<div id="inst">
 <ol>
  <li> Please completely fill out the application below. Be sure to sign, date it and include payment. </li> 
  <li> You may submit your SCTC membership by one of the following methods. 
    <br>
   <b>Online via Paypal is preferred.  Note that using Paypal does not require an account.</b></li>
     <ul>
       <li> <b>Online via Paypal</b>: Fill out the information and click the Waiver button which will bring up the Paypal button
              to submit your application  </li>
        <li> <b>Fill in the fields online</b>, sign a printed copy, date it, include payment  and mail in the application.</li>
        <li> <b>Print out a copy</b> and write in the information, sign and date it , include payment  and mail in the application. </li>

     </ul>
  <li> Membership runs January through December</li>
  <li> For family memberships, a separate form must be completed for each additional family member.  </li>
      <ul>
        <li>Each family member must have the same home address.  </li>
        <li id="LINE2">In the online application, there is a  $0.99 membership charge for each additional family member. </li>
        <li id="LINE3">Fill out the form, and follow the same procedure as for the primary family member.  </li> 
 </li>
      </ul>
  <li> Mail application and check payable to SCTC to: SCTC PO Box 2645, Santa Clara CA , 95054</li>
  <li> For any questions or for more information email to memb@sctennisclub.org</li>
 </ol>
  </div >

<table width="800"><td id="bar_tr"></td></table>


<form id="_FORM" name="signup" action="check", method="post">
<p>
 <span>
  <input id = "NEW" type="checkbox" name="renew" value="" onclick="SingleSelect('renew',this);"/> 
  New 
  <input id = "RENEW" type="checkbox" name="renew" value="N" onclick="SingleSelect('renew',this);"/>  
  Renewal 
 </span>
 <p>


  First Name: <input  required value="" class="eline" name="fname" placeholder="" type="text" id="FIRST"/> &nbsp;&nbsp;&nbsp;&nbsp; 
  Last Name:  <input  required value="" class="eline e200" name="lname" type="text" id="LAST"/>  <br>
  <p>
  M<input type="checkbox" id="MALE" value="M" name="gender" onclick="SingleSelect('gender',this);"/>  
  F<input type="checkbox" id="FEMALE" value ="F" name="gender" onclick="SingleSelect('gender',this);"/> 
  &nbsp;&nbsp;&nbsp;&nbsp;
  NTRP*: <input value="" class="eline pntrp" name="ntrp" maxlength="5" type="text" id = "NTRP"/> &nbsp;&nbsp;
  (2.5/3.0/3.5/4.0/4.5/5.0) (optional)
  <p>
  <p>

  Address: <input  value="" class="eline paddress" name="address" type="text" id="ADDRESS"/>  
  <br>
  City:  <input value="" class="eline" name = "city" type="text" id="CITY"/> 
  &nbsp;&nbsp;&nbsp;&nbsp;
  Zip: <input value="" class="eline pzip" name="zip" maxlength="5" type="text" id ="ZIP"/>   
  <br>
  Contact Phone: (<input id="_phonecode" value = "" class="eline pphone" name="code" maxlength="3" type="text"/>)    
  <input id="_phonepre" value = "" class="eline pphone" name="phonepre"  maxlength="3" type="text"/> -   
  <input id="_phonepost" value = "" class="eline pphone" name="phonepost" maxlength="4" type="text"/>    
  <p>
  Email Address: <input class="eline" name="email" type="text" id="EMAIL"/>@<input id="URL" value="" class="eline" name="url" type="text" "/> 

  <p>
  USTA Team/Captain's Name: <input value="" class="eline pteam" name="team" type="text" maxlength="9"/> 
  / <input value="" class="eline e150" name="captain" type="text"/> (if you are joining a USTA team)

  <p>
  * If you don't have an official NTRP rating and would like one, use the definitions in General Characteristics of various 
  NTRP Playing Levels on the USTA.com website to self-rate 
  or contact a USPTA certified teaching pro to access your playing level.
  If your rating changes during the membership year, please notify the SCTC Director of Membership at
  memb@sctennisclub.org to update the membership roster.
  <p>


  <div id="dialog" class="dialog" style="display:none">
<!-- place holder for free or 0.99 dialog  -->

  </div>




  <div id="dialog2" class="dialog" style="display:none">
    Mail to SCTC PO Box 2645, Santa Clara, CA 95054.
  </div>


  <br>
  <b>MEMBERSHIP TYPE:</b> Mark the applicable membership type. <br>
  Santa Clara Residents &nbsp;
  <span style="position: relative; left: 1em">
  <input type="checkbox" id="RS" name="membership" value="RS" onclick="SingleSelect('memb',this);"/> Single - $<?php echo RS ?>&nbsp;
  <input type="checkbox" id="RF" name="membership" value="RF" onclick="SingleSelect('memb',this);"/> Family - $<?php echo RF ?>&nbsp;
  <input type="checkbox" id="RJ" name="membership" value="RJ" onclick="SingleSelect('memb',this);"/> Jrs(under 16) - $<?php echo RJ ?>  &nbsp;
  </span>
  <br>

<span id="NONRES_NO">
  Non-SC Residents &nbsp;
  At this time SCTC is not accepting non-resident memberships .<br>
  Email memb@sctennisclub.org for more info and to be put onto the club waiting list.

</span>

<span id="NONRES_OK">
  Non-SC Residents &nbsp;
  <span style="position: relative; left: 2.5em">
  <input type="checkbox" id="NRS" name="membership" value="NRS" onclick="SingleSelect('memb',this);"/> Single- $<?php echo NRS ?>&nbsp;
  <input type="checkbox" id="NRF" name="membership" value="NRF" onclick="SingleSelect('memb',this);"/> Family - $<?php echo NRF ?>&nbsp;
  <input type="checkbox" id="NRJ" name="membership" value="NRJ"  onclick="SingleSelect('memb',this);"/> Jrs(under 16) - $<?php echo NRJ ?> &nbsp;
  </span>
</span>


  <p>
  <b>I am interested in helping with club activities:</b><br>
  <input type="checkbox" name="help[]" value="T"/> Tournaments &nbsp;
  <input type="checkbox" name="help[]" value="R"/> Refreshments for Events&nbsp;
  <input type="checkbox" name="help[]" value="S"/> Socials&nbsp;&nbsp;&nbsp;
  Other  <input class="eline" name="other" type="text", value=""/>
  <p>

  <div id="ERROR" style="display:none; background-color: #7bbcee; height:20px">
  </div>

  <span id="WAIVER">
      <input type="checkbox" id="paypal" value="Y" onclick="Paypal(this);" /> 
      <b>WAIVER:</b> <br>
      By checking this box, and submitting this application to Santa Clara Tennis Club, The signee above 
      <span id="NAME" style="display:none; font-weight:bold; background-color: #99cff; "></span>
      hereby agrees to 
      indemnify and hold harmless the City of Santa Clara and the Santa Clara  Tennis Club,
      from and against any and all liabilities for any injury which may be incurred by
      the undersigned arising out of, or in any way connected in any event sponsored by the aforenamed.
      <p>
  </span>

  <div id="PaypalButton" style="display:none">
    <input id="pptext" type="submit" class="button" value="Submit Application " /> &nbsp;&nbsp;&nbsp;&nbsp;
    <input type="hidden" name="mode" value='<?php echo($_GET["mode"]) ?>' >
  </div>

  <p>
  <div id="Signature">Signature: <input value="" class="eline psign" name="signature" type="text"/> 
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Date: <input value="" class="eline" name="SignedDate" type="text"/> &nbsp;&nbsp;&nbsp;&nbsp;
  </div>  

  </form>

  <p><br>
  <table width="800"><td id="bar_tr"></td></table>

  <div style="position:absolute; right:40; width: 20%; text-align: right;">
   Rev 1/1/2017 (ro)
  </div>

  <div style="position:absolute; width: 80%; text-align: left;">
  For Office Use Only: &nbsp;&nbsp;&nbsp;  Received on  _______________________  &nbsp;&nbsp;
  <input type="checkbox" name="OffCheck"/>Check&nbsp;&nbsp;
  <input type="checkbox" name="OffCash"/>Cash
  </div>



  <br>
  <div id = "Debug"  style="margin-left !important;">

  </div>
