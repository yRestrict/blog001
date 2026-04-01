jQuery(window).on("load", function () {
	"use strict";
	// bootstrap wysihtml5
	$(".textarea_editor").wysihtml5({
		html: true,
	});
});
jQuery(window).on("load resize", function () {
	// custom scrollbar
	$(".customscroll").mCustomScrollbar({
		theme: "dark-2",
		scrollInertia: 300,
		autoExpandScrollbar: true,
		advanced: { autoExpandHorizontalScroll: true },
	});
});
jQuery(document).ready(function () {
	"use strict";
	// Background Image
	jQuery(".bg_img").each(function (i, elem) {
		var img = jQuery(elem);
		jQuery(this).hide();
		jQuery(this)
			.parent()
			.css({
				background: "url(" + img.attr("src") + ") no-repeat center center",
			});
	});

	/*==============================================================*/
	// Image to svg convert start
	/*==============================================================*/
	jQuery("img.svg").each(function () {
		var $img = jQuery(this);
		var imgID = $img.attr("id");
		var imgClass = $img.attr("class");
		var imgURL = $img.attr("src");
		jQuery.get(
			imgURL,
			function (data) {
				var $svg = jQuery(data).find("svg");
				if (typeof imgID !== "undefined") {
					$svg = $svg.attr("id", imgID);
				}
				if (typeof imgClass !== "undefined") {
					$svg = $svg.attr("class", imgClass + " replaced-svg");
				}
				$svg = $svg.removeAttr("xmlns:a");
				if (
					!$svg.attr("viewBox") &&
					$svg.attr("height") &&
					$svg.attr("width")
				) {
					$svg.attr(
						"viewBox",
						"0 0 " + $svg.attr("height") + " " + $svg.attr("width")
					);
				}
				$img.replaceWith($svg);
			},
			"xml"
		);
	});
	/*==============================================================*/
	// Image to svg convert end
	/*==============================================================*/

	// click to scroll
	// $('.collapse-box').on('shown.bs.collapse', function () {
	// 	$(".customscroll").mCustomScrollbar("scrollTo",$(this));
	// });

	// code split
	var entityMap = {
		"&": "&amp;",
		"<": "&lt;",
		">": "&gt;",
		'"': "&quot;",
		"'": "&#39;",
		"/": "&#x2F;",
	};
	function escapeHtml(string) {
		return String(string).replace(/[&<>"'\/]/g, function (s) {
			return entityMap[s];
		});
	}
	//document.addEventListener("DOMContentLoaded", init, false);
	window.onload = function init() {
		var codeblock = document.querySelectorAll("pre code");
		if (codeblock.length) {
			for (var i = 0, len = codeblock.length; i < len; i++) {
				var dom = codeblock[i];
				var html = dom.innerHTML;
				html = escapeHtml(html);
				dom.innerHTML = html;
			}
			$("pre code").each(function (i, block) {
				hljs.highlightBlock(block);
			});
		}
	};

	// Search Icon
	$("#filter_input").on("keyup", function () {
		var value = $(this).val().toLowerCase();
		$("#filter_list .fa-hover").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		});
	});

	// custom select 2 init
	$(".custom-select2").select2();

	// Bootstrap Select
	//$('.selectpicker').selectpicker();

	// tooltip init
	$('[data-toggle="tooltip"]').tooltip();

	// popover init
	$('[data-toggle="popover"]').popover();

	// form-control on focus add class
	$(".form-control").on("focus", function () {
		$(this).parent().addClass("focus");
	});
	$(".form-control").on("focusout", function () {
		$(this).parent().removeClass("focus");
	});

	// sidebar menu icon
	$('.menu-icon, [data-toggle="left-sidebar-close"]').on("click", function () {
		//$(this).toggleClass('open');
		$("body").toggleClass("sidebar-shrink");
		$(".left-side-bar").toggleClass("open");
		$(".mobile-menu-overlay").toggleClass("show");
	});
	$('[data-toggle="header_search"]').on("click", function () {
		jQuery(".header-search").slideToggle();
	});

	var w = $(window).width();
	$(document).on("touchstart click", function (e) {
		if (
			$(e.target).parents(".left-side-bar").length == 0 &&
			!$(e.target).is(".menu-icon, .menu-icon img")
		) {
			$(".left-side-bar").removeClass("open");
			$(".menu-icon").removeClass("open");
			$(".mobile-menu-overlay").removeClass("show");
		}
	});
	// $(window).on("resize", function () {
	// 	var w = $(window).width();
	// 	if ($(window).width() > 1200) {
	// 		$(".left-side-bar").removeClass("open");
	// 		$(".menu-icon").removeClass("open");
	// 		$(".mobile-menu-overlay").removeClass("show");
	// 	}
	// });

	// sidebar menu Active Class
	$("#accordion-menu").each(function () {
		var vars = window.location.href.split("/").pop();
		$(this)
			.find('a[href="' + vars + '"]')
			.addClass("active");
	});

	// click to copy icon
	$(".fa-hover").click(function (event) {
		event.preventDefault();
		var $html = $(this).find(".icon-copy").first();
		var str = $html.prop("outerHTML");
		CopyToClipboard(str, true, "Copied");
	});
	var clipboard = new ClipboardJS(".code-copy");
	clipboard.on("success", function (e) {
		CopyToClipboard("", true, "Copied");
		e.clearSelection();
	});

	// date picker
	$(".date-picker").datepicker({
		language: "en",
		autoClose: true,
		dateFormat: "dd MM yyyy",
	});
	$(".datetimepicker").datepicker({
		timepicker: true,
		language: "en",
		autoClose: true,
		dateFormat: "dd MM yyyy",
	});
	$(".datetimepicker-range").datepicker({
		language: "en",
		range: true,
		multipleDates: true,
		multipleDatesSeparator: " - ",
	});
	$(".month-picker").datepicker({
		language: "en",
		minView: "months",
		view: "months",
		autoClose: true,
		dateFormat: "MM yyyy",
	});

	// only time picker
	$(".time-picker").timeDropper({
		mousewheel: true,
		meridians: true,
		init_animation: "dropdown",
		setCurrentTime: false,
	});
	$(".time-picker-default").timeDropper();

	// var color = $('.btn').data('color');
	// console.log(color);
	// $('.btn').style('color'+color);
	$("[data-color]").each(function () {
		$(this).css("color", $(this).attr("data-color"));
	});
	$("[data-bgcolor]").each(function () {
		$(this).css("background-color", $(this).attr("data-bgcolor"));
	});
	$("[data-border]").each(function () {
		$(this).css("border", $(this).attr("data-border"));
	});

	// ── Sidebar menu: accordion com height real ──
	(function () {
		var $menu = $("#accordion-menu");
		if (!$menu.length) return;

		// Desabilitar Bootstrap dropdown nativo no sidebar
		$menu.find(".dropdown-toggle").off("click.bs.dropdown");

		function collapseSubmenu(li) {
			var ul = li.querySelector(":scope > ul");
			if (!ul) return;
			ul.style.height = ul.scrollHeight + "px";
			// Force reflow para o browser registrar o height atual
			ul.offsetHeight;
			ul.style.height = "0";
			li.classList.remove("show");
		}

		function expandSubmenu(li) {
			var ul = li.querySelector(":scope > ul");
			if (!ul) return;
			li.classList.add("show");
			ul.style.height = ul.scrollHeight + "px";
			// Após a transição, remover height fixo para permitir resize
			function onEnd() {
				ul.removeEventListener("transitionend", onEnd);
				if (li.classList.contains("show")) {
					ul.style.height = "auto";
				}
			}
			ul.addEventListener("transitionend", onEnd);
		}

		// Bind click em cada <li> que tem submenu
		$menu.find("li").has("> ul").each(function () {
			var li = this;
			var $link = $(li).children("a").first();

			$link.on("click.sidebar", function (e) {
				e.preventDefault();
				e.stopPropagation();
				e.stopImmediatePropagation();

				var isOpen = li.classList.contains("show");

				// Fechar siblings abertos (mesmo nível)
				$(li).siblings(".show").each(function () {
					collapseSubmenu(this);
					// Fechar sub-filhos também
					$(this).find(".show").each(function () { collapseSubmenu(this); });
				});

				// Toggle
				if (isOpen) {
					// Fechar sub-filhos primeiro
					$(li).find(".show").each(function () { collapseSubmenu(this); });
					collapseSubmenu(li);
				} else {
					expandSubmenu(li);
				}

				return false;
			});
		});

		// Autostart: abrir itens ativos sem animação
		$menu.find("a.active").each(function () {
			$(this).parents("li").each(function () {
				var ul = this.querySelector(":scope > ul");
				if (ul) {
					this.classList.add("show");
					ul.style.height = "auto";
				}
			});
		});
	})();
});

