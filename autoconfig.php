<?php
$config = simplexml_load_file("config.xml");

$request = simplexml_load_string(file_get_contents("php://input"));

header( 'Content-Type: application/xml' );
?>
<?php echo '<?xml version="1.0" encoding="utf-8" ?>'.PHP_EOL; ?>
<clientConfig version="1.1">
  <emailProvider id="<?php echo $config->domain; ?>">
    <domain><?php echo $config->domain; ?></domain>
    <displayName><?php echo $config->displayname; ?></displayName>
    <displayShortName><?php echo $config->displayshortname; ?></displayShortName>
    <?php if($config->useImap == 'true') { ?>
    <incomingServer type="imap">
      <hostname><?php echo $config->imap->server; ?></hostname>
      <port><?php echo $config->imap->port; ?></port>
      <socketType><?php switch($config->imap->encryption) { case 'none': echo 'plain'; break; case 'ssl': echo 'SSL'; break; case 'starttls': echo 'STARTTLS'; break; } ?></socketType>
      <authentication><?php echo $config->imap->password == 'encrypted' ? 'password-encrypted' : 'password-cleartext'; ?></authentication>
      <username><?php echo $config->imap->loginname == 'email' ? '%EMAILADDRESS%' : '%EMAILLOCALPART%'; ?></username>
    </incomingServer>
    <?php } if($config->usePop == 'true') { ?>
    <incomingServer type="pop3">
      <hostname><?php echo $config->pop->server; ?></hostname>
      <port><?php echo $config->pop->port; ?></port>
      <socketType><?php switch($config->pop->encryption) { case 'none': echo 'plain'; break; case 'ssl': echo 'SSL'; break; case 'starttls': echo 'STARTTLS'; break; } ?></socketType>
      <authentication><?php echo $config->pop->password == 'encrypted' ? 'password-encrypted' : 'password-cleartext'; ?></authentication>
      <username><?php echo $config->pop->loginname == 'email' ? '%EMAILADDRESS%' : '%EMAILLOCALPART%'; ?></username>
    </incomingServer>
    <?php } ?>
    <outgoingServer type="smtp">
      <hostname><?php echo $config->smtp->server; ?></hostname>
      <port><?php echo $config->smtp->port; ?></port>
      <socketType><?php switch($config->smtp->encryption) { case 'none': echo 'plain'; break; case 'ssl': echo 'SSL'; break; case 'starttls': echo 'STARTTLS'; break; } ?></socketType>
      <authentication><?php echo $config->smtp->password == 'encrypted' ? 'password-encrypted' : 'password-cleartext'; ?></authentication>
      <username><?php echo $config->smtp->loginname == 'email' ? '%EMAILADDRESS%' : '%EMAILLOCALPART%'; ?></username>
    </outgoingServer>
  </emailProvider>
</clientConfig>
