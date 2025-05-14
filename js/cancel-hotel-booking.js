document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.cancel-hotel-booking-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch(cancelHotelBookingAjax.ajax_url, {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Booking cancelled successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.data);
                }
            })
            .catch(error => {
                alert('AJAX error: ' + error.message);
            });
        });
    });
});
