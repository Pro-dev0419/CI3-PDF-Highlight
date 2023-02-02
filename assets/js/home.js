let ele = document.getElementById("nHighlight-text");
let range = new Range();
range.setStart(ele.firstChild, 0);
range.setEnd(ele.firstChild, 2);
document.getSelection().addRange(range);
let pdfId = 0;

let ele1 = document.getElementById("pdf-viewer");
range.setStart(ele1.firstChild, 0);
range.setEnd(ele1.firstChild, 2);
document.getSelection().addRange(range);
let isDraggable = false;

$(function () {
	$.get(`/test/${0}`, function (data, status) {
		if (data != "null") {
			var hData = JSON.parse(data);
			var pdfViewer = document.getElementById("nHighlight-text");
			var t = pdfViewer.firstChild;
			for (let dat of hData) {
				nHighlightManual(t, dat.start_offset, dat.end_offset);
				t = t.nextSibling.nextSibling;
			}
		}
	});
	$("#btn-nHighlight").css({ visibility: "hidden" });
	$("#btn-nHighlight").click(function () {
		if ($(this).hasClass("nHighlight")) {
			nHighlightSelection();
		}
		$("#btn-nHighlight").css({ visibility: "hidden" });
	});
	$("#nHighlight-text").mouseup(function (e) {
		$("#btn-nHighlight").css({
			top: e.clientY + 10,
			left: e.clientX,
			visibility: "hidden",
		});
		$("#icon-nHighlight").attr("src", "./../../assets/images/pen.png");
		var txt = window.getSelection().toString();
		$("#icon-nHighlight").attr("src", "./../../assets/images/pen.png");
		if (txt) {
			$("#btn-nHighlight").css({
				top: e.clientY + 10,
				left: e.clientX,
				visibility: "visible",
			});
			$("#btn-nHighlight").addClass("nHighlight");
			$("#icon-nHighlight").attr("src", "./../../assets/images/pen.png");
		} else {
			$("#btn-nHighlight").css({
				top: e.clientY + 10,
				left: e.clientX,
				visibility: "hidden",
			});
			$("#icon-nHighlight").attr("src", "./../../assets/images/pen.png");
		}
		var arr = $(".unnHighlighted");
		for (element of arr) {
			$(element).removeClass("nHighlighted");
		}
		$(".nHighlighted").click(function (e) {
			var element = $(this);
			if (element.hasClass("nHighlighted")) {
				$("#btn-nHighlight").css({
					top: e.clientY + 10,
					left: e.clientX,
					visibility: "visible",
				});
				$("#icon-nHighlight").attr("src", "./../../assets/images/cancel.png");
				$("#btn-nHighlight").removeClass("nHighlight");
			}
			$("#btn-nHighlight").click(function () {
				element.css({ background: "transparent" });
				element.addClass("unnHighlighted");
				$("#btn-nHighlight").addClass("nHighlight");
				$("#btn-nHighlight").css({ visibility: "hidden" });
				$("#icon-nHighlight").attr("src", "./../../assets/images/pen.png");
			});
		});
	});
	$("#btn-save1").click(function (e) {
		e.stopPropagation();
		e.stopImmediatePropagation();
		var elements = $(".nHighlighted");
    console.log(elements)
		var arr = [];
		for (item of elements) {
			var start = $(item).data("start");
			var end = $(item).data("end");
			arr.push({ start: start, end: end });
		}

		$.ajax({
			type: "POST",
			url: "/test",
			data: {
				elements: arr,
				pdf_id: 0,
			},
			cache: false,
			success: function (data) {
        if(data == 'error') {
          alert("Please select the text correctly!");
        } else {
          alert("Successfully saved!");
        }
			},
		});
		return false;
	});

	$(".pdf-record").click(function (e) {
		pdfId = $(this).data("id");
		$.get(`/pdf/${pdfId}`, function (data, status) {
			let pdfData = JSON.parse(data);
			$("#pdf-viewer").html(pdfData["pdf"]);
			pdfId = pdfData["pdf_id"];
		});
		if (pdfId) {
			$.get(`/test/${pdfId}`, function (data, status) {
				var hData = JSON.parse(data);
				var pdfViewer = document.getElementById("pdf-viewer");
				var t = pdfViewer.firstChild;
				for (let dat of hData) {
					highlightManual(t, dat.start_offset, dat.end_offset);
					t = t.nextSibling.nextSibling;
				}
			});
		}
	});
	$("#select-zoom").change(function (e) {
		$("#pdf-viewer").css({
			width: $(this).val() + "%",
			"font-size": `${$(this).val() / 100}rem`,
		});
	});
	$("#btn-drag").click(function (e) {
		isDraggable = !isDraggable;
		if (isDraggable === true) {
			$(".draggable").draggable();
			$(".draggable").draggable("enable");
			$(".draggable").css({ cursor: "move" });
			$("#btn-drag").css({ border: "solid red 2px" });
		} else {
			$(".draggable").draggable({ disabled: true });
			$(".draggable").css({ cursor: "text" });
			$("#btn-drag").css({ border: "none" });
		}
	});
	$("#zoomin").click(function (e) {
		var w = $("#pdf-viewer").css("width");
		var fontSize = $("#pdf-viewer").css("font-size");
		$("#pdf-viewer").css({
			width: `calc(${w} + 20%)`,
			"font-size": `calc(${fontSize} + 2px)`,
		});
	});
	$("#zoomout").click(function (e) {
		var w = $("#pdf-viewer").css("width");
		var fontSize = $("#pdf-viewer").css("font-size");
		$("#pdf-viewer").css({
			width: `calc(${w} - 20%)`,
			"font-size": `calc(${fontSize} - 2px)`,
		});
	});
	$("#btn-highlight").css({ visibility: "hidden" });
	$("#btn-highlight").click(function () {
		if ($(this).hasClass("highlight")) {
			highlightSelection();
		}
		$("#btn-highlight").css({ visibility: "hidden" });
	});
	$("#pdf-viewer").mouseup(function (e) {
		$("#btn-highlight").css({
			top: e.clientY + 10,
			left: e.clientX,
			visibility: "hidden",
		});
		$("#icon-highlight").attr("src", "./../../assets/images/pen.png");
		var txt = window.getSelection().toString();
		$("#icon-highlight").attr("src", "./../../assets/images/pen.png");
		if (txt) {
			$("#btn-highlight").css({
				top: e.clientY + 10,
				left: e.clientX,
				visibility: "visible",
			});
			$("#btn-highlight").addClass("highlight");
			$("#icon-highlight").attr("src", "./../../assets/images/pen.png");
		} else {
			$("#btn-highlight").css({
				top: e.clientY + 10,
				left: e.clientX,
				visibility: "hidden",
			});
			$("#icon-highlight").attr("src", "./../../assets/images/pen.png");
		}
		var arr = $(".unhighlighted");
		for (element of arr) {
			$(element).removeClass("highlighted");
		}
		$(".highlighted").click(function (e) {
			var element = $(this);
			if (element.hasClass("highlighted")) {
				$("#btn-highlight").css({
					top: e.clientY + 10,
					left: e.clientX,
					visibility: "visible",
				});
				$("#icon-highlight").attr("src", "./../../assets/images/cancel.png");
				$("#btn-highlight").removeClass("highlight");
			}
			$("#btn-highlight").click(function () {
				element.css({ background: "transparent" });
				element.addClass("unhighlighted");
				$("#btn-highlight").addClass("highlight");
				$("#btn-highlight").css({ visibility: "hidden" });
				$("#icon-highlight").attr("src", "./../../assets/images/pen.png");
			});
		});
	});
	$("#btn-save").click(function (e) {
		e.stopPropagation();
		e.stopImmediatePropagation();
		var elements = $(".highlighted");
		var arr = [];
		for (item of elements) {
			var start = $(item).data("start");
			var end = $(item).data("end");
			arr.push({ start: start, end: end });
		}

		$.ajax({
			type: "POST",
			url: "/test",
			data: {
				elements: $('#pdf-viewer').html(),
				pdf_id: pdfId,
			},
			cache: false,
			success: function (data) {
        if(data == 'error') {
          alert("Please select the text correctly!");
        } else {
          alert("Successfully saved!");
        }
			},
		});
		return false;
	});
});
function nHighlightRange(range, startOffset, endOffset) {
	var newNode = document.createElement("span");
	newNode.setAttribute("style", "background-color: yellow; display: inline;");
	newNode.setAttribute("class", "nHighlighted");
	newNode.setAttribute("data-start", startOffset);
	newNode.setAttribute("data-end", endOffset);
	range.surroundContents(newNode);
}
function getSafeRanges(dangerous) {
	var a = dangerous.commonAncestorContainer;
	var s = new Array(0),
		rs = new Array(0);
	if (dangerous.startContainer != a)
		for (var i = dangerous.startContainer; i != a; i = i.parentNode) s.push(i);
	if (0 < s.length)
		for (var i = 0; i < s.length; i++) {
			var xs = document.createRange();
			if (i) {
				xs.setStartAfter(s[i - 1]);
				xs.setEndAfter(s[i].lastChild);
			} else {
				xs.setStart(s[i], dangerous.startOffset);
				xs.setEndAfter(s[i].nodeType == Node.TEXT_NODE ? s[i] : s[i].lastChild);
			}
			rs.push(xs);
		}

	var e = new Array(0),
		re = new Array(0);
	if (dangerous.endContainer != a)
		for (var i = dangerous.endContainer; i != a; i = i.parentNode) e.push(i);
	if (0 < e.length)
		for (var i = 0; i < e.length; i++) {
			var xe = document.createRange();
			if (i) {
				xe.setStartBefore(e[i].firstChild);
				xe.setEndBefore(e[i - 1]);
			} else {
				xe.setStartBefore(
					e[i].nodeType == Node.TEXT_NODE ? e[i] : e[i].firstChild
				);
				xe.setEnd(e[i], dangerous.endOffset);
			}
			re.unshift(xe);
		}

	if (0 < s.length && 0 < e.length) {
		var xm = document.createRange();
		xm.setStartAfter(s[s.length - 1]);
		xm.setEndBefore(e[e.length - 1]);
	} else {
		return [dangerous];
	}

	rs.push(xm);
	response = rs.concat(re);

	return response;
}
function nHighlightSelection() {
	var userSelection = window.getSelection().getRangeAt(0);
	var safeRanges = getSafeRanges(userSelection);
	for (var i = 0; i < safeRanges.length; i++) {
		nHighlightRange(
			safeRanges[i],
			userSelection.startOffset,
			userSelection.endOffset
		);
	}
}
function nHighlightManual(node, start, end) {
	var userSelection = window.getSelection().getRangeAt(0);
	var safeRanges = getSafeRanges(userSelection);
	userSelection.setStart(node, start);
	userSelection.setEnd(node, end);

	for (var i = 0; i < safeRanges.length; i++) {
		nHighlightRange(safeRanges[i], start, end);
	}
}
function highlightRange(range, startOffset, endOffset) {
	var newNode = document.createElement("span");
	newNode.setAttribute("style", "background-color: yellow; display: inline;");
	newNode.setAttribute("class", "highlighted");
	newNode.setAttribute("data-start", startOffset);
	newNode.setAttribute("data-end", endOffset);
	range.surroundContents(newNode);
}
function getSafeRanges(dangerous) {
	var a = dangerous.commonAncestorContainer;
	var s = new Array(0),
		rs = new Array(0);
	if (dangerous.startContainer != a)
		for (var i = dangerous.startContainer; i != a; i = i.parentNode) s.push(i);
	if (0 < s.length)
		for (var i = 0; i < s.length; i++) {
			var xs = document.createRange();
			if (i) {
				xs.setStartAfter(s[i - 1]);
				xs.setEndAfter(s[i].lastChild);
			} else {
				xs.setStart(s[i], dangerous.startOffset);
				xs.setEndAfter(s[i].nodeType == Node.TEXT_NODE ? s[i] : s[i].lastChild);
			}
			rs.push(xs);
		}

	var e = new Array(0),
		re = new Array(0);
	if (dangerous.endContainer != a)
		for (var i = dangerous.endContainer; i != a; i = i.parentNode) e.push(i);
	if (0 < e.length)
		for (var i = 0; i < e.length; i++) {
			var xe = document.createRange();
			if (i) {
				xe.setStartBefore(e[i].firstChild);
				xe.setEndBefore(e[i - 1]);
			} else {
				xe.setStartBefore(
					e[i].nodeType == Node.TEXT_NODE ? e[i] : e[i].firstChild
				);
				xe.setEnd(e[i], dangerous.endOffset);
			}
			re.unshift(xe);
		}

	if (0 < s.length && 0 < e.length) {
		var xm = document.createRange();
		xm.setStartAfter(s[s.length - 1]);
		xm.setEndBefore(e[e.length - 1]);
	} else {
		return [dangerous];
	}

	rs.push(xm);
	response = rs.concat(re);

	return response;
}
function highlightSelection() {
	var userSelection = window.getSelection().getRangeAt(0);
	var safeRanges = getSafeRanges(userSelection);
	for (var i = 0; i < safeRanges.length; i++) {
		highlightRange(
			safeRanges[i],
			userSelection.startOffset,
			userSelection.endOffset
		);
	}
}
function highlightManual(node, start, end) {
	var userSelection = window.getSelection().getRangeAt(0);
	var safeRanges = getSafeRanges(userSelection);
	userSelection.setStart(node, start);
	userSelection.setEnd(node, end);

	for (var i = 0; i < safeRanges.length; i++) {
		highlightRange(safeRanges[i], start, end);
	}
}
