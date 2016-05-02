<?php
// Start the session
session_start();
?>
<html>
	<head>
	<meta charset="UTF-8">
		<title>QuickBooks Web Connector Locations Updater</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://192.168.11.59/quickbooks-php-master/docs/web_connector/PICK_LOCATION/style.css">
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css">
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
<script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>

<script type="text/javascript">
$(document).ready(function(e){
	 $("input:text:first").focus();
	 $("#update").click(function(){
		 if($("#sku").val()==""){
		 alert('SKU NEEDED!');
		 return false;
		 }
		 if($('#pickValue').val()=="" && $('#stockValue').val()==""){
			 alert('At least one location is required!');
			 return false;
			 }
});//end submit validation
 


/* get xml file using ajax */
$(document).on('keydown', '#sku',function(){
$.ajax({
    url: "locations.xml",
    dataType: "xml",
    success: function(xmlResponse) {
         /* parse response */
         var data = $("Row", xmlResponse).map(function() {
         return {
             value: $("MYSKU", this).text()
          
         };
         }).get();

         /* bind the results to autocomplete */
         $("#sku").autocomplete({
             source: data,
			 minLength: 2
         });
     }
});	 
});
$('#query').click(function() {
  var myval = $('#sku').val();
});

});
</script>

	</head>
	<body>
	<div id="wrapper">
	<h2>Location Updater</h2>
		<form method="POST" action="form.php" data-ajax=false>
	
			<input type="hidden" name="submitted" value="1" />
						
			<table>
				<tr>
					<td>
						SKU:
					</td>
					<td>
					
						<input type="tel" name="sku" id="sku" value="" autocomplete="off" autofocus />
						
			
					</td>
					<td><span id="clear">X</span></td>
				</tr>
				<tr>
					<td>
						PICK:
					</td>
					<td>
						<input type="text" name="pickValue" id="pickValue" value="" />
					</td>
				</tr>
				<tr>
					<td>
						STOCK:
					</td>
					<td>
						<input type="text" name="stockValue" id="stockValue" value="" />
					</td>
				</tr>
			</table>
			<input type="submit" data-inline="true" name="query" value="QUERY!" id="query" />
			<input type="submit" data-inline="true" name="update" value="UPDATE!" id="update" />
			
			
		</form>
		<?php 
if (isset($_POST['update'])) {
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$sku = $_POST['sku'];
	$_SESSION["ssku"] = $_POST['sku'];
	$_SESSION["spick"] = $_POST['pickValue'];
	$_SESSION["sstock"] = $_POST['stockValue'];
	$mysqli = mysqli_connect("localhost", "root", "root", "quickbooks_server11");
$query = mysqli_query($mysqli, "SELECT * FROM my_item_table WHERE quickbooks_fullname='$sku'");
if(mysqli_num_rows($query)>0){
	if(empty($_POST['stockValue'])){
	header('Location: handler.php?sku='. $_SESSION["ssku"]. '&pickValue='. $_SESSION["spick"]);
	}elseif(empty($_POST['pickValue'])){
		header('Location: handler.php?sku='. $_SESSION["ssku"]. '&stockValue='. $_SESSION["sstock"]);
		
	}else{
	
		header('Location: handler.php?sku='. $_SESSION["ssku"]. '&pickValue='. $_SESSION["spick"] . '&stockValue='. $_SESSION["sstock"]);
	}
}else{
	echo "Cannot Find SKU: " . $sku . " !!!! Please Try Again.";
}
	
}
}else{
	if ($_SERVER["REQUEST_METHOD"] == "POST")
	{
		$sku = $_POST['sku'];
		$mysqli = mysqli_connect("localhost", "root", "root", "quickbooks_server11");
		$query = mysqli_query($mysqli, "SELECT * FROM my_item_table WHERE quickbooks_fullname='$sku'");
		if(mysqli_num_rows($query)>0){
			while($row = mysqli_fetch_assoc($query)){
				echo "SKU: <span id='myval'>" . $row["quickbooks_fullname"] . "</span><br>" . "PICK: " . $row["quickbooks_pick"] . "<br>" . "STOCK: " . $row["quickbooks_stock"] . "<br>" . "QTY: " . $row["quickbooks_qoh"];
			}
		}else{
			echo "Cannot Find SKU: " . $sku . " !!!! Please Try Again.";
		}
	
	}
	
}
?>
		</div><script>
		$(document).ready(function(){
			$('#clear').click(function () {
    $('#sku').val('');
});
var myval = $('#myval').text();
if(myval==''){
	//alert("no myval");
	}else{
  $('#sku').val(myval);
  }
});</script>
	</body>

</html>