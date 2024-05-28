<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Notifications</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    <h1>Task Notifications</h1>

    <!-- Login Form -->
    <form id="loginForm">
        <label for="Email">Email:</label>
        <input type="text" id="Email" name="Email">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <button type="submit">Login</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM fully loaded and parsed');

            // Event listener for login form submission
            document.getElementById('loginForm').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent form submission

                // Get email and password from the form
                const email = document.getElementById('Email').value;
                const password = document.getElementById('password').value;

                // Simulate login by sending a POST request to the backend
                loginUser(email, password);
            });

            // Function to perform user login
            async function loginUser(email, password) {
                try {
                    const response = await fetch('/api/users/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ email, password }),
                    });
                    if (!response.ok) {
                        throw new Error('Login failed');
                    }
                    const data = await response.json();
                    // Store authentication token (if needed)
                    localStorage.setItem('authToken', data.access_token);

                    console.log('Login successful:', data);
                    
                    // After successful login, set userId dynamically
                    const userId = data.userId;
                    console.log('User ID:', userId); // Log userId to verify
                    initializeEcho(userId); // Pass userId to initializeEcho function
                } catch (error) {
                    console.error('Error:', error.message);
                }
            }

            // Function to request notification permission
            function requestNotificationPermission() {
                if (Notification.permission !== 'granted') {
                    Notification.requestPermission().then(permission => {
                        if (permission === 'granted') {
                            console.log('Notification permission granted.');
                        } else {
                            console.log('Notification permission denied.');
                        }
                    });
                }
                else
                {
                    console.log('Notification permission granted already.');
                }
            }

            // Function to initialize Echo with dynamic userId
            function initializeEcho(userId) {
                const token = localStorage.getItem('authToken');
                if (!token) {
                    console.error('No auth token found');
                    return;
                }

        
                // Subscribe to the private channel
                Echo.private(`taskchannel.${userId}`)
                    .listen('TaskAddedEvent', (event) => {
                        console.log('TaskAddedEvent received!');

                        // Extract relevant data from the event
                        const task = event.Task;

                        // Display the notification to the user
                        showNotification(task);
                    });
            }

            // Function to show notification
            function showNotification(task) {
                console.log('New task notification:', task);

                // Display the notification
                let notification = new Notification('New Task Added', {
                    body: `Task "${task.Task}" has been added.`,
                });
            }
        });
    </script>

    @vite(['resources/js/app.js'])
</body>
</html>
