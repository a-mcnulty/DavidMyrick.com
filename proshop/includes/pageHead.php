<TITLE>East of Western :: Dashboard Access</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<link type="text/css" rel="stylesheet" href="css/styles.css?V=<?= rand() ?>">
<link href="css/uploader.css" rel="stylesheet" type="text/css" />
<link type="text/css" media="screen" rel="stylesheet" href="css/colorbox.css" />
<link type="text/css" media="screen" rel="stylesheet" href="css/datepicker.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="js/hoverIntent-min.js"></script>

<!--DRAG AND DROP CODE-->
<script type="text/javascript" src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script type="text/javascript" src="js/jquery.multisortable.js"></script>

<!--COLOR PICKER-->
<script type="text/javascript" src="js/spectrum.js"></script>
<link rel='stylesheet' href='js/spectrum.css' />

<!--TINY MCE-->
<script src="tinymce/tinymce.min.js" referrerpolicy="origin"></script>

<!--AJAX/IFRAME IMAGE UPLOAD FOR DETAIL PAGE-->
<script type="text/javascript" src="js/jquery.iframe-post-form.js"></script>

<!--JQUERY FLOT SCRIPT-->
<script type="text/javascript" src="js/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/jquery.flot.time.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

		const body = document.querySelector("body");

		// AJAX GET
		function getAjax(url, success) {
			var xhr = window.XMLHttpRequest ?
				new XMLHttpRequest() :
				new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("GET", url);
			xhr.onreadystatechange = function() {
				if (xhr.readyState > 3 && xhr.status == 200) success(xhr.responseText);
			};
			xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
			xhr.send();
			return xhr;
		}

		/* 2021 ADDITION OF LIGHTBOX STYLE MEDIA LIBRARY TO ADD CONTENT */

		let overlayEl = document.querySelector("#overlay");
		if (typeof overlayEl != "undefined" && overlayEl != null) {

			const closeLB = document.querySelector("#overlay .closer");
			closeLB.addEventListener("click", function(e) {
				e.preventDefault();
				closeModal();
			});

			$('body').on("click", ".open_media", function(e) {
				e.preventDefault();
				let type = this.getAttribute("data-type");
				let id = this.getAttribute("data-id");
				let src = encodeURIComponent(this.getAttribute("data-source"));
				let field = this.getAttribute("data-field");
				getAjax("getMedia.php?type=" + type + "&id=" + id + "&src=" + src + "&field=" + field, function(data) {
					loadModal(data);
				});

			});

		}

		function closeModal() {
			body.classList.remove("overlayOn");
			const dataHold = document.querySelector("#overlay .inner");
			dataHold.innerHTML = "";
		}

		function loadModal(data) {
			const dataHold = document.querySelector("#overlay .inner");
			dataHold.innerHTML = data;
			body.classList.add("overlayOn");
			modalLinks();
			modalAssign();
		}

		function modalAssign() {

			let assignForm = document.querySelector("form.assign");
			assignForm.addEventListener("submit", function(e) {
				e.preventDefault();
				let type = document.querySelector("input#type").value;
				let id = document.querySelector("input#id").value;
				let src = document.querySelector("input#src").value;
				let field = document.querySelector("input#field").value;
				// loop through all check boxes and create itemArray
				var itemArray = [];
				$('.additem').each(function() {
					if ($(this).is(':checked')) {
						var itemID = $(this).data("id");
						itemArray.push(itemID);
					}

				});

				var data = {
					"type": type,
					"id": id,
					"field": field,
					"itemArray": itemArray
				};
				var data_encoded = JSON.stringify(data);

				$.ajax({
					type: "POST",
					url: "assignMedia.php",
					data: {
						"myContent": data_encoded
					},
					success: function(response) {
						window.location.href = src + "&status=items_have_been_added/updated";
					},
					error: function() {
						alert('failure');
					}
				});
			});

		}

		function modalLinks() {

			let modalForm = document.querySelector("form.modal");
			modalForm.addEventListener("submit", function(e) {
				e.preventDefault();
				let keywords = modalForm.querySelector("input.media_search").value;
				let type = document.querySelector("input#type").value;
				let id = document.querySelector("input#id").value;
				let src = document.querySelector("input#src").value;
				let field = document.querySelector("input#field").value;
				getAjax("getMedia.php?keyword=" + keywords + "&type=" + type + "&id=" + id + "&src=" + src + "&field=" + field, function(data) {
					loadModal(data);
				});
			});

			let modalLinks = document.querySelectorAll("a.modal");
			modalLinks.forEach(modalLink => {
				modalLink.addEventListener("click", function(e) {
					e.preventDefault();
					let href = this.getAttribute("href");
					let type = document.querySelector("input#type").value;
					let id = document.querySelector("input#id").value;
					let src = document.querySelector("input#src").value;
					getAjax(href + "&type=" + type + "&id=" + id + "&src=" + src + "&field=" + field, function(data) {
						loadModal(data);
					});
				});
			})
		}

		// set height / width of content area
		var bHeight = $(window).height();
		var bWidth = $(window).width();
		$('#contentArea').height(bHeight - 51);
		$('#contentArea').width(bWidth - 215);

		// add/edit category triggers
		$("a.Add").live({

			click: function(e) {

				e.preventDefault();

				$(this).parent().parent().find('.catformadd').toggle();

			}

		});

		$("a.Edit").live({

			click: function(e) {

				e.preventDefault();

				$(this).parent().parent().find('.catformedit').toggle();

			}

		});

		// breadcrumb

		$("A.breadcrumb").die('click').live('click', function() {

			var thePage = $(this).attr("id");
			loadPage(thePage);

		});

		var contentheight = $("#contentArea").height();
		var menuheight = $("#catList").height();

		$("#leftCol").height(contentheight);

		$("#leftCol .label").live({

			click: function(e) {

				e.preventDefault();

				if ($(this).next().is(":visible")) {

					// close any open sections
					$(".leftMenu").slideUp();

					// remove the "on" state from visible section
					$(".label").removeClass("labelOn");


				} else {

					// close any open sections
					$(".leftMenu").slideUp();

					// remove the "on" state from visible section
					$(".label").removeClass("labelOn");

					// get the id to determine what menu to open
					var theMenu = $(this).attr("id");

					// open the correct menus, add the on state to the label
					$(this).addClass("labelOn");
					$("div#" + theMenu).show();
					$("div#" + theMenu).fadeTo(300, 1);
					if (theMenu === "contentTopMenu") {
						$("#catList").slideToggle();
						$('#storeTopMenu').hide();
					} else {
						$("#storeList").slideToggle();
						$('#contentTopMenu').hide();
					}

				}

				return false;

			}

		});

		$("a.leftCol.ajax").live({

			click: function(e) {

				e.preventDefault();

				var thePage = $(this).attr("rel");

				if ($(this).parent().parent().children(".menuSub").is(":visible")) {

					$(".menuSub:visible").slideUp();

				} else {

					$(".menuSub:visible").slideUp();
					$(this).parent().parent().children(".menuSub").slideToggle();

				}

				loadPage(thePage);

				return false;

			}

		});

		$("a.subleftCol.ajax").live({

			click: function(e) {

				e.preventDefault();

				if ($(this).parent().children(".subOptions").is(":visible")) {

					$(".subOptions:visible").slideUp();

				} else {

					$(".subOptions:visible").slideUp();
					$(this).parent().children(".subOptions").slideToggle();

				}

				var thePage = $(this).attr("rel");
				loadPage(thePage);

				return false;

			}

		});

		// datepicker
		$('.customRange').click(function(e) {

			$('#dateHolder').slideToggle();

			$('#datepickerStart').datepicker({
				inline: true,
				showOtherMonths: true,
				changeMonth: true,
				changeYear: true,
				altFormat: "yy-mm-dd",
				altField: "#startDate",
				dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			});

			$('#datepickerEnd').datepicker({
				inline: true,
				showOtherMonths: true,
				changeMonth: true,
				changeYear: true,
				altFormat: "yy-mm-dd",
				altField: "#endDate",
				dayNamesMin: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			});

		});

		// in page ajax links
		$("a.ajax").click(function() {

			var thePage = $(this).attr("rel");
			loadPage(thePage);

		});

	});

	// ajax post

	function loadPage(thePage) {

		if (typeof thePage === "undefined") {

		} else {

			//alert(thePage);

			//$("body").animate({scrollTop : 0},'slow');

			$('#pageContent').html('<div style="text-align: center; width: 100%; margin-top: 25%; color: #ccc;"><img src="images/ajax-loader.gif"><p>Loading...</p></div>');

			$.ajax({
				url: thePage,
				async: true,
				cache: true,
				dataType: "html",
				success: function(data) {
					$('#pageContent').html(data);

				}
			});

		}

	}

	function loadLeft(thePage) {

		if (typeof thePage === "undefined") {

		} else {

			$.ajax({
				url: thePage,
				success: function(data) {
					$('#leftCol .inner').html(data);

				}
			});

		}

	}
</script>

<script type="text/javascript">
	// close the div in 5 secs
	window.setTimeout("closeDiv();", 4000);

	function closeDiv() {
		var Temp = document.getElementById("alert")
		if (Temp != null)
			//Temp.style.display = "none";

			// jQuery version
			$("#alert").fadeOut("slow");
	}
</script>