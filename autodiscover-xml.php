<?php
$config = simplexml_load_file("config.xml");

$request = simplexml_load_string(file_get_contents("php://input"));
$email=$request->Request->EMailAddress;
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    throw new Exception('Email invalid');
}
$username = explode('@', $email)[0];

header( 'Content-Type: application/xml' );
?>
<?php echo '<?xml version="1.0" encoding="utf-8" ?>'.PHP_EOL; ?>
<Autodiscover xmlns="http://schemas.microsoft.com/exchange/autodiscover/responseschema/2006">
  <Response xmlns="http://schemas.microsoft.com/exchange/autodiscover/outlook/responseschema/2006a">
    <User>
      <DisplayName><?php echo $config->displayname; ?></DisplayName>
    </User>
    <Account>
      <AccountType>email</AccountType>
      <Action>settings</Action>
      <?php if($config->usePop == 'true') { ?>
      <Protocol>
        <Type>POP3</Type>
        <Server><?php echo $config->pop->server; ?></Server>
        <Port><?php echo $config->pop->port; ?></Port>
        <LoginName><?php echo $config->pop->loginname == 'email' ? $email : $username; ?></LoginName>
        <DomainRequired><?php echo $config->pop->loginname == 'email' ? 'on' : 'off'; ?></DomainRequired>
        <SPA><?php echo $config->pop->password == 'encrypted' ? 'on' : 'off'; ?></SPA>
        <SSL><?php echo $config->pop->encryption != 'none' ? 'on' : 'off'; ?></SSL>
        <AuthRequired>on</AuthRequired>
      </Protocol>
      <?php } if($config->useImap == 'true') { ?>
      <Protocol>
        <Type>IMAP</Type>
        <Server><?php echo $config->imap->server; ?></Server>
        <Port><?php echo $config->imap->port; ?></Port>
        <DomainRequired><?php echo $config->imap->loginname == 'email' ? 'on' : 'off'; ?></DomainRequired>
        <LoginName><?php echo $config->imap->loginname == 'email' ? $email : $username; ?></LoginName>
        <SPA><?php echo $config->imap->password == 'encrypted' ? 'on' : 'off'; ?></SPA>
        <SSL><?php echo $config->imap->encryption != 'none' ? 'on' : 'off'; ?></SSL>
        <AuthRequired>on</AuthRequired>
      </Protocol>
      <?php } ?>
      <Protocol>
        <Type>SMTP</Type>
        <Server><?php echo $config->smtp->server; ?></Server>
        <Port><?php echo $config->smtp->port; ?></Port>
        <DomainRequired><?php echo $config->smtp->loginname == 'email' ? 'on' : 'off'; ?></DomainRequired>
        <LoginName><?php echo $config->smtp->loginname == 'email' ? $email : $username; ?></LoginName>
        <SPA><?php echo $config->smtp->password == 'encrypted' ? 'on' : 'off'; ?></SPA>
        <SSL><?php echo $config->smtp->encryption != 'none' ? 'on' : 'off'; ?></SSL>
        <AuthRequired>on</AuthRequired>
        <UsePOPAuth>off</UsePOPAuth>
        <SMTPLast>off</SMTPLast>
      </Protocol>
    </Account>
  </Response>
</Autodiscover>
