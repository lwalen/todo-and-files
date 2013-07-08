
/*
 * Send add item fields to database handler
 */
function addItem() {
	var description = $('#add_description').val();
	var course_id = $('#add_class').val();
	var due_date = $('#add_due_date').val();

	console.log(description);
	if (description != "") {
		$.post( "/dbi/todo/addItem.php", { description: description,
			course_id: course_id,
			due_date: due_date
		}, function() { 
			$('#items').load('/todo.php');
		});

		$('#add_item').find("input[type=text], textarea").val("");
		$('#add_item').find("select").val("");
		$('#submit').attr('disabled', 'disabled');
	}
}

$(document).ready(function() {

	/*
	 * Initialize datepicker for adding new items
	 */
	$('#add_due_date').datepicker({ showAnim: "", 
		dateFormat: "mm.dd.y",
	showOtherMonths: true,
	selectOtherMonths: true });

	/*
	 * Enable Add button only if description exists
	 */
	$('#todo').on('keyup', '#add_description', function() {
		if ($('#add_description').val().length == 0) {
			$('#submit').attr('disabled', 'disabled');
		} else {
			$('#submit').removeAttr('disabled');
		}

	});

	/*
	 * Allow pressing enter in description box to add item
	 */
	$('#todo').on('keypress', '#add_description', function(event) {
		if(event.which == 13 && $('#add_description').val().length > 0) {
			addItem();
		}
	});

	/*
	 * Show delete button when hovering item
	 */
	$('#todo').on({
		mouseenter: function() {
			var id = $(this).attr('id').match(/\d+/);
			$(this).children('.delete').addClass('delete-active');
		},
		mouseleave: function() {
			var id = $(this).attr('id').match(/\d+/);
			$(this).children('.delete').removeClass('delete-active');
		}
	}, '.item');

	/*
	 * Show calendar when hovering due date
	 */
	$('#todo').on({
		mouseenter: function() {
			var id = $(this).parent().attr('id').match(/\d+/);
			var date = $('#item_' + id + ' .due_date').text();
			$('#due_date_calendar').datepicker({ inline: true, 
				dateFormat: "m.d.y", 
				defaultDate: date });
			$(document).bind('mousemove', function(e) {
				$('#due_date_calendar').css({
					left: e.pageX - 200,
					top: e.pageY
				});
			});
			$('#due_date_calendar').show();
		},
		mouseleave: function() {
			$('#due_date_calendar').datepicker()
		$(document).unbind('mousemove');
	$('#due_date_calendar').hide();
	$('#due_date_calendar').datepicker('destroy');
		}
	}, '.due_date');

	/*
	 * Delete item when delete button clicked
	 */
	$('#todo').on('click', '.delete', function() {
		var id = $(this).parent().attr('id').match(/\d+$/)[0];
		$.post( "/dbi/todo/deleteItem.php", { id: id }, function() {
			$('#items').load('/todo.php');
		});
	});

	/*
	 * Set item to complete when checked
	 */
	$('#todo').on('click', 'input[type="checkbox"]', function() {
		var id = $(this).parent().attr('id').match(/\d+/)[0];
		var description = $('#item_' + id + ' .description').text();

		if( $(this).is(':checked') ) {
			var complete = 1;
		} else {
			var complete = 0;
		}

		$.post( "/dbi/todo/updateItem.php", { id: id,
			description: description,
			complete: complete },
			function() {
				$('#items').load('/todo.php');
			});
	});

	/*
	 * Submit item when 'Add' button clicked
	 */
	$('#todo').on('click', '#add_item #submit', addItem);

});
