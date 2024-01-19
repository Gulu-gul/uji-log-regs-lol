<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['fullName'], $_POST['email'], $_POST['password'])) {
        $fullName = $_POST['fullName'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Validate and save data
        if (!empty($fullName) && !empty($email) && !empty($password)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $users = getUsersFromFile();

                // Check if email is already used
                if (isEmailAlreadyUsed($users, $email)) {
                    echo "Email sudah terdaftar. Silakan gunakan email lain.";
                } else {
                    // Add new user to the array
                    $newUser = ['fullName' => $fullName, 'email' => $email, 'password' => $hashedPassword];
                    $users[] = $newUser;

                    // Save updated user data to JSON file
                    saveUsersToFile($users);

                    // Registration successful, redirect
                    header("Location: index.php");
                    exit;
                }
            } else {
                echo "Format email tidak valid.";
            }
        } else {
            echo "Semua kolom harus diisi.";
        }
    } else {
        echo "Formulir tidak lengkap.";
    }
}

function getUsersFromFile() {
    $filename = 'users.json';

    if (file_exists($filename)) {
        $contents = file_get_contents($filename);
        return json_decode($contents, true);
    }

    return [];
}

function saveUsersToFile($users) {
    $filename = 'users.json';
    $json_data = json_encode($users, JSON_PRETTY_PRINT);
    file_put_contents($filename, $json_data);
}

function isEmailAlreadyUsed($users, $email) {
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return true;
        }
    }
    return false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f1f1f1;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
        margin: 0;
      }

      .register-container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        width: 300px;
        text-align: center;
      }

      .register-container h2 {
        color: #4285f4;
      }

      .register-form {
        display: flex;
        flex-direction: column;
        margin-top: 20px;
      }

      .register-form input {
        margin-bottom: 10px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
      }

      .register-form button {
        background-color: #4285f4;
        color: #fff;
        padding: 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
      }

      .register-form button:hover {
        background-color: #317ae2;
      }

      .login-link {
        margin-top: 10px;
        color: #4285f4;
        text-decoration: none;
      }

      .login-link:hover {
        text-decoration: underline;
      }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form class="register-form" id="register-form" method="post" action="index.php">
            <input type="text" name="fullName" placeholder="Nama lengkap" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit">Register</button>
            <a href="index.php" class="login-link">Sudah punya akun? Login disini.</a>
        </form>
        <div id="info-register"></div>
    </div>
<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>

