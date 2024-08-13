<?php
include 'db_connection.php';
session_start();
if ($_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch rooms for dropdown
$rooms_sql = "SELECT * FROM rooms";
$rooms_result = $conn->query($rooms_sql);

if (!$rooms_result) {
    echo "Error fetching rooms: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='utf-8' />
    <title>Manage Calendar</title>
    <link rel="stylesheet" href="styles/style.css">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css' rel='stylesheet' />
    <script src='scripts/calendar.js'></script>
    <style>
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }
        .calendar-controls {
            text-align: center;
            margin-bottom: 20px;
        }
        .calendar-controls label {
            font-size: 16px;
            font-weight: bold;
            margin-right: 10px;
        }
        .calendar-controls select {
            font-size: 16px;
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background-color: #fff;
            width: 200px;
            margin-top:10px;
        }
        .calendar-controls select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }
        .fc-header-toolbar {
            margin-bottom: 10px;
        }
        .fc-view-harness {
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <h1>Manage Calendar</h1>
    <div class="calendar-controls">
        <label for="room-select">Select Room: </label>
        <select id="room-select">
            <?php
            while ($room = $rooms_result->fetch_assoc()) {
                echo "<option value='{$room['id']}'>{$room['name']}</option>";
            }
            ?>
        </select>
    </div>

    <div id='calendar'></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                editable: true,
                selectable: true,
                events: function(info, successCallback, failureCallback) {
                    var roomId = document.getElementById('room-select').value;
                    fetch('get_calendar_events.php?room_id=' + roomId)
                        .then(response => response.json())
                        .then(data => {
                            console.log('Fetched events:', data); // Debugging line
                            successCallback(data);
                        })
                        .catch(error => {
                            console.error('Error fetching events:', error); // Debugging line
                            failureCallback(error);
                        });
                },
                
                eventClick: function(info) {
                    if (confirm('Delete event?')) {
                        fetch('delete_event.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id: info.event.id
                            })
                        }).then(response => response.json())
                          .then(data => calendar.refetchEvents())
                          .catch(error => alert('Error: ' + error));
                    }
                }
            });

            calendar.render();

            document.getElementById('room-select').addEventListener('change', function() {
                calendar.refetchEvents();
            });
        });
    </script>
    <?php include 'footer.php'; ?>

</body>
</html>