// vmenuModule removido — substituído por CSS accordion acima

// copy to clipboard function
function CopyToClipboard(value, showNotification, notificationText) {
	var $temp = $("<input>");
	if (value != "") {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val(value).select();
		document.execCommand("copy");
		$temp.remove();
	}
	if (typeof showNotification === "undefined") {
		showNotification = true;
	}
	if (typeof notificationText === "undefined") {
		notificationText = "Copied to clipboard";
	}
	var notificationTag = $("div.copy-notification");
	if (showNotification && notificationTag.length == 0) {
		notificationTag = $("<div/>", {
			class: "copy-notification",
			text: notificationText,
		});
		$("body").append(notificationTag);

		notificationTag.fadeIn("slow", function () {
			setTimeout(function () {
				notificationTag.fadeOut("slow", function () {
					notificationTag.remove();
				});
			}, 1000);
		});
	}
}

// detectIE Browser
(function detectIE() {
	var ua = window.navigator.userAgent;

	var msie = ua.indexOf("MSIE ");
	if (msie > 0) {
		// IE 10 or older => return version number
		var ieV = parseInt(ua.substring(msie + 5, ua.indexOf(".", msie)), 10);
		document.querySelector("body").className += " IE";
	}

	var trident = ua.indexOf("Trident/");
	if (trident > 0) {
		// IE 11 => return version number
		var rv = ua.indexOf("rv:");
		var ieV = parseInt(ua.substring(rv + 3, ua.indexOf(".", rv)), 10);
		document.querySelector("body").className += " IE";
	}

	// other browser
	return false;
})();
