CREATE DATABASE IF NOT EXISTS flashcards_db;
USE flashcards_db;

CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chapter VARCHAR(50) NOT NULL,
    question TEXT NOT NULL,
    options TEXT NULL,
    answer TEXT NOT NULL,
    question_type ENUM('MCQ', 'Match', 'Fill', 'Essay') NOT NULL
);

-- Insert Multiple Choice Questions (MCQs)
INSERT INTO questions (chapter, question, options, answer, question_type) VALUES
('chapter1', 'Which of the following is NOT a characteristic of cloud computing?', 
 '["On-demand self-service", "Broad network access", "Limited resource pooling", "Rapid elasticity"]', 
 'Limited resource pooling', 'MCQ'),

('chapter1', 'In virtualization, a Type 1 hypervisor is also known as:', 
 '["Hosted hypervisor", "Bare-metal hypervisor", "Client-side hypervisor", "Nested hypervisor"]', 
 'Bare-metal hypervisor', 'MCQ'),

('chapter1', 'VMware Workstation Pro is classified as which type of hypervisor?', 
 '["Type 1 hypervisor", "Type 2 hypervisor", "Type 3 hypervisor", "Cloud-based hypervisor"]', 
 'Type 2 hypervisor', 'MCQ'),

('chapter1', 'Which feature in Oracle VM VirtualBox allows seamless integration of guest windows into the host desktop?', 
 '["Guest Additions", "Shared Folders", "Seamless Mode", "Virtual Networking"]', 
 'Seamless Mode', 'MCQ'),

('chapter1', 'Microsoft Hyper-V allows the creation of three types of virtual switches. Which of the following is NOT one of them?', 
 '["External", "Internal", "Private", "Public"]', 
 'Public', 'MCQ'),

('chapter1', 'In cloud service models, Platform as a Service (PaaS) provides:', 
 '["Virtualized hardware resources", "Development tools and environment", "Ready-to-use software applications", "Networking hardware and protocols"]', 
 'Development tools and environment', 'MCQ'),

('chapter1', 'VMware ESXi is best described as:', 
 '["A Type 2 hypervisor requiring a host OS", "A cloud service model", "A Type 1 hypervisor for server virtualization", "A desktop virtualization application"]', 
 'A Type 1 hypervisor for server virtualization', 'MCQ'),

('chapter1', 'The Rapid Elasticity characteristic of cloud computing refers to:', 
 '["The ability to stretch network cables", "Quick scaling of resources up or down as needed", "Fast data transmission speeds", "Immediate software updates"]', 
 'Quick scaling of resources up or down as needed', 'MCQ'),

('chapter1', 'In VMware ICM Module 4, the recommended practice before making significant changes to a VM is to:', 
 '["Clone the VM", "Take a snapshot", "Shut down the VM", "Increase the VM\'s memory"]', 
 'Take a snapshot', 'MCQ'),

('chapter1', 'Which of the following is a benefit of using virtualization?', 
 '["Increased hardware costs", "Reduced server utilization", "Isolation of applications", "Limited scalability"]', 
 'Isolation of applications', 'MCQ');

-- Insert Match-the-Following Questions
INSERT INTO questions (chapter, question, options, answer, question_type) VALUES
('chapter1', 'Match the virtualization tools to their appropriate descriptions:', 
 '["1. VMware Workstation Pro", "2. Oracle VM VirtualBox", "3. Microsoft Hyper-V", "4. VMware ESXi", "5. Docker"]', 
 '["1 - A Type 2 hypervisor used for running multiple OS on a single PC",
   "2 - An open-source Type 2 hypervisor supporting cross-platform use",
   "3 - A Type 1 hypervisor designed for Windows environments",
   "4 - A bare-metal hypervisor used in enterprise environments",
   "5 - A containerization platform for deploying applications"]', 
 'Match');

-- Insert Fill-in-the-Blanks Questions
INSERT INTO questions (chapter, question, answer, question_type) VALUES
('chapter1', 'In cloud computing, the service model where users are provided with applications over the internet is known as ______________.', 
 'Software as a Service (SaaS)', 'Fill'),

('chapter1', '________________ is a software layer that enables multiple operating systems to share a single hardware host.', 
 'Hypervisor', 'Fill'),

('chapter1', 'The ______________ feature in virtualization allows you to save the state of a virtual machine at a specific point in time.', 
 'Snapshot', 'Fill'),

('chapter1', '________________ is the process of creating a virtual version of something, such as hardware or a storage device.', 
 'Virtualization', 'Fill'),

('chapter1', 'In VMware, ______________ provides centralized management of virtualized hosts and VMs.', 
 'vCenter Server', 'Fill');

-- Insert Short Essay Questions
INSERT INTO questions (chapter, question, answer, question_type) VALUES
('chapter1', 'Explain the differences between Type 1 and Type 2 hypervisors. Provide examples of each and discuss scenarios where one might be preferred over the other.', 
 'Type 1 hypervisors run directly on hardware (e.g., VMware ESXi, Microsoft Hyper-V), while Type 2 hypervisors run on a host OS (e.g., VMware Workstation, VirtualBox). Type 1 is used for enterprise server virtualization, while Type 2 is used for development and testing.', 'Essay'),

('chapter1', 'Discuss the benefits and challenges of cloud computing in modern IT infrastructure. How does virtualization support cloud computing?', 
 'Cloud computing benefits include scalability, cost efficiency, and accessibility. Challenges include security and vendor lock-in. Virtualization enables efficient resource allocation and scalability in cloud environments.', 'Essay'),

('chapter1', 'Describe the process of creating a virtual machine in VMware Workstation Pro. Highlight the key steps and considerations during the setup.', 
 'Key steps: Open VMware Workstation, create a new VM, select OS type, allocate memory, configure disk, install OS, install VMware Tools.', 'Essay'),

('chapter1', 'Oracle VM VirtualBox offers "Guest Additions". Explain what this feature is and how it enhances the performance and usability of virtual machines.', 
 '"Guest Additions" enhances VM performance by enabling shared clipboard, drag-and-drop, improved display resolution, and seamless integration between host and guest OS.', 'Essay'),

('chapter1', 'In the context of VMware ICM Module 1, define what is meant by a Software-Defined Data Center (SDDC) and discuss its importance in enterprise environments.', 
 'SDDC refers to a data center where all infrastructure components (compute, storage, networking) are virtualized and delivered as a service. It enhances automation, scalability, and efficiency in enterprise IT.', 'Essay');
