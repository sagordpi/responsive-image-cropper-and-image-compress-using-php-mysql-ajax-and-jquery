

 <link rel="stylesheet" href="assets/css/cropper.min.css" type="text/css" />
 <link rel="stylesheet" href="assets/css/font-awesome.min.css" type="text/css" />
 
     
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="assets/js/cropper.min.js"></script>
    <script src="assets/js/exif.js"></script>
    <script src="assets/js/ImageUploader.js"></script>
    <script src="pro_pic_crop_mobile/js/script.js"></script>
    <script type="text/javascript">
      $(window).on('load', function() {
        $(".loader").fadeOut("slow");
      }
      );
    </script>

  <style>
      .face {
        position: absolute;
        border: 2px solid #FFF;
      }
      .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('images/page-loader.gif') 50% 50% no-repeat rgb(249,249,249);
      }
      .bheader {
        background-color: #DDDDDD;
        border-radius: 10px 10px 0 0;
        padding: 10px 0;
        text-align: center;
      }
      .bbody {
        color: #000;
        overflow: hidden;
        padding-bottom: 20px;
        text-align: center;
        background: -moz-linear-gradient(#ffffff, #f2f2f2);
        background: -ms-linear-gradient(#ffffff, #f2f2f2);
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffffff), color-stop(100%, #f2f2f2));
        background: -webkit-linear-gradient(#ffffff, #f2f2f2);
        background: -o-linear-gradient(#ffffff, #f2f2f2);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#f2f2f2');
        -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#f2f2f2')";
        background: linear-gradient(#ffffff, #f2f2f2);
      }
      .bbody h2, .info, .error {
        margin: 10px 0;
      }
      .step2, .error {
        display: none;
      }
      .error {
        font-size: 18px;
        font-weight: bold;
        color: red;
      }
      .info {
        font-size: 14px;
      }
      label {
        margin: 0 5px;
      }
      input {
        border: 1px solid #CCCCCC;
        border-radius: 10px;
        padding: 4px 8px;
        text-align: center;
        width: 400px;
        background:#6fb502;
        color:#FFFFFF;
        cursor:pointer;
      }
      .jcrop-holder {
        display: inline-block;
      }
      input[type=submit] {
        width: 400px;
        background:#6fb502;
        color:#FFFFFF;
        cursor:pointer;
        border: 1px solid #bbb;
        border-radius: 3px;
        -webkit-box-shadow: inset 0 0 1px 1px #f6f6f6;
        box-shadow: inset 0 0 1px 1px #f6f6f6;
        font: bold 12px/1 "helvetica neue", helvetica, arial, sans-serif;
        padding: 15px;
        text-align: center;
        text-shadow: 0 1px 0 #fff;
      }
      input[type=submit]:hover {
        background:#6fb502;
        color:#FFFFFF;
        -webkit-box-shadow: inset 0 0 1px 1px #eaeaea;
        box-shadow: inset 0 0 1px 1px #eaeaea;
        color: #222;
        cursor: pointer;
      }
      input[type=submit]:active {
        background: #d0d0d0;
        -webkit-box-shadow: inset 0 0 1px 1px #e3e3e3;
        box-shadow: inset 0 0 1px 1px #e3e3e3;
        color: #000;
      }
      input[type="file"] {
        display: none;
      }
    .image_file {
     background: transparent;
    color: #171616;
    font-size: 18px;
    width: initial;
    padding: 60px 200px 60px 200px;
    -webkit-border-radius: 10px;
    border-radius: 0px;
    border: 4px dashed #B7894B;
    cursor: pointer;
    margin-top: 15px;
}

	  .pho_up_ins {
    background:#9f9f9f;
    color: #fff;
    font-size: 14px;
    padding: 12px;
    -webkit-border-radius: 10px;
    border-radius: 0px;
    border: 1px solid #cfcfcf;
    margin: auto;
    line-height: 32px;
}
      .btn-group .btn span {
        font-weight: normal;
      }
      .loading {
          display: none;
          position: absolute;
          top: 0;
          right: 0;
          bottom: 0;
          left: 0;
          background: #fff url("assets/images/loading.gif") no-repeat center center;
          opacity: .75;
          filter: alpha(opacity=75);
          z-index: 20140628;
        }
      @media only screen and (max-width: 720px) {
        .bbody {
            text-align: center;
        }
        input[type=submit] {
            width: 50%;
            margin: 0 auto;

        }
        .image_file {
            width: initial;
        }
        .pho_up_ins {
            width: initial;
        }
    }
    </style>

<section id="contact">
	<div class="upload_warper">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
						<div class="contact_head profile_head_up">
							<h3>Add a Profile Photo</h3>
						</div>
					<div class="contact_area update_area">
						<div class="upload_form">
						<div class="up_hints">
							
						</div>
						
							
						
								
								
<form id="upload_form" name="upload_form" class="saz_form" method="post" action="crop.php" enctype="multipart/form-data" onSubmit="return checkForm()">
             
              <input type="hidden" id="x1" name="x1" />
              <input type="hidden" id="y1" name="y1" />
              <input type="hidden" id="x2" name="x2" />
              <input type="hidden" id="y2" name="y2" />
              <h4 class="sag_align">Step 1: Please select image file </h4>
              <div>
                <div style="margin: 0 auto;display: block;text-align: center;" class="saz_button_area">
                  <label for="image_file" class="image_file">
                    <i class="fa fa-picture-o">
                    </i> Choose File
                  </label>
                  <input type="file" name="image_file" id="image_file" onChange="fileSelectHandler()" />
                </div>
              </div>
              <div class="error">
              </div>
              <div class="step2 saz_step_2">
                <h4>Step 2: Please select a crop region</h4>
                <br/>
                <div style="width: 100%; margin: 0 auto;">
                  <p class="help pho_up_ins">
                    <strong>
                      আপনার ফটোটি আপলোড হওয়ার পর কিছুক্ষণ অপেক্ষা করুন । এবার আপনার মাউস দিয়ে অথবা মোবাইল ব্যবহার কারিদের ক্ষেত্রে হাতের আঙ্গুল দিয়ে ড্রাগ করুন ফটোটির উপরে । 
                      ক্রপের বন্ধনীর ভিতরে আপনার প্রদর্শিত ফটোর কাঙ্খিত অংশুটুকু রাখুন ।
                      এবার আপলোড এ ক্লিক করুন । 
                    </strong>
                  </p>
                </div>
                
                <div class="ccontainer">
                    <div>
                      <img id="img" src="" alt="" />
                    </div>
                </div>
                <br/>
                <div class="btn-group">
                  <button type="button" class="btn btn-primary up_image_sn_btn" data-method="zoom" data-option="0.1" title="Zoom In">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;zoom&quot;, 0.1)">
                      <span class="fa fa-search-plus"></span>
                    </span>
                  </button>
                  <button type="button" class="btn btn-primary up_image_sn_btn" data-method="zoom" data-option="-0.1" title="Zoom Out">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;zoom&quot;, -0.1)">
                      <span class="fa fa-search-minus"></span>
                    </span>
                  </button>
               
                  <button type="button" class="btn btn-primary up_image_sn_btn" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;move&quot;, -10, 0)">
                      <span class="fa fa-arrow-left"></span>
                    </span>
                  </button>
                  <button type="button" class="btn btn-primary up_image_sn_btn" data-method="move" data-option="10" data-second-option="0" title="Move Right">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;move&quot;, 10, 0)">

                      <span class="fa fa-arrow-right"></span>
                    </span>
                  </button>
                  <button type="button" class="btn btn-primary up_image_sn_btn" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;move&quot;, 0, -10)">
                      <span class="fa fa-arrow-up"></span>
                    </span>
                  </button>
                  <button type="button" class="btn btn-primary up_image_sn_btn" data-method="move" data-option="0" data-second-option="10" title="Move Down">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;move&quot;, 0, 10)">
                      <span class="fa fa-arrow-down"></span>
                    </span>
                  </button>
                
                  <button type="button" class="btn btn-primary up_image_sn_btn" data-method="rotate" data-option="-90" title="Rotate Left">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;rotate&quot;, -90)">
                      <span class="fa fa-rotate-left"></span>
                    </span>
                  </button>
                  <button type="button" class="btn btn-primary up_image_sn_btn" data-method="rotate" data-option="90" title="Rotate Right">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;rotate&quot;, 90)">
                      <span class="fa fa-rotate-right"></span>
                    </span>
                  </button>
                
                  <button type="button" class="btn btn-primary up_image_sn_btn" data-method="scaleX" data-option="-1" title="Flip Horizontal">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;scaleX&quot;, -1)">
                      <span class="fa fa-arrows-h"></span>
                    </span>
                  </button>
                  <button type="button" class="btn btn-primary up_image_sn_btn" data-method="scaleY" data-option="-1" title="Flip Vertical">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="$().cropper(&quot;scaleY&quot;, -1)">
                      <span class="fa fa-arrows-v"></span>
                    </span>
                  </button>
                </div>

                <br/>
                <div class="infos">
                <input type="hidden" id="filedata" name="filedata" />
                  <input type="hidden" id="filesize" name="filesize" />
                  <input type="hidden" id="filetype" name="filetype" />
                  <input type="hidden" id="filedim" name="filedim" />
                  <input type="hidden" id="w" name="w" />
                  <input type="hidden" id="h" name="h" />
                  <input type="hidden" name="img_data" id="img-data">
                  <br/>
                  <br/>
				  <div class="up_image_sn">
                  <button type="submit" class="btn btn-primary up_image_sn_btn" data-method="scaleY" data-option="-1" title="Flip Vertical">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="">Upload
                      <span class="fa fa fa-upload"></span>
                    </span>
                  </button>
                  </div>
                </div>
              </div>
            <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>
            </form>

							
						</div>
					</div>
				</div>
			</div>	
		</div>
	</div>
</section>


