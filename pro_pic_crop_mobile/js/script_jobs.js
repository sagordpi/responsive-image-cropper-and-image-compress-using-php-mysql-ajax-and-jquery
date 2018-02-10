// convert bytes into friendly format
function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB'];
    if (bytes == 0) return 'n/a';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};
// check for selected crop region
function checkForm() {
    if (parseInt($('#w').val())) return true;
    $('.error').html('Please select a crop region and then press Upload').show();
    return false;
};
// update info by cropping (onChange and onSelect events handler)
function updateInfo(e) {
    $('#x1').val(e.x);
    $('#y1').val(e.y);
    $('#x2').val(e.x2);
    $('#y2').val(e.y2);
    $('#w').val(e.w);
    $('#h').val(e.h);
};
// clear info by cropping (onRelease event handler)
function clearInfo() {
    $('.info #w').val('');
    $('.info #h').val('');
};
// Create variables (in this scope) to hold the Jcrop API and image size
var jcrop_api, boundx, boundy;

$( document ).ready( function(){
	function formUploader($element) {
		this.$loading = $('.loading');
		this.$uploadForm = $('#upload_form');
		this.$avatarSrc = $('#avatar-src');
		this.$imageFile = $('#imageFile');
		this.init();
	}

	formUploader.prototype = {
		constructor: formUploader,
		support: {
	      fileList: !!$('<input type="file">').prop('files'),
	      blobURLs: !!window.URL && URL.createObjectURL,
	      formData: !!window.FormData
	    },

	    init: function () {
	      this.support.datauri = this.support.fileList && this.support.blobURLs;

	      if (!this.support.formData) {
	        this.initIframe();
	      }
	      this.addListener();
	  	},
	  	addListener: function () {
	      this.$uploadForm.on('submit', $.proxy(this.submit, this));
	    },
	  	initIframe() {
			var target = 'upload-iframe-' + (new Date()).getTime();
			var $iframe = $('<iframe>').attr({
			    name: target,
			    src: ''
			  });
			var _this = this;

			// Ready ifrmae
			$iframe.one('load', function () {

			// respond response
			$iframe.on('load', function () {
			  var data;

			  try {
			    data = $(this).contents().find('body').text();
			  } catch (e) {
			    console.log(e.message);
			  }

			  if (data) {
			    try {
			      data = $.parseJSON(data);
			    } catch (e) {
			      console.log(e.message);
			    }

			    _this.submitDone(data);
			  } else {
			    _this.submitFail('Image upload failed!');
			  }

			  _this.submitEnd();
			  
			

			});
			});

			this.$iframe = $iframe;
			this.$uploadForm.attr('target', target).after($iframe.hide());
		},

		ajaxUpload: function () {
			var url = this.$uploadForm.attr('action');
			var data = new FormData(this.$uploadForm[0]);
			var _this = this;

			$.ajax(url, {
				type: 'post',
				data: data,
				dataType: 'json',
				processData: false,
				contentType: false,

				beforeSend: function () {
				  _this.submitStart();
				},

				success: function (data) {
				  _this.submitDone(data);
				},

				error: function (XMLHttpRequest, textStatus, errorThrown) {
				  _this.submitFail(textStatus || errorThrown);
				},

				complete: function () {
				  _this.submitEnd();
				}
			});
		},
		submitStart: function () {
		  this.$loading.fadeIn();
		},

		submitDone: function (data) {
		  console.log(data);

		  if ($.isPlainObject(data) && data.state === 200) {
		    if (data.result) {
		      this.url = data.result;

		      if (this.support.datauri || this.uploaded) {
		        this.uploaded = false;
		        this.cropDone();
		      } else {
		        this.uploaded = true;
		        this.$avatarSrc.val(this.url);
		        
		        //this.startCropper();
		      }

		      this.$imageFile.val('');
		    } else if (data.message) {
		      this.alert(data.message);
		    }
		  } else {
		    this.alert('Failed to response');
		  }
		},

		submitFail: function (msg) {
		  this.alert(msg);
		},

		submitEnd: function () {
			$('.error').hide();
		  	this.$loading.fadeOut();
		  	$('.step2').fadeOut();
			window.location.replace("list_confirm.php");
		},
		cropDone: function () {
	      this.$uploadForm.get(0).reset();
	      this.$avatarSrc.attr('src', this.url);
	      $("#img").cropper("destroy");

	      //this.stopCropper();
	      //this.$avatarModal.modal('hide');
	    },
		submit: function () {
	      /*if (!this.$avatarSrc.val() && !this.$avatarInput.val()) {
	        return false;
	      }*/

	      if (this.support.formData) {
	        this.ajaxUpload();
	        return false;
	      }
	    },

		alert: function (msg) {
			$('.error').text(msg).show();
		}

	};

	var formUp = new formUploader();
});

