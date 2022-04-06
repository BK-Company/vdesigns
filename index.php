<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Mural Design</title>
<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="css/cropper.css" type="text/css"/>
<style type="text/css"><?php

	// get dimensions
	if(!isset($_REQUEST['txtWidth'])){
		$_REQUEST['txtWidth'] = 300;
	}
	
	if(!isset($_REQUEST['txtHeight'])){
		$_REQUEST['txtHeight'] = 250;
	}
	
	if(!isset($_REQUEST['txtDepth'])){
		$_REQUEST['txtDepth'] = 25;
	}

	// Upload customer image
	$strUserImag = "";
	if(isset($_REQUEST['uploadedImage']) && !empty($_REQUEST['uploadedImage'])){
		
		// Check cropped image data url exists, then save it.
		$encodedData = $_REQUEST['uploadedImage'];
		$filteredData = substr($encodedData, strpos($encodedData, ",")+1);
		
		$filteredData = str_replace(' ','+',$filteredData);
		$unencodedData = base64_decode($filteredData);
		
		$strUserImag = 'images/user-image.png';
		
		// Write $imgData into the image file
		file_put_contents($strUserImag, $unencodedData);
	}

	// Save cropped image to pass in mural frame
	$strMuralImag = "";
	
	// Check cropped image data url exists, then save it.
	if(!empty($_REQUEST['hdCroppedImage'])){
		$encodedData = $_REQUEST['hdCroppedImage'];
		$filteredData = substr($encodedData, strpos($encodedData, ",")+1);
		
		$filteredData = str_replace(' ','+',$filteredData);
		$unencodedData = base64_decode($filteredData);
		
		$strMuralImag = 'images/mural-image.png';
		
		// Write $imgData into the image file
		file_put_contents($strMuralImag, $unencodedData);
	}
	
	// Get image width and height
	if(!empty($strMuralImag) && file_exists($strMuralImag)){
		$objMuralImage = new Imagick($strMuralImag);
		
		$intOrigImgHieght = $objMuralImage->getImageHeight();
		$intOrigImgWidth = $objMuralImage->getImageWidth();
		
		// Free memory
		$objMuralImage->destroy();
	}
	else{
		$intOrigImgWidth = 400;
		$intOrigImgHieght = 300;
	}
	
	$intFinalImgWidth = "350px";
	if(isset($_REQUEST['txtWidth'])){
		$intFinalImgWidth = $_REQUEST['txtWidth'] . "px";
	}
	
	$intFinalImgHeight = "300px";
	if(isset($_REQUEST['txtHeight'])){
		$intFinalImgHeight = $_REQUEST['txtHeight'] . "px";
	}
	
	$intFinalImgDepth = "25px";
	if(isset($_REQUEST['txtDepth'])){
		$intFinalImgDepth = $_REQUEST['txtDepth'] . "px";
	}
	
	// Caluclate x position of main image based on original width and entered
	$intFinalImgXPos = ($intFinalImgWidth - $intOrigImgWidth)/2;
	
	// Caluclate x position of edge image based on entered width and entered depth
	$intEdgeImgXPos = ($intFinalImgWidth + $intFinalImgDepth) - $intFinalImgXPos;
	
	?>/*Thumbnail Background*/
	.thumb {
		width: <?php print($intFinalImgWidth); ?>; height: <?php print($intFinalImgHeight); ?>; margin: 15px auto;
		perspective: 1000px; position:relative;z-index:99;
		transition:10s;
	}
	.thumb a {
		display: block; width: 100%; height: 0;
		/*double layered BG for lighting effect*/
		background: 
			linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), 
			url("<?php print($strMuralImag); ?>");
		/*disabling the translucent black bg on the main image*/
		background-size: 0, auto;
		/*3d space for children*/
		transform-style: preserve-3d;
		background-position-x: <?php print($intFinalImgXPos); ?>px;
		/*box-shadow: 0 150px 100px 50px rgba(0, 0, 0, 0.5);*/
		transition: height 5s;
		/*transition: all 0.5s;*/
		transform: rotateY(30deg);
		transform-origin: left;
		-webkit-box-shadow: 0px 0px 11px -1px rgba(0,0,0,0.75);
		-moz-box-shadow: 0px 0px 11px -1px rgba(0,0,0,0.75);
		box-shadow: 0px 0px 11px -1px rgba(0,0,0,0.75);
	}
	
	/*bottom surface */
	.thumb a:after {
		/*36px high element positioned at the bottom of the image*/
		content: ''; position: absolute; left: -<?php print($intFinalImgDepth); ?>;
		width: <?php print($intFinalImgDepth); ?>; height: 0%;
		/*inherit the main BG*/
		background: inherit; background-size: auto, auto;
		/*draw the BG bottom up*/
		background-position-x: <?php print($intEdgeImgXPos); ?>px;
		/*rotate the surface 90deg on the bottom axis*/
		transform: rotateY(-90deg); transform-origin: right;
		transition: height 5s;
		
		-webkit-box-shadow: -8px 1px 8px 4px rgba(0,0,0,0.30);
		-moz-box-shadow: -8px 1px 8px 4px rgba(0,0,0,0.30);
		box-shadow: -8px 1px 8px 4px rgba(0,0,0,0.30);
	}
	
	.edge{height:100% !important;}
	.thumb a.edge:after{height:<?php print($intFinalImgHeight); ?> !important;}
	
	.frame {
		width: <?php print($intFinalImgWidth); ?>; height: <?php print($intFinalImgHeight); ?>; margin: 15px auto;
		perspective: 1000px;
		position:absolute;
		top:0;
		left:0;
		right:0;
		z-index:0;
	}
	
	.frame a {
		display: block; width: 100%; height: 100%;
		/*double layered BG for lighting effect*/
		background: #666;
			
		/*disabling the translucent black bg on the main image*/
		background-size: 0, auto;
		background-position-x: <?php print($intFinalImgXPos); ?>px;
		/*3d space for children*/
		transform-style: preserve-3d;
		/*box-shadow: 0 150px 100px 50px rgba(0, 0, 0, 0.5);*/
		transition: all 0.5s;
		transform: rotateY(30deg);
		transform-origin: left;
		
		-webkit-box-shadow: 0px 0px 11px -1px rgba(0,0,0,0.75);
		-moz-box-shadow: 0px 0px 11px -1px rgba(0,0,0,0.75);
		box-shadow: 0px 0px 11px -1px rgba(0,0,0,0.75);
		
	}

	/*bottom surface */
	.frame a:after {
		/*36px high element positioned at the bottom of the image*/
		content: ''; position: absolute; left:-<?php print($intFinalImgDepth); ?>;
		width: <?php print($intFinalImgDepth); ?>; height: <?php print($intFinalImgHeight); ?>;
		/*inherit the main BG*/
		background: inherit; background-size: cover, cover;
		/*draw the BG bottom up*/
		background-position-x: <?php print($intEdgeImgXPos); ?>px;
		/*rotate the surface 90deg on the bottom axis*/
		transform:rotateY(-90deg); transform-origin:right;
		
		-webkit-box-shadow: -8px 1px 8px 4px rgba(0,0,0,0.30);
		-moz-box-shadow: -8px 1px 8px 4px rgba(0,0,0,0.30);
		box-shadow: -8px 1px 8px 4px rgba(0,0,0,0.30);
	}
	
	/* Effetcs */
	.sepia {
		-webkit-filter: sepia(100%);
		-moz-filter: sepia(100%);
		-o-filter: sepia(100%);
		filter: sepia(100%);
		transition:0s !important;		
	}
	
	/* .thumb a.sepia:after{
		-webkit-filter: sepia(100%);
		-moz-filter: sepia(100%);
		-o-filter: sepia(100%);
		filter: sepia(100%);
	} */
	
	.grayscale {
		-webkit-filter: grayscale(100%);
		-moz-filter: grayscale(100%);
		-o-filter: grayscale(100%);
		filter: grayscale(100%);
		transition:0s !important;
	}
	
	.nofilter {
		transition:0s !important;
	}
	
	/* .thumb a.grayscale:after {
		-webkit-filter: grayscale(100%);
		-moz-filter: grayscale(100%);
		-o-filter: grayscale(100%);
		filter: grayscale(100%);
	} */

	.container{
		max-width:800px;
		min-height:600px;
		margin:0 auto;
		padding:0;
		border:1px solid #cccccc;
		}
	.topbar{
		background:#dc3545;
		height:50px;
		font-size:25px;
		font-weight:600;
		color:#ffffff;
		text-align:center;
		padding:5px 0 10px;
		}
	.img-field{
		width:90%;
		min-height:300px;
		border:1px solid #cccccc;
		margin:20px auto 20px;
		position:relative;
		}
	.input-wrp{
		width:90%;
		margin:0 auto 20px;
		}
	.input-fld{
		width:100%;
		min-height:45px;
		padding:0 20px;
		}
	.title-bx{
		display:inline-block;
		padding:33px 0 0;
		}
	button.input-fld{
		color:#000000;
		height:50px;
		margin-left:10px;
		border:none;
		}
	button.input-fld:first-child{
		margin-left:0;
		}
	.dimension{
		padding:0;
		margin-left:10px;
		}
	.dimension input{
		border:1px solid #cccccc;
		}
	.dimension:first-child{
		margin-left:0;
		}
	.dimension label{
		font-size:12px;
		}
	.save-btn{
		border:none;
		background:#dc3545;
		color:#ffffff;
		min-height:45px;
		}	
	.fileUpload2 {
		position:absolute;
		right:0px;
		top:0px;
		overflow: hidden;
		height: 100%;
		width: 100%;
		color:#fff;
		line-height:43px;
		text-align: center;
		display:inline-block;
		transition:1s;
	}	
	.fileUpload2:before {
		content:'UPLOAD';
		width:20%;
		height:100%;
		display:block;
		right:0px;
		position:absolute;
		background-color:#dc3545;
		transition:1s;
	}
	.fileUpload2 input.upload {
		position: absolute;
		top: 0;
		right: 0;
		margin: 0;
		padding: 0;
		font-size: 20px;
		cursor: pointer;
		opacity: 0;
		filter: alpha(opacity=0);
		height: 100%;
		width:100%;
		text-align: center;
	}
	.upld-area {
		width:100%;
		position:relative;
		min-height:45px;
		border:1px solid #cccccc;
		border-radius:0px;
		padding:5px 0px;
	}
	#uploadFile, #uploadFile2, #uploadFile3 {
		border: none;
		width: 100%;
		height:30px;
		background-color:transparent;
		padding-left:20px;
		color:#000 !important;
		-webkit-text-fill-color: rgba(0, 0, 0, 1); 
	   -webkit-opacity: 1;
	}	
		
	.btn.btn-danger{
		text-transform:uppercase;
		}		
