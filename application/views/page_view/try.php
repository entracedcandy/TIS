<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Push Notification Example</title>
<script>
    // Check if the browser supports service workers
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
                // Registration was successful
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
                // Registration failed
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }

    // Request permission for push notifications
    function requestNotificationPermission() {
        Notification.requestPermission().then(function(result) {
            if (result === 'granted') {
                console.log('Notification permission granted.');
            } else {
                console.log('Notification permission denied.');
            }
        });
    }

    // Send a test push notification
    function sendNotification() {
        if ('serviceWorker' in navigator && 'PushManager' in window) {
            navigator.serviceWorker.ready.then(function(registration) {
                registration.showNotification('Hello!', {
                    body: 'This is a test push notification.',
                    icon: 'icon.png',
                    vibrate: [200, 100, 200, 100, 200, 100, 200],
                    tag: 'notification-demo'
                });
            });
        }
    }
</script>
</head>
<body>
    <h1>Push Notification Example</h1>
    <button onclick="requestNotificationPermission()">Request Notification Permission</button>
    <button onclick="sendNotification()">Send Test Notification</button>
</body>
</html>