<?php include 'header.php'; ?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flashcards_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Load existing JSON
$json_data = file_get_contents('questions.json');
$questions = json_decode($json_data, true);

// New questions
$new_questions = [
    // Systems Security - Module 1: Securing Networks (ss-007 to ss-031)
    ["id" => "ss-007", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Which Cisco command enables SSH on a router?", "options" => ["ssh enable", "crypto key generate rsa", "ip ssh version 2", "enable ssh"], "answer" => "crypto key generate rsa", "question_type" => "Multiple Choice"],
    ["id" => "ss-008", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "A _______ isolates network segments to enhance security.", "options" => null, "answer" => "VLAN", "question_type" => "Fill"],
    ["id" => "ss-009", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Match network security tools with their functions:", "options" => ["Firewall", "IDS", "VLAN"], "answer" => ["Firewall - Filters traffic", "IDS - Detects intrusions", "VLAN - Segments network"], "question_type" => "Match"],
    ["id" => "ss-010", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Explain how VLANs improve network security.", "options" => null, "answer" => "VLANs segment traffic, reducing broadcast domains and limiting unauthorized access.", "question_type" => "Essay"],
    ["id" => "ss-011", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Which protocol encrypts data over a VPN?", "options" => ["IPsec", "HTTP", "FTP", "SNMP"], "answer" => "IPsec", "question_type" => "Multiple Choice"],
    ["id" => "ss-012", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "What does ACL stand for?", "options" => null, "answer" => "Access Control List", "question_type" => "Fill"],
    ["id" => "ss-013", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Which command sets a VLAN IP address?", "options" => ["ip address 192.168.1.1", "vlan ip 192.168.1.1", "interface vlan 10", "ip vlan 10"], "answer" => "interface vlan 10", "question_type" => "Multiple Choice"],
    ["id" => "ss-014", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "A _______ attack targets network availability.", "options" => null, "answer" => "DoS", "question_type" => "Fill"],
    ["id" => "ss-015", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Match security protocols with their uses:", "options" => ["SSL/TLS", "IPsec", "SSH"], "answer" => ["SSL/TLS - Web security", "IPsec - VPNs", "SSH - Remote access"], "question_type" => "Match"],
    ["id" => "ss-016", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Describe the purpose of a DMZ.", "options" => null, "answer" => "A DMZ isolates public-facing servers from the internal network, enhancing security.", "question_type" => "Essay"],
    ["id" => "ss-017", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Which layer does VLAN operate at?", "options" => ["Layer 1", "Layer 2", "Layer 3", "Layer 4"], "answer" => "Layer 2", "question_type" => "Multiple Choice"],
    ["id" => "ss-018", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "The _______ command displays VLAN info.", "options" => null, "answer" => "show vlan brief", "question_type" => "Fill"],
    ["id" => "ss-019", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Which security measure uses MAC addresses?", "options" => ["ACL", "Port security", "VLAN", "NAT"], "answer" => "Port security", "question_type" => "Multiple Choice"],
    ["id" => "ss-020", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "A _______ network uses public infrastructure.", "options" => null, "answer" => "VPN", "question_type" => "Fill"],
    ["id" => "ss-021", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Match network devices with security roles:", "options" => ["Router", "Switch", "Firewall"], "answer" => ["Router - Routes traffic", "Switch - Segments LAN", "Firewall - Filters traffic"], "question_type" => "Match"],
    ["id" => "ss-022", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Explain the role of NAT in security.", "options" => null, "answer" => "NAT hides internal IP addresses, reducing exposure to external threats.", "question_type" => "Essay"],
    ["id" => "ss-023", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Which command sets a router password?", "options" => ["enable password", "set password", "passwd", "login password"], "answer" => "enable password", "question_type" => "Multiple Choice"],
    ["id" => "ss-024", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "A _______ prevents unauthorized access.", "options" => null, "answer" => "firewall", "question_type" => "Fill"],
    ["id" => "ss-025", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Which protocol uses port 22?", "options" => ["FTP", "SSH", "Telnet", "HTTP"], "answer" => "SSH", "question_type" => "Multiple Choice"],
    ["id" => "ss-026", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "The _______ command shows interface status.", "options" => null, "answer" => "show interfaces", "question_type" => "Fill"],
    ["id" => "ss-027", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Match security features with descriptions:", "options" => ["ACL", "NAT", "VPN"], "answer" => ["ACL - Filters packets", "NAT - Translates addresses", "VPN - Encrypts traffic"], "question_type" => "Match"],
    ["id" => "ss-028", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Describe how ACLs enhance security.", "options" => null, "answer" => "ACLs filter traffic based on rules, blocking unauthorized access.", "question_type" => "Essay"],
    ["id" => "ss-029", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Which command configures a VLAN?", "options" => ["vlan 10", "set vlan 10", "interface vlan 10", "vlan config 10"], "answer" => "vlan 10", "question_type" => "Multiple Choice"],
    ["id" => "ss-030", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "A _______ key secures SSH sessions.", "options" => null, "answer" => "RSA", "question_type" => "Fill"],
    ["id" => "ss-031", "course" => "Systems Security", "category" => "Module 1: Securing Networks", "question" => "Which protocol secures web traffic?", "options" => ["HTTP", "HTTPS", "FTP", "SMTP"], "answer" => "HTTPS", "question_type" => "Multiple Choice"],

    // Module 2: Network Threats (ss-032 to ss-056)
    ["id" => "ss-032", "course" => "Systems Security", "category" => "Module 2: Network Threats", "question" => "Which attack floods a network with traffic?", "options" => ["Phishing", "DDoS", "Spoofing", "MITM"], "answer" => "DDoS", "question_type" => "Multiple Choice"],
    ["id" => "ss-033", "course" => "Systems Security", "category" => "Module 2: Network Threats", "question" => "A _______ attack tricks users into revealing info.", "options" => null, "answer" => "Phishing", "question_type" => "Fill"],
    ["id" => "ss-034", "course" => "Systems Security", "category" => "Module 2: Network Threats", "question" => "Match attack types with descriptions:", "options" => ["DDoS", "Phishing", "MITM"], "answer" => ["DDoS - Overloads servers", "Phishing - Steals credentials", "MITM - Intercepts communication"], "question_type" => "Match"],
    ["id" => "ss-035", "course" => "Systems Security", "category" => "Module 2: Network Threats", "question" => "Explain how a DDoS attack disrupts services.", "options" => null, "answer" => "A DDoS attack floods a target with traffic, overwhelming resources and denying access to legitimate users.", "question_type" => "Essay"],
    ["id" => "ss-036", "course" => "Systems Security", "category" => "Module 2: Network Threats", "question" => "Which attack impersonates a legitimate source?", "options" => ["Spoofing", "Brute Force", "SQL Injection", "XSS"], "answer" => "Spoofing", "question_type" => "Multiple Choice"],
    // ... (20 more up to ss-056)

    // Module 3: Mitigating Threats (ss-057 to ss-081)
    ["id" => "ss-057", "course" => "Systems Security", "category" => "Module 3: Mitigating Threats", "question" => "Which command limits traffic rate?", "options" => ["rate-limit", "traffic-shape", "ip limit", "bandwidth"], "answer" => "rate-limit", "question_type" => "Multiple Choice"],
    // ... (24 more up to ss-081)

    // Module 4: Securing Device Access (ss-082 to ss-106)
    ["id" => "ss-082", "course" => "Systems Security", "category" => "Module 4: Securing Device Access", "question" => "Which command sets a console password?", "options" => ["line con 0", "password cisco", "login", "All of these"], "answer" => "All of these", "question_type" => "Multiple Choice"],
    // ... (24 more up to ss-106)

    // Module 5: Assigning Administrative Roles (ss-107 to ss-131)
    ["id" => "ss-107", "course" => "Systems Security", "category" => "Module 5: Assigning Administrative Roles", "question" => "Which command assigns privilege level 15?", "options" => ["privilege 15", "username admin privilege 15", "level 15", "admin 15"], "answer" => "username admin privilege 15", "question_type" => "Multiple Choice"],
    // ... (24 more up to ss-131)
    // Continue up to Module 22 (ss-556) in Parts 2 and 3


    // Continuing $new_questions from Part 1...

    // Module 6: Device Monitoring and Management (ss-132 to ss-156)
    ["id" => "ss-132", "course" => "Systems Security", "category" => "Module 6: Device Monitoring and Management", "question" => "Which protocol monitors network devices?", "options" => ["SNMP", "SMTP", "FTP", "HTTP"], "answer" => "SNMP", "question_type" => "Multiple Choice"],
    ["id" => "ss-133", "course" => "Systems Security", "category" => "Module 6: Device Monitoring and Management", "question" => "The _______ command enables SNMP.", "options" => null, "answer" => "snmp-server enable", "question_type" => "Fill"],
    ["id" => "ss-134", "course" => "Systems Security", "category" => "Module 6: Device Monitoring and Management", "question" => "Match monitoring tools with purposes:", "options" => ["SNMP", "Syslog", "NetFlow"], "answer" => ["SNMP - Device status", "Syslog - Logs events", "NetFlow - Traffic analysis"], "question_type" => "Match"],
    ["id" => "ss-135", "course" => "Systems Security", "category" => "Module 6: Device Monitoring and Management", "question" => "Explain the role of SNMP in security.", "options" => null, "answer" => "SNMP monitors device health and alerts admins to potential security issues.", "question_type" => "Essay"],
    ["id" => "ss-136", "course" => "Systems Security", "category" => "Module 6: Device Monitoring and Management", "question" => "Which command sets an SNMP community?", "options" => ["snmp-server community public", "snmp community public", "community public snmp", "snmp public"], "answer" => "snmp-server community public", "question_type" => "Multiple Choice"],
    // ... (20 more to ss-156)

    // Module 7: Authentication, Authorization, and Accounting (AAA) (ss-157 to ss-181)
    ["id" => "ss-157", "course" => "Systems Security", "category" => "Module 7: Authentication, Authorization, and Accounting (AAA)", "question" => "Which protocol is used for AAA?", "options" => ["RADIUS", "HTTP", "FTP", "SNMP"], "answer" => "RADIUS", "question_type" => "Multiple Choice"],
    // ... (24 more to ss-181)

    // Module 8: Access Control Lists (ss-182 to ss-206)
    ["id" => "ss-182", "course" => "Systems Security", "category" => "Module 8: Access Control Lists", "question" => "Which command applies an ACL to an interface?", "options" => ["ip access-group", "access-list apply", "interface acl", "apply acl"], "answer" => "ip access-group", "question_type" => "Multiple Choice"],
    // ... (24 more to ss-206)

    // Module 9: Firewall Technologies (ss-207 to ss-231)
    ["id" => "ss-207", "course" => "Systems Security", "category" => "Module 9: Firewall Technologies", "question" => "Which firewall type examines packet headers only?", "options" => ["Packet-filtering", "Stateful", "Proxy", "Next-Generation"], "answer" => "Packet-filtering", "question_type" => "Multiple Choice"],
    ["id" => "ss-208", "course" => "Systems Security", "category" => "Module 9: Firewall Technologies", "question" => "A _______ firewall tracks connection states.", "options" => null, "answer" => "Stateful", "question_type" => "Fill"],
    // ... (23 more to ss-231)

    // Module 10: Zone-Based Policy Firewalls (ss-232 to ss-256)
    ["id" => "ss-232", "course" => "Systems Security", "category" => "Module 10: Zone-Based Policy Firewalls", "question" => "Which command defines a security zone?", "options" => ["zone security", "security zone", "zone define", "define zone"], "answer" => "zone security", "question_type" => "Multiple Choice"],
    // ... (24 more to ss-256)

    // Module 11: IPS Technologies (ss-257 to ss-281)
    ["id" => "ss-257", "course" => "Systems Security", "category" => "Module 11: IPS Technologies", "question" => "IPS stands for _______ Prevention System.", "options" => null, "answer" => "Intrusion", "question_type" => "Fill"],
    // ... (24 more to ss-281)

    // Module 12: IPS Operation and Implementation (ss-282 to ss-306)
    ["id" => "ss-282", "course" => "Systems Security", "category" => "Module 12: IPS Operation and Implementation", "question" => "Which command applies an IPS rule?", "options" => ["ip ips name", "ips apply", "ip ips in", "ips rule"], "answer" => "ip ips in", "question_type" => "Multiple Choice"],
    // ... (24 more to ss-306)

    // Module 13: Endpoint Security (ss-307 to ss-331)
    ["id" => "ss-307", "course" => "Systems Security", "category" => "Module 13: Endpoint Security", "question" => "Which command enables port security?", "options" => ["port-security", "switchport port-security", "security port", "enable port-security"], "answer" => "switchport port-security", "question_type" => "Multiple Choice"],
    // ... (24 more to ss-331)

    // Module 14: Layer 2 Security Considerations (ss-332 to ss-356)
    ["id" => "ss-332", "course" => "Systems Security", "category" => "Module 14: Layer 2 Security Considerations", "question" => "Which attack exploits ARP?", "options" => ["ARP Spoofing", "DDoS", "Phishing", "SQL Injection"], "answer" => "ARP Spoofing", "question_type" => "Multiple Choice"],
    // ... (24 more to ss-356)

    // Module 15: Cryptographic Services (ss-357 to ss-381)
    ["id" => "ss-357", "course" => "Systems Security", "category" => "Module 15: Cryptographic Services", "question" => "Which command generates RSA keys?", "options" => ["crypto key generate rsa", "rsa generate", "key rsa", "generate rsa"], "answer" => "crypto key generate rsa", "question_type" => "Multiple Choice"],
    // ... (24 more to ss-381)

    // Module 16: Basic Integrity and Authenticity (ss-382 to ss-406)
    ["id" => "ss-382", "course" => "Systems Security", "category" => "Module 16: Basic Integrity and Authenticity", "question" => "HMAC uses _______ for integrity.", "options" => null, "answer" => "hashing", "question_type" => "Fill"],
    // ... (24 more to ss-406)

    // Module 17: Public Key Cryptography (ss-407 to ss-431)
    ["id" => "ss-407", "course" => "Systems Security", "category" => "Module 17: Public Key Cryptography", "question" => "Which key pair includes public and private keys?", "options" => ["RSA", "DES", "AES", "MD5"], "answer" => "RSA", "question_type" => "Multiple Choice"],
    // ... (24 more to ss-431)

    // Module 18: VPNs (ss-432 to ss-456)
    ["id" => "ss-432", "course" => "Systems Security", "category" => "Module 18: VPNs", "question" => "Which protocol secures VPN traffic?", "options" => ["IPsec", "HTTP", "FTP", "SNMP"], "answer" => "IPsec", "question_type" => "Multiple Choice"],
    // ... (24 more to ss-456)

    // Module 19: Implement Site-to-Site IPsec VPNs (ss-457 to ss-481)
    ["id" => "ss-457", "course" => "Systems Security", "category" => "Module 19: Implement Site-to-Site IPsec VPNs", "question" => "Which command sets an IPsec transform set?", "options" => ["crypto ipsec transform-set", "ipsec transform", "transform-set ipsec", "set transform"], "answer" => "crypto ipsec transform-set", "question_type" => "Multiple Choice"],
    // ... (24 more to ss-481)

    // Module 20: Introduction to the ASA (ss-482 to ss-506)
    ["id" => "ss-482", "course" => "Systems Security", "category" => "Module 20: Introduction to the ASA", "question" => "ASA stands for _______ Security Appliance.", "options" => null, "answer" => "Adaptive", "question_type" => "Fill"],
    // ... (24 more to ss-506)

    // Module 21: ASA Firewall Configuration (ss-507 to ss-531)
    ["id" => "ss-507", "course" => "Systems Security", "category" => "Module 21: ASA Firewall Configuration", "question" => "Which command sets an ASA interface IP?", "options" => ["ip address", "interface ip", "set ip", "config ip"], "answer" => "ip address", "question_type" => "Multiple Choice"],
    // ... (24 more to ss-531)

    // Module 22: Network Security Testing (ss-532 to ss-556)
    ["id" => "ss-532", "course" => "Systems Security", "category" => "Module 22: Network Security Testing", "question" => "Which tool tests network vulnerabilities?", "options" => ["Wireshark", "Nmap", "Ping", "Traceroute"], "answer" => "Nmap", "question_type" => "Multiple Choice"],
    // ... (24 more to ss-556)

    // Certification Prep - A+ (cp-001 to cp-075, partial due to limit)
    ["id" => "cp-001", "course" => "Certification Prep", "category" => "A+", "question" => "What does BIOS stand for?", "options" => null, "answer" => "Basic Input/Output System", "question_type" => "Fill"],
    ["id" => "cp-002", "course" => "Certification Prep", "category" => "A+", "question" => "Which port is used for USB connections?", "options" => ["COM", "LPT", "USB", "PS/2"], "answer" => "USB", "question_type" => "Multiple Choice"],
    ["id" => "cp-003", "course" => "Certification Prep", "category" => "A+", "question" => "Match PC components with their roles:", "options" => ["CPU", "RAM", "GPU"], "answer" => ["CPU - Processes data", "RAM - Stores temporary data", "GPU - Renders graphics"], "question_type" => "Match"],
    ["id" => "cp-004", "course" => "Certification Prep", "category" => "A+", "question" => "Explain the role of the CPU.", "options" => null, "answer" => "The CPU executes instructions from programs by performing fetch, decode, and execute cycles.", "question_type" => "Essay"],
    ["id" => "cp-005", "course" => "Certification Prep", "category" => "A+", "question" => "Which component cools the CPU?", "options" => ["Fan", "Heatsink", "PSU", "Both A and B"], "answer" => "Both A and B", "question_type" => "Multiple Choice"],
    // ... (up to cp-075, truncated here)
    // Scripting (pr-151 to pr-180, 30 questions)
    ["id" => "pr-151", "course" => "Programming", "category" => "Scripting", "question" => "In Bash, _______ prints text.", "options" => null, "answer" => "echo", "question_type" => "Fill"],
    ["id" => "pr-152", "course" => "Programming", "category" => "Scripting", "question" => "Which command runs a script?", "options" => ["run", "bash", "exec", "start"], "answer" => "bash", "question_type" => "Multiple Choice"],
    ["id" => "pr-153", "course" => "Programming", "category" => "Scripting", "question" => "A _______ variable stores data in Bash.", "options" => null, "answer" => "environment", "question_type" => "Fill"],
    ["id" => "pr-154", "course" => "Programming", "category" => "Scripting", "question" => "Match Bash commands with functions:", "options" => ["echo", "ls", "chmod"], "answer" => ["echo - Prints text", "ls - Lists files", "chmod - Changes permissions"], "question_type" => "Match"],
    ["id" => "pr-155", "course" => "Programming", "category" => "Scripting", "question" => "Explain how to set a variable in Bash.", "options" => null, "answer" => "Use 'name=value' syntax, e.g., 'MYVAR=hello', with no spaces around the equals sign.", "question_type" => "Essay"],
    ["id" => "pr-156", "course" => "Programming", "category" => "Scripting", "question" => "Which symbol redirects output?", "options" => [">", "<", "|", "&"], "answer" => ">", "question_type" => "Multiple Choice"],
    ["id" => "pr-157", "course" => "Programming", "category" => "Scripting", "question" => "The _______ command lists processes.", "options" => null, "answer" => "ps", "question_type" => "Fill"],
    ["id" => "pr-158", "course" => "Programming", "category" => "Scripting", "question" => "Which command kills a process?", "options" => ["stop", "kill", "end", "terminate"], "answer" => "kill", "question_type" => "Multiple Choice"],
    ["id" => "pr-159", "course" => "Programming", "category" => "Scripting", "question" => "A _______ loop repeats commands.", "options" => null, "answer" => "for", "question_type" => "Fill"],
    ["id" => "pr-160", "course" => "Programming", "category" => "Scripting", "question" => "Match script elements with roles:", "options" => ["#!/bin/bash", "$1", "if"], "answer" => ["#!/bin/bash - Shebang", "$1 - First argument", "if - Conditional"], "question_type" => "Match"],
    ["id" => "pr-161", "course" => "Programming", "category" => "Scripting", "question" => "Describe Bash conditional statements.", "options" => null, "answer" => "Bash uses 'if [condition]; then commands; fi' to execute code based on conditions.", "question_type" => "Essay"],
    ["id" => "pr-162", "course" => "Programming", "category" => "Scripting", "question" => "Which operator tests equality?", "options" => ["==", "-eq", "eq", "="], "answer" => "-eq", "question_type" => "Multiple Choice"],
    ["id" => "pr-163", "course" => "Programming", "category" => "Scripting", "question" => "The _______ command changes directories.", "options" => null, "answer" => "cd", "question_type" => "Fill"],
    ["id" => "pr-164", "course" => "Programming", "category" => "Scripting", "question" => "Which command shows current directory?", "options" => ["dir", "pwd", "where", "path"], "answer" => "pwd", "question_type" => "Multiple Choice"],
    ["id" => "pr-165", "course" => "Programming", "category" => "Scripting", "question" => "A _______ file runs commands.", "options" => null, "answer" => "script", "question_type" => "Fill"],
    ["id" => "pr-166", "course" => "Programming", "category" => "Scripting", "question" => "Match redirection symbols with uses:", "options" => [">", "<", ">>"], "answer" => ["> - Overwrites file", "< - Input from file", ">> - Appends to file"], "question_type" => "Match"],
    ["id" => "pr-167", "course" => "Programming", "category" => "Scripting", "question" => "Explain piping in Bash.", "options" => null, "answer" => "Piping uses '|' to send one command’s output as input to another, e.g., 'ls | grep txt'.", "question_type" => "Essay"],
    ["id" => "pr-168", "course" => "Programming", "category" => "Scripting", "question" => "Which command shows file contents?", "options" => ["cat", "show", "print", "view"], "answer" => "cat", "question_type" => "Multiple Choice"],
    ["id" => "pr-169", "course" => "Programming", "category" => "Scripting", "question" => "The _______ command searches text.", "options" => null, "answer" => "grep", "question_type" => "Fill"],
    ["id" => "pr-170", "course" => "Programming", "category" => "Scripting", "question" => "Which symbol runs commands in background?", "options" => ["&", "|", ">", "<"], "answer" => "&", "question_type" => "Multiple Choice"],
    ["id" => "pr-171", "course" => "Programming", "category" => "Scripting", "question" => "A _______ statement checks conditions.", "options" => null, "answer" => "if", "question_type" => "Fill"],
    ["id" => "pr-172", "course" => "Programming", "category" => "Scripting", "question" => "Match Bash loops with syntax:", "options" => ["for", "while", "until"], "answer" => ["for - for i in list; do", "while - while condition; do", "until - until condition; do"], "question_type" => "Match"],
    ["id" => "pr-173", "course" => "Programming", "category" => "Scripting", "question" => "Explain Bash functions.", "options" => null, "answer" => "Functions in Bash group commands under a name, e.g., 'myfunc() { commands; }', for reuse.", "question_type" => "Essay"],
    ["id" => "pr-174", "course" => "Programming", "category" => "Scripting", "question" => "Which command shows environment variables?", "options" => ["env", "vars", "setenv", "list"], "answer" => "env", "question_type" => "Multiple Choice"],
    ["id" => "pr-175", "course" => "Programming", "category" => "Scripting", "question" => "The _______ command creates files.", "options" => null, "answer" => "touch", "question_type" => "Fill"],
    ["id" => "pr-176", "course" => "Programming", "category" => "Scripting", "question" => "Which operator tests file existence?", "options" => ["-e", "-f", "-d", "exists"], "answer" => "-e", "question_type" => "Multiple Choice"],
    ["id" => "pr-177", "course" => "Programming", "category" => "Scripting", "question" => "A _______ script automates tasks.", "options" => null, "answer" => "Bash", "question_type" => "Fill"],
    ["id" => "pr-178", "course" => "Programming", "category" => "Scripting", "question" => "Match file commands with actions:", "options" => ["cp", "mv", "rm"], "answer" => ["cp - Copies", "mv - Moves", "rm - Deletes"], "question_type" => "Match"],
    ["id" => "pr-179", "course" => "Programming", "category" => "Scripting", "question" => "Describe Bash exit codes.", "options" => null, "answer" => "Exit codes indicate success (0) or failure (non-zero) of a command, e.g., 'echo $?' shows the last status.", "question_type" => "Essay"],
    ["id" => "pr-180", "course" => "Programming", "category" => "Scripting", "question" => "Which command shows script arguments?", "options" => ["$#", "$@", "$*", "All of these"], "answer" => "All of these", "question_type" => "Multiple Choice"]
]; // End of $new_questions array

// Combine all questions
$all_questions = array_merge($questions, $new_questions);

// Clear table
$conn->query("TRUNCATE TABLE questions");

foreach ($all_questions as $q) {
    $stmt = $conn->prepare("INSERT INTO questions (id, course, category, question, options, answer, question_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $options = isset($q['options']) ? json_encode($q['options']) : null;
    $answer = is_array($q['answer']) ? json_encode($q['answer']) : $q['answer'];
    $stmt->bind_param("sssssss", $q['id'], $q['course'], $q['category'], $q['question'], $options, $answer, $q['question_type']);
    if (!$stmt->execute()) {
        echo "Error importing question ID " . $q['id'] . ": " . $stmt->error . "<br>";
    }
}

$conn->close();
echo "<h2>Questions imported successfully!</h2>";
?>

<?php include 'footer.php'; ?>