</style>
</head><?php

// Check file exists, then we will add the class to
$strMainDivClass = "";
if(!empty($strMuralImag) && file_exists($strMuralImag)){
	$strMainDivClass = "thumb";
} 

?><body><?php

if(!isset($_REQUEST["setAction"])){
	$_REQUEST["setAction"] = "";
}

switch($_REQUEST["setAction"]){
	
	case "":
		?><div class="main">
			<form name="frmMural" id="frmMural" action="" method="post" autocomplete="off">
				<div class="container">
					<div class="topbar">MURAL DESIGN</div>
					<div class="img-field">
						<div class="<?php print($strMainDivClass); ?>" id="muralFrame">
							<a href="javascript:;"></a>
						</div>
						<div class="frame">
							<a href="javascript:;"></a>
						</div>
					</div>
					<div class="input-wrp row">
						<div class="col-4">
						<span class="title-bx"><strong>STEP 1:</strong> Enter the size</span>
						</div>
						<div class="col-8 row"><?php
							
							?><div class="col-3 dimension"><label>Width (Inch)</label><input id="txtWidth" class="input-fld" type="text" value="<?php print($_REQUEST['txtWidth']); ?>" name="txtWidth"></div>
							<div class="col-3 dimension"><label>Height (Inch)</label><input id="txtHeight" class="input-fld" type="text" value="<?php print($_REQUEST['txtHeight']); ?>" name="txtHeight"></div>
							<div class="col-3 dimension"><label>Depth (Inch)</label><input id="txtDepth" class="input-fld" type="text" value="<?php print($_REQUEST['txtDepth']); ?>" name="txtDepth"></div>
						</div> 
					</div>
					<div class="input-wrp row">
						<div class="col-4">
						<strong>STEP 2:</strong> Upload Your Image
						</div>
						<div class="col-8 row">
							<div class="upld-area">
								<input id="uploadFile2" placeholder="Upload" disabled="disabled" />
								<div class="fileUpload2">
									<input id="uploadBtn2" type="file" class="upload" />
								</div>
							</div>
						</div>
						<!-- Modal HTML -->
						<div id="myModal" class="modal fade" role="dialog">
							<div class="modal-dialog">
							  <div class="modal-content">
							  
								<!-- Modal Header -->
								<div class="modal-header">
								  <h4 class="modal-title">Modal Heading</h4>
								  <button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								
								<!-- Modal body -->
								<div class="modal-body">
								  <img id="newImage" src="" style="max-width:100%;" alt="New Image">
								</div>
								
								<!-- Modal footer -->
								<div class="modal-footer">
								  <button id="btnCropModal" type="button" class="btn btn-danger" data-dismiss="modal">Crop & Continue</button>
								</div>
								
							  </div>
						</div>
					</div>
					</div>
					<div class="input-wrp row">
						<div class="col-4">
						<strong>STEP 3:</strong> Select Effect
						</div>
						<div class="col-8 row">
							<button class="input-fld col-3 muralEffect" type="button" value="Sepia" name="Sepia" data-effect="sepia"><span>Sepia</span></button>
							<button class="input-fld col-3 muralEffect" type="button" value="Grayscale" name="Grayscale" data-effect="grayscale"><span>Grayscale</span></button>
							<button style="background-color:#dc3545;color:#FFFFFF;" class="input-fld col-3 muralEffectReset" type="button" value="Reset" name="Reset"><span>Reset</span></button>
						</div> 
					</div>
					<div class="input-wrp row" style="display:none;">
						<div class="col-4">
						<strong>STEP 4:</strong> Save Image
						</div>
						<div class="col-8 row">
							<button class="col-3 save-btn" type="submit" value="Sepia" name="Sepia"><span>SAVE</span></button>
						</div>
					</div>
				</div>
				<input type="hidden" name="uploadedImage" id="uploadedImage" value="" />
				<input type="hidden" name="hdCroppedImage" id="hdCroppedImage" value="" />
				<input type="hidden" name="setAction" id="setAction" value="" />
			</form>
		</div><?php
		break;

	case "cropImage":
		?><div class="main">
			<form name="frmCropMural" id="frmCropMural" action="" method="post" autocomplete="off">
				<div class="container">
					<div class="topbar">MURAL DESIGN</div>
					<div class="img-field">
						<img id="newImage" src="<?php print($strUserImag . "?ver=" . gmdate('ymdhis')); ?>" style="max-width:100%;" alt="New Image">
					</div>
					<div class="input-wrp row" style="display:none;">
						<div class="col-4">
						<span class="title-bx"><strong>STEP1:</strong> Enter the size</span>
						</div>
						<div class="col-8 row"><?php
						
							// get dimensions
							if(!isset($_REQUEST['txtWidth'])){
								$_REQUEST['txtWidth'] = 0;
							}
							
							if(!isset($_REQUEST['txtHeight'])){
								$_REQUEST['txtHeight'] = 0;
							}
							
							if(!isset($_REQUEST['txtDepth'])){
								$_REQUEST['txtDepth'] = 0;
							}
							
							?><div class="col-3 dimension"><label>Width (px)</label><input id="txtWidth" class="input-fld" type="text" value="<?php print($_REQUEST['txtWidth']); ?>" name="txtWidth"></div>
							<div class="col-3 dimension"><label>Height (px)</label><input id="txtHeight" class="input-fld" type="text" value="<?php print($_REQUEST['txtHeight']); ?>" name="txtHeight"></div>
							<div class="col-3 dimension"><label>Depth (px)</label><input id="txtDepth" class="input-fld" type="text" value="<?php print($_REQUEST['txtDepth']); ?>" name="txtDepth"></div>
						</div> 
					</div>
					<div class="input-wrp row">
						<div class="col-4">
						<!--<strong>STEP1:</strong> Save Image-->
						</div>
						<div class="col-8 row">
							<button id="btnNext" class="col-5 save-btn" type="button" value="Sepia" name="Sepia"><span>Crop & Continue</span></button>
						</div>
					</div>
				</div>
				<input type="hidden" name="uploadedImage" id="uploadedImage" value="" />
				<input type="hidden" name="hdCroppedImage" id="hdCroppedImage" value="" />
			</form>
		</div><?php
	}

