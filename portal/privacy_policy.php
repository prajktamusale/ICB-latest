<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if (!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
  header("Location: ./index.php");
}
if (!isset($_SESSION['type'])) {
  header("Location: ../index.html");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Details</title>
  <style>
    <?php include "./css/header.css" ?>
  </style>
  <link rel="stylesheet" href="./css/bottomNav.css">
  <?php
  echo "<script>localStorage.setItem('id', " . $_SESSION['id'] . ");</script>";
  ?>
  <script src="./js/sliderAccordian.js" defer></script>
  <script src="./js/sideBar.js" defer></script>
</head>

<body>
  <?php include './header.php'; ?>
<h1> PRIVACY POLICY</h1>
<p>At I Care for Bharat, we are committed to protecting the privacy of our users, associates, partners, and all stakeholders who interact with our platform. This Privacy Policy outlines how we collect, use, store, and protect your personal data in accordance with applicable laws and industry standards.</p>

<h2>1.Information We Collect</h2>
<p>We collect various types of information to ensure seamless service delivery and legal compliance:</p>
<ul>
  <li><strong>KYC Information:</strong>Including but not limited to full name, address, phone number, email ID, government-issued ID numbers (such as PAN, Aadhaar), and banking details.</li>

<li><strong>User Categories:</strong> Information is collected from associates, partners, employees, administrators, and other users interacting with the platform.</li>
<li><strong>Transactional Information:</strong> Payment data and financial records processed through third-party gateways such as Razorpay.
</li>
<li><strong>Technical Data:</strong>IP address, device information, browser type, location data, and interaction logs for improving user experience.</li>
<li><strong>Communication Data:</strong>Messages, queries, and feedback shared through our platform.
</li>
</ul>
<h2>2. Purpose of Data Collection
</h2>
<p>The data collected is utilized for the following purposes:
  </p>
<ul>
  <li>To establish and verify user identities through KYC.</li>
  <li>To manage administrative roles and access control.
  </li>
  <li>To process payments and withdrawals through secure gateways.
  </li>
  <li>To ensure compliance with legal and financial regulations.
  </li>
  <li>To improve the efficiency, functionality, and user experience of the platform.
  </li>
  <li>To send necessary communications including updates, alerts, or service notifications.</li>
</ul>
<h2>3.Nature of Services Offered</h2>
<p>“I Care for Bharat” is a multi-level marketing (MLM) platform designed to support associate on boarding, network management, reward distribution, and internal communication. The services include:</p>
<ul>
  <li>Associate and partner registration
  </li>
  <li>User dashboard and analytics
  </li>
  <li>Commission and incentive tracking
  </li>
  <li>Customer support system
  </li>
  <li>Product promotions and purchases.
  </li>
    <li>Training modules and support assistance.
    </li>
      <li>Integration with third-party payment systems (Razorpay).
      </li>
</ul>
<h2>4. Sharing of Information
</h2>
<p>We may share your information in the following circumstances:
  </p>
  <ul>
  <li>With verified partners and service providers to fulfil contractual obligations.
  </li>
  <li>With payment processing entities for secure financial transactions.
  </li>
  <li>With internal teams for customer support and system management.
  </li>
  <li>With government or legal authorities where required by law.
  </li>
  <li>With successors in the event of a merger, acquisition, or asset sale.
  </li>
</ul>
<h2>5. Data Security
</h2>
<p>We implement a variety of industry-standard security measures to ensure the confidentiality and integrity of your data. These include encryption, firewalls, access control protocols, and regular audits. However, while we strive to use commercially acceptable means to protect your data, no method of transmission over the internet is entirely secure.</p>
<h2>6. Data Retention
</h2>
<p>Personal data is retained for as long as it is necessary to fulfil the purposes outlined in this policy or as required by law. Post this period, the data is either securely deleted or anonymized.
</p>
<h2>7. User Rights
</h2>
<ul>
  <li>Access your personal information.
  </li>
  <li>Request corrections or updates.
  </li>
  <li>Withdraw your consent for specific data uses.
  </li>
  <li>Request deletion of your personal data (subject to legal limitations).
  </li>
  <li>Lodge a complaint with a relevant authority if you believe your rights are being violated.</li>
</ul>
<h2>8. Use of Cookies and Tracking Tools
</h2>
<p>Our platform may use cookies and similar technologies to enhance user experience, track activity, and analyse platform usage. You may choose to disable cookies through your browser settings.</p>
<h2>9. Third-Party Services
</h2>
<p>This Privacy Policy does not apply to services offered by third parties, including Razorpay and other payment gateways or external websites linked through our platform. We encourage you to review their respective privacy policies.</p>
<h2>10. Updates to the Policy</h2>
<p>We reserve the right to update or amend this Privacy Policy at our discretion. The updated version will be posted on our website with the revised effective date. Continued use of the platform after changes signifies your acceptance of the new terms.</p>
<h2>11. Contact Us
</h2>
<p>For any questions, concerns, or requests regarding this Privacy Policy, please reach out to:<br>
  <strong>Email:</strong>i@careforbharat.in<br>
  <strong>Phone:</strong>020 29980420 <br>
  <strong>Address:</strong> Flat No. 7, Shefali Apartment, 15A/10, off Karve Road, behind Chinese Room, Khilarewadi, Erandwane, Pune, Maharashtra 411004
</p>

</body>
</html>