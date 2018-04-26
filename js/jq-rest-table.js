;(function ( $, window, document, undefined ) { // jshint ignore:line  
"use strict";

$.fn.resttable = function ( options ) {
	var resttable = new RestTable(options);
	this.append(resttable.table);
	resttable.load();
}

$.fn.resttable.defaults = {
	service       : "",
	fields        : ['id', 'name', 'description'],
	keyField      : 'id',
	rowIdPrefix   : "row_",
	newEntryClass : "newEntry"
};

function RestTable(opts) {
	var that = this; //private reference to current instance
	var editRecord = {};
	var tableBody = $("<tbody />");
	that.options = $.extend({}, $.fn.resttable.defaults, opts);
	that.table = $("<table />");
	that.table.append(createHeader());
	that.table.append(createFooter());
	that.table.append(tableBody);
	
	function createHeader() {
		var header = $("<thead />");
		var row = $("<tr />");
		that.options.fields.forEach(function(field){
			row.append("<th>" + field + "</th>");
		});
		header.append(row);
		return header;
	}
	
	//footer will hold 'createNew' form
	function createFooter() {
		var footer = $("<tfoot />");
		var row = $("<tr />");
		row.addClass(that.options.newEntryClass);
		that.options.fields.forEach(function(field){
			if (field===that.options.keyField) {
				var submit = $("<td />");
				submit.addClass("add");
				submit.click(function() {
					var newRecord = getNewRecord(row, that.options.fields, that.options.keyField);
					callCreateRecord(that.options.service, newRecord, function() {
						row.find("input").val("");
						that.load();
					});
				});
				row.append(submit);
			} else {
				row.append("<td><input type='text' class='" + field + "' /></td>");
			}
		});
		footer.append(row);
		return footer;
	}
	
	function getRecordKeyFromTableRow (row) {
		var keyWithPrefix = row.attr('id');
		return keyWithPrefix.replace(that.options.rowIdPrefix, '');
	}

	function getTableRow (recordKey) {
		return $("#" + that.options.rowIdPrefix + recordKey);
	}

	function getRecordFromRow (tableRow) {
		var record = {};
		that.options.fields.forEach(function(field){
			if (field===that.options.keyField) {
				record[field] = getRecordKeyFromTableRow (tableRow);
			} else {
				record[field] = tableRow.find("."+field).text();
			}
		});
		return record;
	}

	function resetListeners() {
		//edit listener (but only call service if content changed)
		var editable = tableBody.find("td[contenteditable=true]");
		editable.focus( function() {
			var row = $(this).closest("tr");
			editRecord = getRecordFromRow(row);
		});
		editable.blur( function() {
			var row = $(this).closest("tr");
			var newRecord = getRecordFromRow(row);
			if (recordChanged(editRecord, newRecord, that.options.fields)) {
				callUpdateRecord(that.options.service, newRecord);
			}
		});
		
		//delete listener
		tableBody.find("."+that.options.keyField).click(function(){
			var row = $(this).closest("tr");
			var record = {};
			record[that.options.keyField] = getRecordKeyFromTableRow(row);
			if (confirm("delete item "+record+"?")) {
				callDeleteRecord(that.options.service, record, function() {
					row.remove();
				});
			}
		});
	}

	//privileged function
	this.load = function (queryParms) {
		tableBody.empty();
		callReadRecords(that.options.service, null, function(data){
			$.each(data, function(i, obj){
				var row = $("<tr />");
				row.attr("id", that.options.rowIdPrefix + obj[that.options.keyField]);
				that.options.fields.forEach( function(field) {
					var isEditable = (field===that.options.keyField) ? false : true;
					row.append("<td class='" + field + "' contenteditable='" + isEditable + "'>" + obj[field] + "</td>");
				});
				tableBody.append(row);
			});
			resetListeners();
		});
	}

} //end constructor

RestTable.prototype = {
	options:   {},
	table:     null
	
} //end prototype



//utility functions

function getNewRecord(tableRow, fieldNames, keyField) {
	var record = {};
	fieldNames.forEach(function(field){
		if (field!==keyField) {
			record[field] = tableRow.find("."+field).val();
		}
	});
	return record;
}

function recordChanged(a, b, fieldNames) {
	return fieldNames.some(function(field) {
		return (a[field] !== b[field]);
	});
}


function callCreateRecord(serviceUrl, record, onSuccess) {
	var caller = $.post(serviceUrl, record);
	caller.done( function(data, status, jqXHR) {
		onSuccess();
	});
	caller.fail( function (jqXHR, status, err) {
		alert(status);
		console.log(jqXHR);
	});
};


function callReadRecords(serviceUrl, query, onSuccess) {
	//TODO support query parameters
	$.getJSON(serviceUrl, onSuccess);
};


function callUpdateRecord(serviceUrl, record) {
	var caller = $.ajax({
		method: 'PUT',
		url: serviceUrl,
		data: record
	});
	caller.done( function(data, status, jqXHR) {
		//alert(status);
	});
	caller.fail( function (jqXHR, status, err) {
		alert(status);
		console.log(jqXHR);
	});
};

function callDeleteRecord(serviceUrl, record, onSuccess) {
	var caller = $.ajax( {
		method: 'DELETE',
		url: serviceUrl,
		data: record
	});
	caller.done( function(data, status, jqXHR) {
		onSuccess(); 
	});
	caller.fail( function(jqXHR, status, err) {
		alert(status);
		console.log(jqXHR);
	});
};

}( jQuery, window, document ));