function fileSelectHandler() 
{
	var _MinWidth = 185;
	var _MinHeight = 175;
	var oFile, img, oReader, rFilter;
	var _URL = window.URL || window.webkitURL;
	var realImageWidth, realImageHeight;
	var minCropBoxWidth, minCropBoxHeight;
	var containerSize;
	if ($('#image_file')[0].files[0]) 
	{
		// get selected file
		oFile = $('#image_file')[0].files[0];
		// hide all errors
	    $('.error').hide();
	    // check for image type (jpg and png are allowed)
	    rFilter = /^(image\/jpeg|image\/png)$/i;
	    if (!rFilter.test(oFile.type)) {
	        $('.error').html('Please select a valid image file (jpg and png are allowed)').show();
	        return;
	    }
	    // check for file size
	    if (oFile.size > 1024 * 1024 * 8) {
	        $('.error').html('You have selected too big file, please select a one smaller image file').show();
	        return;
	    }
	    //img = new Image();
	    var imgScale = new ImageUploader(
	    	{
	    		inputElement: $('#image_file')[0],
	    		maxWidth : 1024, 
	    		maxHeight: 1024, 
	    		maxSize : 1000 * 1000,
	    		debug: true,
	    		onScale : function(imageData){
	    			$("#img").attr('src', imageData);
	    			var sResultFileSize = bytesToSize(oFile.size);
	    			$('#filedata').val(imageData);
					$('#filesize').val(sResultFileSize);
					$('#filetype').val(oFile.type);
					
					$("#img")[0].onload = function () {
				    	$('#filedim').val(this.width + ' x ' + this.height);
				    	realImageWidth = this.width;
	    				realImageHeight = this.height;
						

						$('.step2').fadeIn(500, function(){
							$("#img").cropper("destroy");
							
							$("#img").cropper({

								//minCanvasWidth		: _MinWidth,
								//minCanvasHeight		: _MinHeight,
								//minContainerWidth	: _MinWidth,
								//minContainerHeight	: _MinHeight,
								//maxContainerWidth	: _MinWidth,
								//maxContainerHeight	: _MinHeight,
								minCropBoxWidth		: 100,
								aspectRatio			: 185/175,
								//viewMode			: 3,
								zoomOnTouch			: false,
								zoomOnWheel			: false,
								dragMode			: 'none', 
								toggleDragModeOnDblclick : false,
								crop: function (e) {
									var json = [
									'{"x":' + e.x,
									'"y":' + e.y,
									'"height":' + e.height,
									'"width":' + e.width,
									'"rotate":' + e.rotate,
									'"scaleX":' + e.scaleX,
									'"scaleY":' + e.scaleY
									 + '}'
									].join();
									$('#w').val(e.width);
    							$('#h').val(e.height);
									$("#img-data").val(json);
								}
							});
							var container = $('body'),
								scrollTo = $('.ccontainer');

							// Or you can animate the scrolling:
							container.animate({
							    scrollTop: scrollTo.offset().top - container.offset().top + container.scrollTop()
							});
							  // Methods
							$('.btn-group').on('click', '[data-method]', function () {
							var $this = $(this);
							var data = $this.data();
							var $target;
							var result;
							var $image = $("#img");

							
							if ($this.prop('disabled') || $this.hasClass('disabled')) {
							  return;
							}

							if ($image.data('cropper') && data.method) {
							  data = $.extend({}, data); // Clone a new one

							  if (typeof data.target !== 'undefined') {
							    $target = $(data.target);

							    if (typeof data.option === 'undefined') {
							      try {
							        data.option = JSON.parse($target.val());
							      } catch (e) {
							        console.log(e.message);
							      }
							    }
							  }

							  if (data.method === 'rotate') {
							    $image.cropper('clear');
							  }

							  result = $image.cropper(data.method, data.option, data.secondOption);

							  if (data.method === 'rotate') {
							    $image.cropper('crop');
							  }

							  switch (data.method) {
							    case 'scaleX':
							    case 'scaleY':
							      $(this).data('option', -data.option);
							      break;

							    case 'getCroppedCanvas':
							      if (result) {

							        // Bootstrap's Modal
							        $('#getCroppedCanvasModal').modal().find('.modal-body').html(result);

							        if (!$download.hasClass('disabled')) {
							          $download.attr('href', result.toDataURL('image/jpeg'));
							        }
							      }

							      break;

							    case 'destroy':
							      if (uploadedImageURL) {
							        URL.revokeObjectURL(uploadedImageURL);
							        uploadedImageURL = '';
							        $image.attr('src', originalImageURL);
							      }

							      break;
							  }

							  if ($.isPlainObject(result) && $target) {
							    try {
							      $target.val(JSON.stringify(result));
							    } catch (e) {
							      console.log(e.message);
							    }
							  }

							}
							});
							
						});
					};
					$('#image_file').val('');
	    		}
	    	});
	    

        
	    
	}
 

}
function fileSelectHandlerOld() {
    // get selected file
    img = new Image();
    var oFile = $('#image_file')[0].files[0];
    // hide all errors
    $('.error').hide();
    // check for image type (jpg and png are allowed)
    var rFilter = /^(image\/jpeg|image\/png)$/i;
    if (!rFilter.test(oFile.type)) {
        $('.error').html('Please select a valid image file (jpg and png are allowed)').show();
        return;
    }
    // check for file size
    if (oFile.size > 1024 * 1024 * 8) {
        $('.error').html('You have selected too big file, please select a one smaller image file').show();
        return;
    }
    var _URL = window.URL || window.webkitURL;
    // test image size
    var imgSize = getImgSize(oFile)
 
    // preview element
    var oImage = document.getElementById('preview');
    // prepare HTML5 FileReader
    var oReader = new FileReader();
    oReader.onload = function(e) {
        // e.target.result contains the DataURL which we can use as a source of the image
        oImage.src = e.target.result;
        oImage.onload = function() { // onload event handler
            // display step 2
            $('.step2').fadeIn(500);
            // display some basic image info
            var sResultFileSize = bytesToSize(oFile.size);
            $('#filesize').val(sResultFileSize);
            $('#filetype').val(oFile.type);
            $('#filedim').val(oImage.naturalWidth + ' x ' + oImage.naturalHeight);
            // destroy Jcrop if it is existed
            if (typeof jcrop_api != 'undefined') {
                jcrop_api.destroy();
                jcrop_api = null;
                $('#preview').width(oImage.naturalWidth);
                $('#preview').height(oImage.naturalHeight);
            }
            //setTimeout(function() {
                // initialize Jcrop
                $('#preview').Jcrop({
                    minSize: [185, 175], // min crop size
                    //boxWidth: 600,
                    aspectRatio: 87200 / 116000, // keep aspect ratio 1:1
                    bgFade: true, // use fade effect
                    bgOpacity: .5, // fade opacity
                    onChange: updateInfo,
                    onSelect: updateInfo,
                    onRelease: clearInfo

                    //minSize: [100, 100], // min crop size
                    //boxWidth: 600,
                    //aspectRatio : 9/6, // keep aspect ratio 1:1
                    //bgFade: true, // use fade effect
                    //bgOpacity: .4, // fade opacity
                    //onChange: updateInfo,
                    //onSelect: updateInfo,
                    //onRelease: clearInfo

                }, function() {
                    // use the Jcrop API to get the real image size
                    var bounds = this.getBounds();
                    boundx = bounds[0];
                    boundy = bounds[1];
                    // Store the Jcrop API in the jcrop_api variable
                    jcrop_api = this;
                });
            //}, 3000);
        };
    };
    // read selected file as DataURL
    oReader.readAsDataURL(oFile);
    console.info(oReader);
}

function getImgSize(imgSrc) {
    var newImg = new Image();
	var height, width;
    newImg.onload = function() {
      height = newImg.height;
      width = newImg.width;
    }

    newImg.src = imgSrc; // this must be done AFTER setting onload
    return { "height" : height, "width" : width };
}