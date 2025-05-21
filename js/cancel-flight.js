document.addEventListener('DOMContentLoaded', function () {
    const flightCancelForms = document.querySelectorAll('.cancel-flight-booking-form');

    flightCancelForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch(flightCancelAjax.ajax_url, {
                method: "POST",
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Flight cancelled successfully!');
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
