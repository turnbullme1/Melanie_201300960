<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$page_title = "Home - Melanie's Portfolio";
$current_page = "home";
require_once 'includes/header.php';
?>

<main class="main-content">
    <section id="home" >
        <p class="section-subtitle">Welcome to my professional portfolio. Here you'll find my latest projects and information about my skills and experience.</p>
    </section>

    <section class="elegant-section" id="badges">
        <h2>My Certifications</h2>
        <p class="section-subtitle">Below are some of my earned credentials, showcased through Credly badges.</p>

        <!-- Three Badges Side by Side -->
        <div class="badge-row">
            <h3>Networking</h3>
            <p>Click for official Credly description and verification.</p>
            <div class="badge-container">
                <div data-iframe-width="150" data-iframe-height="270" data-share-badge-id="cb883845-70e1-47f6-a9f8-6fb57316a016" data-share-badge-host="https://www.credly.com"></div>
            </div>
            <div class="badge-container">
                <div data-iframe-width="150" data-iframe-height="270" data-share-badge-id="e74317e9-76b2-45eb-9fc0-63d1d8e04144" data-share-badge-host="https://www.credly.com"></div>
            </div>
            <div class="badge-container">
                <div data-iframe-width="150" data-iframe-height="270" data-share-badge-id="88f42950-2d4c-450e-9ebb-32db8506246d" data-share-badge-host="https://www.credly.com"></div>
            </div>
            <p>Skills: Access Control, Computer Network Management, Ethernet, IPv6, Internet Protocol (IP), Network Automation, Packet Switching, Route Management, Routing Protocols, Security, Service Quality, Software Defined Networking (SDN), Subnetting, Switchover, Threat Management, Uptime, Virtualization, Wide Area Networks</p>
        </div>



        <!-- Single Badge with Image -->
        <div class="badge-row">
            <h3>Security</h3>
            <div class="badge-container">
                <a href="images/sec.pdf" target="_blank">
                    <img src="images/sec.jpg" alt="Security Certification" class="badge-img">
                </a>
            </div>
            <p>Click the image to view the Security certification PDF.</p>
        </div>
		
		        <!-- Grid of Badges (Placeholder) -->
        <div class="badge-row">
            <h3>Other Badges & Certifications</h3>
            <p>Coming soon</p>
        </div>
		
		
    </section>
</main>

<script type="text/javascript" async src="//cdn.credly.com/assets/utilities/embed.js"></script>

<?php require_once 'includes/footer.php'; ?>