<?php 
include('includes/header.php');

class ConfigGenerator {
    public static function generateRouterConfig($hostname, $ipAddress) {
        return "
hostname $hostname
interface g0/0
 ip address $ipAddress 255.255.255.0
 no shutdown
end
        ";
    }

    public static function generateSwitchConfig($hostname, $ipAddress) {
        return "
hostname $hostname
interface vlan 1
 ip address $ipAddress 255.255.255.0
 no shutdown
end
        ";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cisco Config Generator</title>
</head>
<body>

<h1>Cisco Config Generator</h1>

<form action="config_generator.php" method="POST">
    <!-- Device Selection -->
    <label for="routers">Number of Routers:</label>
    <input type="number" name="routers" id="routers" min="0" value="0">
    
    <label for="switches">Number of Switches:</label>
    <input type="number" name="switches" id="switches" min="0" value="0">

    <h3>Choose Devices to Configure:</h3>
    <input type="checkbox" id="routerCheckbox" name="devices[]" value="router">
    <label for="routerCheckbox">Router</label>
    <input type="checkbox" id="switchCheckbox" name="devices[]" value="switch">
    <label for="switchCheckbox">Switch</label>

    <h3>Config Details:</h3>
    <div id="deviceForms"></div>

    <input type="submit" value="Generate Configurations">
</form>

<script>
// JavaScript to dynamically display the forms for the specified number of devices
document.getElementById('routers').addEventListener('input', updateDeviceForms);
document.getElementById('switches').addEventListener('input', updateDeviceForms);

function updateDeviceForms() {
    let routers = document.getElementById('routers').value;
    let switches = document.getElementById('switches').value;
    let deviceForms = document.getElementById('deviceForms');
    deviceForms.innerHTML = ''; // Clear current forms

    // Display router forms if the checkbox is checked
    if (document.getElementById('routerCheckbox').checked) {
        for (let i = 0; i < routers; i++) {
            deviceForms.innerHTML += `
                <h4>Router ${i + 1}:</h4>
                Hostname: <input type="text" name="routerHostnames[]" required><br>
                IP Address: <input type="text" name="routerIPs[]" required><br><br>
            `;
        }
    }

    // Display switch forms if the checkbox is checked
    if (document.getElementById('switchCheckbox').checked) {
        for (let i = 0; i < switches; i++) {
            deviceForms.innerHTML += `
                <h4>Switch ${i + 1}:</h4>
                Hostname: <input type="text" name="switchHostnames[]" required><br>
                IP Address: <input type="text" name="switchIPs[]" required><br><br>
            `;
        }
    }
}
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '<h2>Generated Configurations:</h2>';

    if (isset($_POST['devices'])) {
        $devices = $_POST['devices'];
        $routerHostnames = $_POST['routerHostnames'] ?? [];
        $routerIPs = $_POST['routerIPs'] ?? [];
        $switchHostnames = $_POST['switchHostnames'] ?? [];
        $switchIPs = $_POST['switchIPs'] ?? [];

        // Generate router configs
        if (in_array('router', $devices)) {
            foreach ($routerHostnames as $index => $hostname) {
                $ipAddress = $routerIPs[$index] ?? '';
                echo '<pre>' . ConfigGenerator::generateRouterConfig($hostname, $ipAddress) . '</pre>';
            }
        }

        // Generate switch configs
        if (in_array('switch', $devices)) {
            foreach ($switchHostnames as $index => $hostname) {
                $ipAddress = $switchIPs[$index] ?? '';
                echo '<pre>' . ConfigGenerator::generateSwitchConfig($hostname, $ipAddress) . '</pre>';
            }
        }
    }
}
?>

</body>
</html>

