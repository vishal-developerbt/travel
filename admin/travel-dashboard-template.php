<?php
// travel-dashboard-template.php
 $data =getTravelDashboardData();
  $price_currency = $data['price_currency'];
 //echo "<pre>"; print_r($data); die();
?><style type="text/css">
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  }
  .bookings-section {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    max-width: 900px;
    margin: auto;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  }
.right-panel .card-icon img {
    width: 30px;
    opacity: 0.8;
}
  .bookings-header {
    font-size: 24px;
    margin-bottom: 20px;
  }

  .filters {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 20px;
  }

  .bookings-section select, input[type="date"] {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  th, td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    text-align: left;
  }

  .passenger-avatars {
    display: flex;
    gap: 5px;
  }

  .avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .blue { background: #007bff; }
  .red { background: #dc3545; }
  .yellow { background: #ffc107; color: #000; }
  .green { background: #28a745; }
  body {
    background-color: #f8fafc;
    color: #333;
    line-height: 1.5;
  }
  
  .dashboard {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
  }
  
  /* Left Panel */
  .dashboard .left-panel  {
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    padding: 20px;
  }
  
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }
  
  .dashboard-title {
    font-size: 24px;
    font-weight: 700;
    color: #1a202c;
  }
  
  .add-widget {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #4299e1;
    font-size: 14px;
    cursor: pointer;
  }
  /* Sales Performance Section */
  .sales-performance {
    margin-bottom: 30px;
  }
  
  .sales-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
  }
  
  .dashboard .sales-title{
    font-size: 18px;
    font-weight: 600;
    color: #1a202c;
  }
  
  .dashboard .sales-subtitle {
    font-size: 14px;
    color: #718096;
    margin-top: 4px;
  }
 
  
  .chart {
    height: 150px;
    background: linear-gradient(to bottom, rgba(90, 103, 216, 0.1), rgba(90, 103, 216, 0.02));
    border-radius: 10px;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
  }
  .stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
  }
  
  .sales-performance .stat-box {
    background-color: #f7fafc;
    border-radius: 10px;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 15px;
  }
  
  .sales-performance .stat-icon {
    width: 48px;
    height: 48px;
    background-color: #ebf8ff;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4299e1;
  }
  
  .stat-content .value {
    font-size: 20px;
    font-weight: 700;
    color: #1a202c;
  }
  
  .stat-content .label {
    font-size: 14px;
    color: #718096;
  }
  
  /* Bookings Section */
  .bookings-section {
    margin-top: 30px;
  }
  
  .bookings-header {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 20px;
    color: #1a202c;
  }
  
  .filters {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
  }
  
  .filter-button {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 16px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background-color: white;
    color: #1a202c;
    cursor: pointer;
    font-size: 14px;
    min-width: 150px;
  }
  
  .filter-button:hover {
    border-color: #cbd5e0;
  }
  
  .filter-button svg {
    margin-left: 10px;
  }
  
  .bookings-table {
    width: 100%;
    border-collapse: collapse;
  }
  
  .bookings-table th {
    text-align: left;
    padding: 12px 0;
    border-bottom: 1px solid #e2e8f0;
    color: #718096;
    font-weight: 500;
    font-size: 14px;
  }
  
  .bookings-table td {
    padding: 16px 0;
    border-bottom: 1px solid #e2e8f0;
  }
  
  .flight-icon {
    width: 40px;
    height: 40px;
    background-color: #ebf4ff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4299e1;
  }
  
  .flight-info {
    display: flex;
    align-items: center;
    gap: 16px;
  }
  
  .flight-times {
    font-weight: 600;
    margin-bottom: 4px;
    font-size: 16px;
  }
  
  .flight-details {
    color: #718096;
    font-size: 14px;
  }
  
  .flight-details span {
    margin: 0 6px;
  }
  
  .passenger-avatars {
    display: flex;
  }
  
  .avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    margin-left: -5px;
    border: 2px solid white;
  }
  
  .avatar:first-child {
    margin-left: 0;
  }
  
  .avatar.blue {
    background-color: #4299e1;
  }
  
  .avatar.red {
    background-color: #f56565;
  }
  
  .avatar.yellow {
    background-color: #ecc94b;
  }
  
  .avatar.green {
    background-color: #48bb78;
  }
  
  /* Right Panel */
  .right-panel {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  
  .illustration {
    background-color: white;
    border-radius: 12px;
    height: 200px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    overflow: hidden;
  }
  
  .stats-cards {
    display: flex;
    flex-direction: column;
    gap: 15px;
  }
  
  .stat-card {
    background-color: white;
    border-radius: 12px;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  }
  
  .right-panel .stat-card.blue {
    background-color: #4299e1;
    color: white;
    opacity: 0.8;
  }
  
  .right-panel .stat-card.purple {
    background-color: #e890ad;
    color: white;
    opacity: 0.8;
  }
  
  .right-panel  .stat-card.dark {
    background-color: #1a202c;
    color: white;
    opacity: 0.7;
  }
  
  .card-content .label {
    font-size: 14px;
    margin-bottom: 5px;
  }
  
  .card-content .value {
    font-size: 24px;
    font-weight: 700;
  }
  
  .card-content .trend {
    font-size: 12px;
    display: flex;
    align-items: center;
    margin-top: 5px;
  }
  
  .card-content .trend.up {
    color: #9ae6b4;
  }
  
  .card-content .trend.down {
    color: #fbb6ce;
  }
  
  .card-icon {
    width: 40px;
    height: 40px;
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  /* Activity Section */
  .activity-section {
    background-color: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  
  }
  
  .activity-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
  }
  
  .activity-title {
    font-size: 20px;
    font-weight: 700;
    color: #1a202c;
  }
 .bookings-table-sections-main {
    max-height: 390px;
    overflow-y: auto !important; /* auto is usually better than scroll */
    
    /* Scrollbar Styling */
    scrollbar-width: thin; /* For Firefox */
    scrollbar-color: #ccc transparent; /* For Firefox */
}

/* For Webkit Browsers (Chrome, Edge, Safari) */
.bookings-table-sections-main::-webkit-scrollbar {
    width: 6px; /* Make scrollbar very thin */
}

.bookings-table-sections-main::-webkit-scrollbar-track {
    background: transparent; /* No track background */
}

.bookings-table-sections-main::-webkit-scrollbar-thumb {
    background-color: #ccc; /* Color of the scrollbar */
    border-radius: 3px; /* Rounded corners */
}
</style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Wait until the DOM is fully loaded before executing the chart
    document.addEventListener('DOMContentLoaded', function () {
    
      const ctx = document.getElementById('combinedChart').getContext('2d');
      
      // Create gradient for sales
      const gradientSales = ctx.createLinearGradient(0, 0, 0, 250);
      gradientSales.addColorStop(0, 'rgba(160, 99, 245, 0.5)');
      gradientSales.addColorStop(1, 'rgba(160, 99, 245, 0.05)');
    
      // Create gradient for revenue
      const gradientRevenue = ctx.createLinearGradient(0, 0, 0, 250);
      gradientRevenue.addColorStop(0, 'rgba(245, 99, 160, 0.5)');
      gradientRevenue.addColorStop(1, 'rgba(245, 99, 160, 0.05)');
    
      // Initialize the chart
      new Chart(ctx, {
        type: 'line',  // Line chart type
        data: {
          labels: <?php echo json_encode($monthdata['labels']); ?>, // X-axis labels
          datasets: [
            {
              label: 'Hotel', // Sales dataset
              data: <?php echo json_encode($monthdata['hotel_data']); ?>, // Sales data
              borderColor: '#a063f5',
              backgroundColor: gradientSales, // Sales gradient
              tension: 0.4, // Smooth line
              fill: true, // Fill under the line
              pointBackgroundColor: '#a063f5',
              pointBorderColor: '#fff',
              pointRadius: 5,
              pointHoverRadius: 6
            },
            {
              label: 'Flight', // Revenue dataset
              data: <?php echo json_encode($monthdata['flight_data']); ?>, // Revenue data
              borderColor: '#f599a0',
              backgroundColor: gradientRevenue, // Revenue gradient
              tension: 0.4, // Smooth line
              fill: true, // Fill under the line
              pointBackgroundColor: '#f599a0',
              pointBorderColor: '#fff',
              pointRadius: 5,
              pointHoverRadius: 6
            }
          ]
        },
        options: {
          plugins: {
            legend: {
              display: true, // Show the legend
              position: 'top' // Place the legend at the top
            }
          },
          scales: {
            x: {
              display: true, // Show x-axis
            },
            y: {
              display: true, // Show y-axis
              ticks: {
                callback: function(value) {
                  return value / 1000 + 'k'; // Format y-axis labels
                },
                color: '#999',
                font: {
                  size: 12
                }
              },
              grid: {
                color: '#eee',
                drawTicks: false
              }
            }
          }
        }
      });
    
    });
    </script>
 
     <script>
document.addEventListener("DOMContentLoaded", function () {
    // Get the dynamic data from PHP
    var hotelData = <?php echo json_encode($monthdata['hotel_current_week']); ?>;
    var flightData = <?php echo json_encode($monthdata['flight_current_week']); ?>;
    
    // Labels for the days of the week (you can modify this if needed)
    var labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    // Create the chart with dynamic data
    const ctx = document.getElementById('weekdayBarChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,  // Labels for the x-axis (Days of the week)
            datasets: [
                {
                    label: 'This Week Hotel',
                    data: hotelData,  // Dynamic hotel booking data
                    backgroundColor: '#a063f5',  // Color for the hotel bookings
                    borderRadius: 6,
                    barThickness: 30
                },
                {
                    label: 'This Week Flight',
                    data: flightData,  // Dynamic flight booking data
                    backgroundColor: '#f59e63',  // Color for the flight bookings
                    borderRadius: 6,
                    barThickness: 30
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,  // Adjust the max value depending on your data
                    ticks: {
                        callback: function(value) {
                            return value + '%';  // Format Y-axis as percentages
                        },
                        color: '#666'
                    },
                    grid: {
                        color: '#eee'
                    }
                },
                x: {
                    ticks: {
                        color: '#666'
                    },
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: '#666'
                    }
                }
            }
        }
    });
});
</script>
<script>
    // Convert hotel and flight results to booking format
    const hotelbooking = <?php echo json_encode($hotelResults); ?>;
    const flightbooking = <?php echo json_encode($flightResults); ?>;
    const flightWithHotelbooking = <?php echo json_encode($flightWithHotelResults); ?>;

    // Convert hotel data to booking format
    function convertToHotelBookingFormat(hotel) {
        return {
            id: hotel.id,
            date: hotel.checkin, // Using checkin as the date
            type: 'hotel',
            destination: hotel.location,
            time: `${hotel.checkin} - ${hotel.checkout}`,
            people: [hotel.firstName.charAt(0).toUpperCase() + hotel.lastName.charAt(0).toUpperCase()],
            colors: ["red"],
        };
    }

    // Convert flight data to booking format
    function convertToFlightBookingFormat(flight) {
        return {
            id: flight.id,
            date: flight.departure_date, // Using departure_date as the date
            type: 'flight',
            destination: `${flight.destination_from} → ${flight.destination_to}`,
            time: `${flight.departure_date}`,
            people: [flight.first_name.charAt(0).toUpperCase() + flight.last_name.charAt(0).toUpperCase()],
            colors: ["blue"],
        };
    }

    // Convert flight with hotel data to booking format
    function convertToFlightWithHotelBookingFormat(booking) {
        return {
            id: booking.id,
            date: booking.departure_date || booking.checkin,
            type: 'flight_with_hotel',
            destination: booking.location || `${booking.destination_from} → ${booking.destination_to}`,
            time: booking.departure_date || `${booking.checkin} - ${booking.checkout}`,
            people: [(booking.first_name || '').charAt(0).toUpperCase() + (booking.last_name || '').charAt(0).toUpperCase()],
            colors: ["purple"],
        };
    }

    const hotelBookings = hotelbooking.map(convertToHotelBookingFormat);
    const flightBookings = flightbooking.map(convertToFlightBookingFormat);
    const flightWithHotelBookings = flightWithHotelbooking.map(convertToFlightWithHotelBookingFormat);

    const bookings = [...hotelBookings, ...flightBookings, ...flightWithHotelBookings];

    // Elements
    const typeFilter = document.getElementById("bookingTypeFilter");
    const startDateInput = document.getElementById("startDate");
    const endDateInput = document.getElementById("endDate");
    const tableBody = document.getElementById("bookingTableBody");

    // Render the table with booking data
    function renderTable(data) {
        tableBody.innerHTML = "";
        if (data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="3">No bookings found.</td></tr>`;
            return;
        }

        data.forEach(booking => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>
                    <strong>${booking.time}</strong><br/>
                    <small>${booking.destination}</small>
                </td>
                <td>${new Date(booking.date).toDateString()}</td>
                <td>
                    <div class="passenger-avatars">
                        ${booking.people.map((p, i) => `<div class="avatar ${booking.colors[i]}">${p}</div>`).join('')}
                    </div>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    // Filter the bookings based on selected filters
    function filterTable() {
        const selectedType = typeFilter.value;
        const start = startDateInput.value ? new Date(startDateInput.value) : null;
        const end = endDateInput.value ? new Date(endDateInput.value) : null;

        const filtered = bookings.filter(booking => {
            const bookingDate = new Date(booking.date);

            let matchType = true;
            if (selectedType === 'hotel') {
                matchType = booking.type === 'hotel';
            } else if (selectedType === 'flight') {
                matchType = booking.type === 'flight';
            } else if (selectedType === 'flight_with_hotel') {
                matchType = booking.type === 'flight_with_hotel';
            }

            const matchRange = (!start || bookingDate >= start) && (!end || bookingDate <= end);

            return matchType && matchRange;
        });

        renderTable(filtered);
    }

    // Event listeners for the filters
    typeFilter.addEventListener("change", filterTable);
    startDateInput.addEventListener("change", filterTable);
    endDateInput.addEventListener("change", filterTable);

    // Initial render with all data
    renderTable(bookings);

</script>


<div class="dashboard">
    <!-- Left Panel -->
    <div class="left-panel">
      <div class="header">
        <h1 class="dashboard-title">Dashboard</h1>
      </div>
      <div class="sales-performance">
        <div class="sales-header">
          <div>
            <div class="sales-title">Travel Sales Performance</div>
          </div>
        </div>
        <div class="sales-performance">
          <div class="sales-card">
            <div class="sales-subtitle">Sales and Revenue across months</div>
            <canvas id="combinedChart" height="160"></canvas>
          </div>
        </div>
        <div class="stats">
          <div class="stat-box">
            <div class="stat-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
              </svg>
            </div>
            <div class="stat-content">
              <div class="value"><?php echo $price_currency ;?> <?php echo $data['hotelTotalPrice']?></div>
              <div class="label">Hotel Total Revenue</div>
            </div>
          </div>
          <div class="stat-box">
            <div class="stat-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8h1a4 4 0 01 0 8h-1"/>
                <path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/>
                <line x1="6" y1="1" x2="6" y2="4"/>
                <line x1="10" y1="1" x2="10" y2="4"/>
                <line x1="14" y1="1" x2="14" y2="4"/>
              </svg>
            </div>
            <div class="stat-content">
              <div class="value"><?php echo $price_currency;?> <?php echo $data['flightTotalPrice']?></div>
              <div class="label">Flight Total Revenue</div>
            </div>
          </div>
        </div>
      </div>
      
<div class="bookings-section">
    <h2 class="bookings-header">All Bookings</h2>

    <div class="filters">

        <select id="bookingTypeFilter">
            <option value="flight_with_hotel">All Booking Types</option>
            <option value="hotel">Hotel</option>
            <option value="flight">Flight</option>
        </select>

        <input type="date" id="startDate" />
        <input type="date" id="endDate" />
    </div>
<div class="bookings-table-sections-main">
    <table class="bookings-table">
        <thead>
            <tr>
                <th>Destination</th>
                <th>Date</th>
                <th>People</th>
            </tr>
        </thead>
        <tbody id="bookingTableBody">
            <!-- Dynamic rows will be added here -->
        </tbody>
    </table>
</div>
</div>
<?php 
$hotelResults = isset($data['hotelbooking']) ? $data['hotelbooking'] : [];
$flightResults = isset($data['flightbooking']) ? $data['flightbooking'] : [];
$flightWithHotelResults = isset($data['flightwithbooking']) ? $data['flightwithbooking'] : [];
?>

    </div>
    
    <!-- Right Panel -->
    <div class="right-panel">
    
      
      <div class="stats-cards">
        <div class="stat-card blue">
          <div class="card-content">
            <div class="label">Total Hotel Booking</div>
            <div class="value"><?php echo count($data['hotelbooking'])?></div>
            
          </div>
          <div class="card-icon">
           <img src="http://localhost/travel/wp-content/themes/travel/photos/star-hotel.png" alt="" class="img-fluid" style="max-height: 24px;">
          </div>
        </div>
        
        <div class="stat-card purple">
          <div class="card-content">
            <div class="label">Total Fligh Booking</div>
            <div class="value"><?php echo count($data['flightbooking'])?></div>
          </div>
          <div class="card-icon">
           <img src="http://localhost/travel/wp-content/themes/travel/photos/aeroplane.png" alt="">
          </div>
        </div>
        
        <div class="stat-card dark">
          <div class="card-content">
            <div class="label">Total Revenue</div>
            <div class="value"><?php echo $price_currency;?> <?php echo $data['totalRevenue']?></div>
           
          </div>
          <div class="card-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="12" y1="1" x2="12" y2="23"/>
              <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
            </svg>
          </div>
        </div>
      </div>
      <?php  $chart_data = get_hotel_Activity_booking_data(7); ?>
      <div class="activity-section">
        <div class="activity-header">
          <h2 class="activity-title"> Recent Activity</h2>
         
        </div>
        
        <div class="chart-container" style="width: 100%; max-width: 600px; margin: auto;">
          <canvas id="weekdayBarChart"></canvas>
        </div>
      

        
      </div>
    </div>
  </div>
   <?php 
$monthdata = get_monthly_booking_price_data();
//echo "<pre>"; print_r($monthdata); die; 
?>
  
