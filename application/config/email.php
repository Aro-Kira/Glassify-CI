<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| EMAIL CONFIGURATION
| -------------------------------------------------------------------
| Configuration for CodeIgniter Email Library
|
| For complete instructions please consult the 'Email' page of the User Guide.
|
*/

// Email protocol: 'mail', 'sendmail', or 'smtp'
$config['protocol'] = 'smtp'; // Changed to 'smtp' for Gmail

// SMTP Server Settings (Gmail SMTP)
// IMPORTANT: These settings are for the SENDING account (FROM address)
// The recipient email (what users enter in the form) can be ANY email address
$config['smtp_host'] = 'smtp.gmail.com';
$config['smtp_user'] = 'glassifytesting@gmail.com'; // Gmail account that SENDS emails (needs app password)
$config['smtp_pass'] = 'avhfzrqfstgavwbt'; // Gmail App Password for glassifytesting@gmail.com (see EMAIL_SETUP_INSTRUCTIONS.md)
$config['smtp_port'] = 465; // Using 465 with SSL (more reliable than 587 with TLS for Gmail)
$config['smtp_timeout'] = 30; // Increased timeout for data transmission
$config['smtp_crypto'] = 'ssl'; // 'ssl' for port 465, 'tls' for port 587
$config['smtp_keepalive'] = FALSE; // Don't keep connection alive

// Email Settings
$config['mailtype'] = 'html'; // 'text' or 'html'
$config['charset'] = 'utf-8';
$config['wordwrap'] = FALSE; // Disable wordwrap to avoid encoding issues
$config['wrapchars'] = 76;
$config['newline'] = "\r\n"; // CRLF for SMTP
$config['crlf'] = "\r\n"; // CRLF for SMTP

// Default from email
$config['from_email'] = 'glassifytesting@gmail.com';
$config['from_name'] = 'Glassify';

/* End of file email.php */
/* Location: ./application/config/email.php */

