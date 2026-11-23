$(document).ready(function () {
    // Initialize DataTable for example1 if needed
    $("#example1").DataTable();

    // Handle the change event for any element with the toggle-class class
    $('#example1').on('change', '.toggle-class', function () {
        var isChecked = $(this).prop('checked'); // Check if the checkbox is checked or unchecked
        var elementType = $(this).data('type'); // Get the element type (group, user, etc.)
        var elementId = $(this).attr('data-id'); // Get the element ID
        var url; // Determine the URL and data based on the element type

        // Set the URL and data based on the element type
        switch (elementType) {
            case 'group':
                url = 'change-status/group';
                break;
            case 'user':
                url = 'change-status/user';
                break;
            default:
                return; // Exit the function if the element type is not valid
        }

        // Make the AJAX request with the configured URL and data
        $.ajax({
            type: "GET",
            dataType: "json",
            url: url,
            data: {
                'status': isChecked ? 'active' : 'inactive', // Status ('active' if checked, 'inactive' if unchecked)
                'id': elementId // Element ID
            },
            success: function (data) {
                console.log(`The status of the ${elementType} has been changed successfully.`);
                // Perform additional actions after the AJAX request succeeds
                console.log('Server response:', data);
            },
            error: function (xhr, status, error) {
                console.error(`Error changing the status of the ${elementType}: ${error}`);
                // Handle AJAX request errors if necessary
            }
        });
    });
});