?><script src="js/jquery-3.2.1.min.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript" src="js/cropper.js"></script>
<script type="text/javascript">

	var cropper;

	<?php if(!empty($strMuralImag) && file_exists($strMuralImag)){ ?>
		$(document).ready(function() {
		   $(".thumb a").addClass("edge");
		});
		
	<?php }
	
	if($_REQUEST["setAction"] == ""){ ?>
		document.getElementById("uploadBtn2").onchange = function () {
			var fileP = this.value.substring(this.value.lastIndexOf("\\") + 1, this.value.length);
			document.getElementById("uploadFile2").value = fileP;
			
			// Show uploaded file
			var file = document.querySelector('input[type=file]').files[0];
			var reader = new FileReader();

			reader.addEventListener("load", function () {
				$("#newImage").attr("src", reader.result);
				$("#uploadedImage").val(reader.result);
				$("#setAction").val("cropImage");
				
				// Show modal
				//$("#myModal").modal("show");
				
				/* var imageWidth = $('#newImage').width();
				var imageHeight = $('#newImage').height();
				
				var strCropperWidth = Number($('#txtWidth').val()) + Number($('#txtDepth').val() * 2);
				var strCropperHeight = Number($('#txtHeight').val());
			  
				  var image = document.getElementById('newImage');
				  image.src = reader.result;
				  
				  cropper = new Cropper(image, {
					dragMode: 'move',
					viewMode: 3,
					aspectRatio: strCropperWidth / strCropperHeight,
					autoCropArea: 0.65,
					restore: false,
					guides: false,
					center: false,
					highlight: false,
					cropBoxMovable: true,
					cropBoxResizable: false,
					zoomable: false,
					toggleDragModeOnDblclick: false,
					ready: function () {
						this.cropper.setCropBoxData({
						  width: strCropperWidth,
						  height: strCropperHeight,
						});
					 },
				  }); */

				// Submit the form
				$("#frmMural").submit();
			}, false);

			if (file) {
				reader.readAsDataURL(file);
			}
		}; <?php
	}
	if(!empty($strUserImag)){
		
		// Calculate width and height for cropper js
		$strCropperWidth = 400;
		if(!empty($_REQUEST['txtWidth'])){
			$strCropperWidth = $_REQUEST['txtWidth'] + ($_REQUEST['txtDepth'] * 2);
		}
		
		$strCropperHeight = 300;
		if(!empty($_REQUEST['txtHeight'])){
			$strCropperHeight = $_REQUEST['txtHeight'];
		} ?>
		
		var cropper;
		window.addEventListener('DOMContentLoaded', function () {
		  var imageWidth = $('#newImage').width();
		  var imageHeight = $('#newImage').height();
		  
		  var image = document.getElementById('newImage');
		  cropper = new Cropper(image, {
			dragMode: 'move',
			viewMode: 3,
			aspectRatio: <?php print($strCropperWidth); ?> / <?php print($strCropperHeight); ?>,
			autoCropArea: 0.65,
			restore: false,
			guides: false,
			center: false,
			highlight: false,
			cropBoxMovable: true,
			cropBoxResizable: false,
			zoomable: false,
			toggleDragModeOnDblclick: false,
			ready: function () {
				this.cropper.setCropBoxData({
				  width: <?php print($strCropperWidth); ?>,
				  height: <?php print($strCropperHeight); ?>,
				});
			 },
		  });
		});
	<?php } ?>
	
	// Create cropped image data URL on form submition
	$("#btnNext").click(function(){
		
		
		<?php // Calculate width and height for cropper js
		$strCropperWidth = 400;
		if(!empty($_REQUEST['txtWidth'])){
			$strCropperWidth = $_REQUEST['txtWidth'] + ($_REQUEST['txtDepth'] * 2);
		}
		
		$strCropperHeight = 300;
		if(!empty($_REQUEST['txtHeight'])){
			$strCropperHeight = $_REQUEST['txtHeight'];
		} ?>
		
		// Make a canvas with cropped image area
		var canvas = cropper.getCroppedCanvas({
            width: <?php print($strCropperWidth); ?>,
			height: <?php print($strCropperHeight); ?>,
        });
		
		$("#hdCroppedImage").val(canvas.toDataURL());
		
		// Destroy cropper object
		cropper.destroy();
        cropper = null;
		
		$("#frmCropMural").submit();
		//$("#frmMural").submit();
	});
	
	// Create cropped image data URL on form submition
	$("#btnCropModal").click(function(){
		
		var strCropperWidth = Number($('#txtWidth').val()) + Number($('#txtDepth').val() * 2);
		var strCropperHeight = Number($('#txtHeight').val());
		
		// Make a canvas with cropped image area
		var canvas = cropper.getCroppedCanvas({
            width: strCropperWidth,
			height: strCropperHeight,
        });
		
		$("#hdCroppedImage").val(canvas.toDataURL());
		
		// Destroy cropper object
		cropper.destroy();
        cropper = null;
		
		//$("#frmCropMural").submit();
		$("#frmMural").submit();
	});
	
	// Apply effect to image
	$(".muralEffect").click(function(){
		$(".thumb").removeClass('sepia');
		$(".thumb").removeClass('grayscale');
		$(".thumb").addClass($(this).attr('data-effect'));
	});
	
	// Remove effect from image
	$(".muralEffectReset").click(function(){
		$(".thumb").removeClass('sepia');
		$(".thumb").removeClass('grayscale');
		$(".thumb").addClass('nofilter');
	});

</script>
</body>
</html>