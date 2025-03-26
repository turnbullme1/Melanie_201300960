-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2025 at 03:00 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `flashcards_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `question` text NOT NULL,
  `options` text DEFAULT NULL,
  `answer` text NOT NULL,
  `question_type` enum('MCQ','Match','Fill','Essay') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `category`, `question`, `options`, `answer`, `question_type`) VALUES
(1, 'NDG: Cloud and Virtualization Concepts', 'Which of the following is NOT a characteristic of cloud computing?', '[\"On-demand self-service\", \"Broad network access\", \"Limited resource pooling\", \"Rapid elasticity\"]', 'Limited resource pooling', 'MCQ'),
(2, 'NDG: Cloud and Virtualization Concepts', 'In virtualization, a Type 1 hypervisor is also known as:', '[\"Hosted hypervisor\", \"Bare-metal hypervisor\", \"Client-side hypervisor\", \"Nested hypervisor\"]', 'Bare-metal hypervisor', 'MCQ'),
(3, 'NDG: Cloud and Virtualization Concepts', 'VMware Workstation Pro is classified as which type of hypervisor?', '[\"Type 1 hypervisor\", \"Type 2 hypervisor\", \"Type 3 hypervisor\", \"Cloud-based hypervisor\"]', 'Type 2 hypervisor', 'MCQ'),
(4, 'NDG: Cloud and Virtualization Concepts', 'Which feature in Oracle VM VirtualBox allows seamless integration of guest windows into the host desktop?', '[\"Guest Additions\", \"Shared Folders\", \"Seamless Mode\", \"Virtual Networking\"]', 'Seamless Mode', 'MCQ'),
(5, 'NDG: Cloud and Virtualization Concepts', 'Microsoft Hyper-V allows the creation of three types of virtual switches. Which of the following is NOT one of them?', '[\"External\", \"Internal\", \"Private\", \"Public\"]', 'Public', 'MCQ'),
(6, 'NDG: Cloud and Virtualization Concepts', 'In cloud service models, Platform as a Service (PaaS) provides:', '[\"Virtualized hardware resources\", \"Development tools and environment\", \"Ready-to-use software applications\", \"Networking hardware and protocols\"]', 'Development tools and environment', 'MCQ'),
(7, 'NDG: Cloud and Virtualization Concepts', 'VMware ESXi is best described as:', '[\"A Type 2 hypervisor requiring a host OS\", \"A cloud service model\", \"A Type 1 hypervisor for server virtualization\", \"A desktop virtualization application\"]', 'A Type 1 hypervisor for server virtualization', 'MCQ'),
(8, 'NDG: Cloud and Virtualization Concepts', 'The Rapid Elasticity characteristic of cloud computing refers to:', '[\"The ability to stretch network cables\", \"Quick scaling of resources up or down as needed\", \"Fast data transmission speeds\", \"Immediate software updates\"]', 'Quick scaling of resources up or down as needed', 'MCQ'),
(9, 'NDG: Cloud and Virtualization Concepts', 'In VMware ICM Module 4, the recommended practice before making significant changes to a VM is to:', '[\"Clone the VM\", \"Take a snapshot\", \"Shut down the VM\", \"Increase the VM\'s memory\"]', 'Take a snapshot', 'MCQ'),
(10, 'NDG: Cloud and Virtualization Concepts', 'Which of the following is a benefit of using virtualization?', '[\"Increased hardware costs\", \"Reduced server utilization\", \"Isolation of applications\", \"Limited scalability\"]', 'Isolation of applications', 'MCQ'),
(11, 'NDG: Cloud and Virtualization Concepts', 'Match the virtualization tools to their appropriate descriptions:', '[\"1. VMware Workstation Pro\", \"2. Oracle VM VirtualBox\", \"3. Microsoft Hyper-V\", \"4. VMware ESXi\", \"5. Docker\"]', '[\"1 - A Type 2 hypervisor used for running multiple OS on a single PC\",\r\n   \"2 - An open-source Type 2 hypervisor supporting cross-platform use\",\r\n   \"3 - A Type 1 hypervisor designed for Windows environments\",\r\n   \"4 - A bare-metal hypervisor used in enterprise environments\",\r\n   \"5 - A containerization platform for deploying applications\"]', 'Match'),
(12, 'NDG: Cloud and Virtualization Concepts', 'In cloud computing, the service model where users are provided with applications over the internet is known as ______________.', NULL, 'Software as a Service (SaaS)', 'Fill'),
(13, 'NDG: Cloud and Virtualization Concepts', '________________ is a software layer that enables multiple operating systems to share a single hardware host.', NULL, 'Hypervisor', 'Fill'),
(14, 'NDG: Cloud and Virtualization Concepts', 'The ______________ feature in virtualization allows you to save the state of a virtual machine at a specific point in time.', NULL, 'Snapshot', 'Fill'),
(15, 'NDG: Cloud and Virtualization Concepts', '________________ is the process of creating a virtual version of something, such as hardware or a storage device.', NULL, 'Virtualization', 'Fill'),
(16, 'NDG: Cloud and Virtualization Concepts', 'In VMware, ______________ provides centralized management of virtualized hosts and VMs.', NULL, 'vCenter Server', 'Fill'),
(17, 'NDG: Cloud and Virtualization Concepts', 'Explain the differences between Type 1 and Type 2 hypervisors. Provide examples of each and discuss scenarios where one might be preferred over the other.', NULL, 'Type 1 hypervisors run directly on hardware (e.g., VMware ESXi, Microsoft Hyper-V), while Type 2 hypervisors run on a host OS (e.g., VMware Workstation, VirtualBox). Type 1 is used for enterprise server virtualization, while Type 2 is used for development and testing.', 'Essay'),
(18, 'NDG: Cloud and Virtualization Concepts', 'Discuss the benefits and challenges of cloud computing in modern IT infrastructure. How does virtualization support cloud computing?', NULL, 'Cloud computing benefits include scalability, cost efficiency, and accessibility. Challenges include security and vendor lock-in. Virtualization enables efficient resource allocation and scalability in cloud environments.', 'Essay'),
(19, 'NDG: Cloud and Virtualization Concepts', 'Describe the process of creating a virtual machine in VMware Workstation Pro. Highlight the key steps and considerations during the setup.', NULL, 'Key steps: Open VMware Workstation, create a new VM, select OS type, allocate memory, configure disk, install OS, install VMware Tools.', 'Essay'),
(20, 'NDG: Cloud and Virtualization Concepts', 'Oracle VM VirtualBox offers \"Guest Additions\". Explain what this feature is and how it enhances the performance and usability of virtual machines.', NULL, '\"Guest Additions\" enhances VM performance by enabling shared clipboard, drag-and-drop, improved display resolution, and seamless integration between host and guest OS.', 'Essay'),
(21, 'NDG: Cloud and Virtualization Concepts', 'In the context of VMware ICM Module 1, define what is meant by a Software-Defined Data Center (SDDC) and discuss its importance in enterprise environments.', NULL, 'SDDC refers to a data center where all infrastructure components (compute, storage, networking) are virtualized and delivered as a service. It enhances automation, scalability, and efficiency in enterprise IT.', 'Essay'),
(22, 'NDG: Cloud and Virtualization Concepts', 'Which of the following is NOT a characteristic of cloud computing?', '[\"On-demand self-service\", \"Broad network access\", \"Limited resource pooling\", \"Rapid elasticity\"]', 'Limited resource pooling', 'MCQ'),
(23, 'NDG: Cloud and Virtualization Concepts', 'In virtualization, a Type 1 hypervisor is also known as:', '[\"Hosted hypervisor\", \"Bare-metal hypervisor\", \"Client-side hypervisor\", \"Nested hypervisor\"]', 'Bare-metal hypervisor', 'MCQ'),
(24, 'NDG: Cloud and Virtualization Concepts', 'VMware Workstation Pro is classified as which type of hypervisor?', '[\"Type 1 hypervisor\", \"Type 2 hypervisor\", \"Type 3 hypervisor\", \"Cloud-based hypervisor\"]', 'Type 2 hypervisor', 'MCQ'),
(25, 'NDG: Cloud and Virtualization Concepts', 'Which feature in Oracle VM VirtualBox allows seamless integration of guest windows into the host desktop?', '[\"Guest Additions\", \"Shared Folders\", \"Seamless Mode\", \"Virtual Networking\"]', 'Seamless Mode', 'MCQ'),
(26, 'NDG: Cloud and Virtualization Concepts', 'Microsoft Hyper-V allows the creation of three types of virtual switches. Which of the following is NOT one of them?', '[\"External\", \"Internal\", \"Private\", \"Public\"]', 'Public', 'MCQ'),
(27, 'NDG: Cloud and Virtualization Concepts', 'In cloud service models, Platform as a Service (PaaS) provides:', '[\"Virtualized hardware resources\", \"Development tools and environment\", \"Ready-to-use software applications\", \"Networking hardware and protocols\"]', 'Development tools and environment', 'MCQ'),
(28, 'NDG: Cloud and Virtualization Concepts', 'VMware ESXi is best described as:', '[\"A Type 2 hypervisor requiring a host OS\", \"A cloud service model\", \"A Type 1 hypervisor for server virtualization\", \"A desktop virtualization application\"]', 'A Type 1 hypervisor for server virtualization', 'MCQ'),
(29, 'NDG: Cloud and Virtualization Concepts', 'The Rapid Elasticity characteristic of cloud computing refers to:', '[\"The ability to stretch network cables\", \"Quick scaling of resources up or down as needed\", \"Fast data transmission speeds\", \"Immediate software updates\"]', 'Quick scaling of resources up or down as needed', 'MCQ'),
(30, 'NDG: Cloud and Virtualization Concepts', 'In VMware ICM Module 4, the recommended practice before making significant changes to a VM is to:', '[\"Clone the VM\", \"Take a snapshot\", \"Shut down the VM\", \"Increase the VM\'s memory\"]', 'Take a snapshot', 'MCQ'),
(31, 'NDG: Cloud and Virtualization Concepts', 'Which of the following is a benefit of using virtualization?', '[\"Increased hardware costs\", \"Reduced server utilization\", \"Isolation of applications\", \"Limited scalability\"]', 'Isolation of applications', 'MCQ'),
(32, 'NDG: Cloud and Virtualization Concepts', 'Match the virtualization tools to their appropriate descriptions:', '[\"1. VMware Workstation Pro\", \"2. Oracle VM VirtualBox\", \"3. Microsoft Hyper-V\", \"4. VMware ESXi\", \"5. Docker\"]', '[\"1 - A Type 2 hypervisor used for running multiple OS on a single PC\",\r\n   \"2 - An open-source Type 2 hypervisor supporting cross-platform use\",\r\n   \"3 - A Type 1 hypervisor designed for Windows environments\",\r\n   \"4 - A bare-metal hypervisor used in enterprise environments\",\r\n   \"5 - A containerization platform for deploying applications\"]', 'Match'),
(33, 'NDG: Cloud and Virtualization Concepts', 'In cloud computing, the service model where users are provided with applications over the internet is known as ______________.', NULL, 'Software as a Service (SaaS)', 'Fill'),
(34, 'NDG: Cloud and Virtualization Concepts', '________________ is a software layer that enables multiple operating systems to share a single hardware host.', NULL, 'Hypervisor', 'Fill'),
(35, 'NDG: Cloud and Virtualization Concepts', 'The ______________ feature in virtualization allows you to save the state of a virtual machine at a specific point in time.', NULL, 'Snapshot', 'Fill'),
(36, 'NDG: Cloud and Virtualization Concepts', '________________ is the process of creating a virtual version of something, such as hardware or a storage device.', NULL, 'Virtualization', 'Fill'),
(37, 'NDG: Cloud and Virtualization Concepts', 'In VMware, ______________ provides centralized management of virtualized hosts and VMs.', NULL, 'vCenter Server', 'Fill'),
(38, 'NDG: Cloud and Virtualization Concepts', 'Explain the differences between Type 1 and Type 2 hypervisors. Provide examples of each and discuss scenarios where one might be preferred over the other.', NULL, 'Type 1 hypervisors run directly on hardware (e.g., VMware ESXi, Microsoft Hyper-V), while Type 2 hypervisors run on a host OS (e.g., VMware Workstation, VirtualBox). Type 1 is used for enterprise server virtualization, while Type 2 is used for development and testing.', 'Essay'),
(39, 'NDG: Cloud and Virtualization Concepts', 'Discuss the benefits and challenges of cloud computing in modern IT infrastructure. How does virtualization support cloud computing?', NULL, 'Cloud computing benefits include scalability, cost efficiency, and accessibility. Challenges include security and vendor lock-in. Virtualization enables efficient resource allocation and scalability in cloud environments.', 'Essay'),
(40, 'NDG: Cloud and Virtualization Concepts', 'Describe the process of creating a virtual machine in VMware Workstation Pro. Highlight the key steps and considerations during the setup.', NULL, 'Key steps: Open VMware Workstation, create a new VM, select OS type, allocate memory, configure disk, install OS, install VMware Tools.', 'Essay'),
(41, 'NDG: Cloud and Virtualization Concepts', 'Oracle VM VirtualBox offers \"Guest Additions\". Explain what this feature is and how it enhances the performance and usability of virtual machines.', NULL, '\"Guest Additions\" enhances VM performance by enabling shared clipboard, drag-and-drop, improved display resolution, and seamless integration between host and guest OS.', 'Essay'),
(42, 'NDG: Cloud and Virtualization Concepts', 'In the context of VMware ICM Module 1, define what is meant by a Software-Defined Data Center (SDDC) and discuss its importance in enterprise environments.', NULL, 'SDDC refers to a data center where all infrastructure components (compute, storage, networking) are virtualized and delivered as a service. It enhances automation, scalability, and efficiency in enterprise IT.', 'Essay'),
(43, 'VMware Workstation Pro', 'Which of the following is NOT a feature of VMware Workstation Pro?', '[\"Snapshot management\", \"Virtual machine cloning\", \"Direct access to physical hardware\", \"Multiple VM support\"]', 'Direct access to physical hardware', 'MCQ'),
(44, 'VMware Workstation Pro', 'Which file format is used to store virtual machines in VMware Workstation?', '[\".vmdk\", \".iso\", \".vmx\", \".exe\"]', '.vmx', 'MCQ'),
(45, 'VMware Workstation Pro', 'In VMware Workstation Pro, which feature allows you to run multiple virtual machines simultaneously?', '[\"VMware Fusion\", \"Virtual Machine Network\", \"Virtual Machine Isolation\", \"VMware Workstation Pro\"]', 'VMware Workstation Pro', 'MCQ'),
(46, 'VMware Workstation Pro', 'Which of the following can be used to manage the virtual network adapters in VMware Workstation?', '[\"VMware vSphere\", \"VMware Network Editor\", \"VMware vCenter\", \"VMware Fusion\"]', 'VMware Network Editor', 'MCQ'),
(47, 'VMware Workstation Pro', 'VMware Workstation Pro is mainly used for:', '[\"Cloud-based applications\", \"Development and testing\", \"Data storage management\", \"Server virtualization\"]', 'Development and testing', 'MCQ'),
(48, 'Oracle VM VirtualBox', 'Which of the following is a key feature of Oracle VM VirtualBox?', '[\"Seamless mode\", \"Hyper-V integration\", \"Multiple guest OS per VM\", \"Native cloud integration\"]', 'Seamless mode', 'MCQ'),
(49, 'Oracle VM VirtualBox', 'In VirtualBox, which of the following file types represents a virtual machine disk image?', '[\".vmdk\", \".iso\", \".vdi\", \".vpx\"]', '.vdi', 'MCQ'),
(50, 'Oracle VM VirtualBox', 'Which of the following is NOT a supported host operating system for Oracle VM VirtualBox?', '[\"Windows\", \"Linux\", \"MacOS\", \"Chrome OS\"]', 'Chrome OS', 'MCQ'),
(51, 'Oracle VM VirtualBox', 'In VirtualBox, a VM can be connected to the host network using:', '[\"Host-only adapter\", \"Internal network\", \"NAT adapter\", \"All of the above\"]', 'All of the above', 'MCQ'),
(52, 'Oracle VM VirtualBox', 'Which component is required to enable better guest OS performance in VirtualBox?', '[\"Guest Additions\", \"Host Extensions\", \"VirtualBox Management Suite\", \"VirtualBox Host Add-ons\"]', 'Guest Additions', 'MCQ'),
(53, 'Microsoft Hyper-V', 'Which of the following best describes the role of Hyper-V in virtualization?', '[\"Cloud management tool\", \"Type 1 hypervisor for running virtual machines\", \"Type 2 hypervisor for running guest OS\", \"Virtual machine monitoring tool\"]', 'Type 1 hypervisor for running virtual machines', 'MCQ'),
(54, 'Microsoft Hyper-V', 'In Hyper-V, which of the following is a virtual switch type?', '[\"Internal\", \"External\", \"Private\", \"All of the above\"]', 'All of the above', 'MCQ'),
(55, 'Microsoft Hyper-V', 'Which of the following is NOT a feature of Microsoft Hyper-V?', '[\"Snapshot management\", \"Live migration\", \"Multiple virtual CPUs per VM\", \"Support for virtual hardware appliances\"]', 'Support for virtual hardware appliances', 'MCQ'),
(56, 'Microsoft Hyper-V', 'In Hyper-V, what feature allows you to move virtual machines between hosts without downtime?', '[\"Live Migration\", \"Snapshot\", \"Hyper-V Replica\", \"Dynamic Memory\"]', 'Live Migration', 'MCQ'),
(57, 'Microsoft Hyper-V', 'Which of the following is the correct command to start a virtual machine in Hyper-V using PowerShell?', '[\"Start-VM\", \"Run-VM\", \"Start-VirtualMachine\", \"VMStart\"]', 'Start-VM', 'MCQ'),
(58, 'VMware ICM Module 1 & 4', 'In VMware ICM, which component is responsible for centralized management of virtual machines?', '[\"vCenter Server\", \"vSphere Client\", \"VMware Workstation\", \"ESXi\"]', 'vCenter Server', 'MCQ'),
(59, 'VMware ICM Module 1 & 4', 'Which of the following is NOT a function of vSphere HA (High Availability)?', '[\"Automatic VM restart\", \"VMware Fault Tolerance\", \"Clustered VM migration\", \"VM monitoring\"]', 'Clustered VM migration', 'MCQ'),
(60, 'VMware ICM Module 1 & 4', 'In VMware vSphere, which feature allows VMs to move from one host to another without interruption?', '[\"VMware vMotion\", \"VMware Fault Tolerance\", \"VMware DRS\", \"VMware HA\"]', 'VMware vMotion', 'MCQ'),
(61, 'VMware ICM Module 1 & 4', 'Which of the following actions can be performed in the VMware vSphere Web Client?', '[\"VM cloning\", \"VM storage management\", \"VM migration\", \"All of the above\"]', 'All of the above', 'MCQ'),
(62, 'VMware ICM Module 1 & 4', 'Which of the following describes a virtual machine snapshot in VMware?', '[\"A point-in-time copy of a virtual machine\", \"A backup of the virtual machine\", \"A clone of the virtual machine\", \"A running instance of the VM\"]', 'A point-in-time copy of a virtual machine', 'MCQ'),
(63, 'NDG: Cloud and Virtualization Concepts', 'Which of the following is the key benefit of cloud computing?', '[\"Increased physical hardware requirements\", \"On-demand resource scaling\", \"Higher capital expenditure\", \"Fewer software solutions\"]', 'On-demand resource scaling', 'MCQ'),
(64, 'NDG: Cloud and Virtualization Concepts', 'What does IaaS stand for in cloud computing?', '[\"Infrastructure as a Service\", \"Integration as a Service\", \"Internet as a Service\", \"Information as a Service\"]', 'Infrastructure as a Service', 'MCQ'),
(65, 'NDG: Cloud and Virtualization Concepts', 'Which of the following is an example of a Platform as a Service (PaaS)?', '[\"AWS EC2\", \"Google App Engine\", \"Microsoft Azure Storage\", \"Amazon S3\"]', 'Google App Engine', 'MCQ'),
(66, 'NDG: Cloud and Virtualization Concepts', 'What is a hypervisor in the context of virtualization?', '[\"A virtual machine monitor\", \"A physical server\", \"A cloud service\", \"A storage solution\"]', 'A virtual machine monitor', 'MCQ'),
(67, 'NDG: Cloud and Virtualization Concepts', 'In a private cloud, who manages and controls the cloud environment?', '[\"A third-party provider\", \"An organization itself\", \"The general public\", \"A hybrid cloud provider\"]', 'An organization itself', 'MCQ'),
(68, 'VMware Workstation Pro', 'Which of the following is a virtual disk format used by VMware Workstation?', '[\".vmdk\", \".iso\", \".vdi\", \".vpx\"]', '.vmdk', 'MCQ'),
(69, 'VMware Workstation Pro', 'What feature allows VMware Workstation to run guest operating systems in a seamless window?', '[\"Seamless Mode\", \"VMware Tools\", \"SnapShot\", \"Full-screen mode\"]', 'Seamless Mode', 'MCQ'),
(70, 'VMware Workstation Pro', 'Which of the following is used to manage the virtual network in VMware Workstation?', '[\"Virtual Network Editor\", \"vSphere Client\", \"VMware Cloud Director\", \"VMware Fusion\"]', 'Virtual Network Editor', 'MCQ'),
(71, 'VMware Workstation Pro', 'How can you increase the memory allocation for a virtual machine in VMware Workstation?', '[\"Edit the VM settings\", \"Increase the physical host memory\", \"Upgrade the operating system\", \"Add a new hard drive\"]', 'Edit the VM settings', 'MCQ'),
(72, 'VMware Workstation Pro', 'What is the maximum number of virtual machines you can run simultaneously in VMware Workstation Pro?', '[\"Depends on the host hardware\", \"5\", \"10\", \"Unlimited\"]', 'Depends on the host hardware', 'MCQ'),
(73, 'Oracle VM VirtualBox', 'Which of the following is NOT supported in Oracle VM VirtualBox?', '[\"USB device pass-through\", \"3D acceleration\", \"vMotion\", \"Shared folders\"]', 'vMotion', 'MCQ'),
(74, 'Oracle VM VirtualBox', 'How can you create a snapshot of a virtual machine in Oracle VM VirtualBox?', '[\"Using VirtualBox Manager\", \"By copying the VM folder\", \"By using command-line tools\", \"By exporting the VM\"]', 'Using VirtualBox Manager', 'MCQ'),
(75, 'Oracle VM VirtualBox', 'Which of the following is required to run a 64-bit virtual machine in Oracle VM VirtualBox?', '[\"64-bit host OS\", \"64-bit processor\", \"Enabled hardware virtualization in BIOS\", \"All of the above\"]', 'All of the above', 'MCQ'),
(76, 'Oracle VM VirtualBox', 'Which network adapter mode provides internet access to a virtual machine in VirtualBox?', '[\"Bridged Adapter\", \"Host-Only Adapter\", \"Internal Network\", \"NAT\"]', 'NAT', 'MCQ'),
(77, 'Oracle VM VirtualBox', 'Which of the following is a correct method to add a new virtual disk to a virtual machine in VirtualBox?', '[\"Attach it in VM settings\", \"Insert it into the guest OS\", \"Download from the internet\", \"Create a new VM\"]', 'Attach it in VM settings', 'MCQ'),
(78, 'Microsoft Hyper-V', 'Which of the following Hyper-V components is used to manage virtual machines and hosts?', '[\"Hyper-V Manager\", \"VirtualBox Manager\", \"vSphere Client\", \"Hyper-V Console\"]', 'Hyper-V Manager', 'MCQ'),
(79, 'Microsoft Hyper-V', 'Which of the following storage options is recommended for running a Hyper-V virtual machine?', '[\"Direct-attached storage\", \"iSCSI\", \"Shared storage\", \"Network-attached storage\"]', 'Shared storage', 'MCQ'),
(80, 'Microsoft Hyper-V', 'Which of the following is NOT a type of virtual switch in Hyper-V?', '[\"External\", \"Private\", \"Internal\", \"Shared\"]', 'Shared', 'MCQ'),
(81, 'Microsoft Hyper-V', 'Which of the following is the default location for Hyper-V virtual machine files?', '[\"C:Program FilesMicrosoft Hyper-V\", \"C:UsersPublicDocumentsHyper-V\", \"C:Virtual Machines\", \"C:Hyper-V VM\"]', 'C:Virtual Machines', 'MCQ'),
(82, 'Microsoft Hyper-V', 'What is the purpose of Hyper-V Replica?', '[\"To replicate virtual machine data across servers\", \"To migrate virtual machines between hosts\", \"To backup virtual machines\", \"To integrate virtual machines with Active Directory\"]', 'To replicate virtual machine data across servers', 'MCQ'),
(83, 'VMware ICM Module 1 & 4', 'In VMware vSphere, what is the function of Distributed Resource Scheduler (DRS)?', '[\"Automatically moves VMs to balance resource usage\", \"Performs VM backups\", \"Patches VMs\", \"Creates VM templates\"]', 'Automatically moves VMs to balance resource usage', 'MCQ'),
(84, 'VMware ICM Module 1 & 4', 'Which of the following tasks is performed by VMware vCenter Server?', '[\"Managing and monitoring ESXi hosts\", \"Upgrading VMware Workstation\", \"Creating VM snapshots\", \"Setting up network switches\"]', 'Managing and monitoring ESXi hosts', 'MCQ'),
(85, 'VMware ICM Module 1 & 4', 'Which technology in VMware vSphere allows a virtual machine to migrate to another host without downtime?', '[\"vMotion\", \"vCenter\", \"vSphere HA\", \"VMware Workstation\"]', 'vMotion', 'MCQ'),
(86, 'VMware ICM Module 1 & 4', 'Which VMware feature allows centralized management of virtualized infrastructure?', '[\"vSphere Web Client\", \"vCenter Server\", \"vCloud Director\", \"VMware Horizon\"]', 'vCenter Server', 'MCQ'),
(87, 'VMware ICM Module 1 & 4', 'What is the purpose of VMware Fault Tolerance?', '[\"Provide a live replica of a VM for continuous availability\", \"Migrate virtual machines between ESXi hosts\", \"Backup virtual machine data\", \"Create virtual machine snapshots\"]', 'Provide a live replica of a VM for continuous availability', 'MCQ');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
