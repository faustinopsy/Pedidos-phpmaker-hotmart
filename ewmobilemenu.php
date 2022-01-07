<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(2, "mmi_endereco", $Language->MenuPhrase("2", "MenuText"), "enderecolist.php", -1, "", AllowListMenu('{0EEDE62B-51BB-46D1-A6B6-B34E204C3205}endereco'), FALSE);
$RootMenu->AddMenuItem(3, "mmi_produtos", $Language->MenuPhrase("3", "MenuText"), "produtoslist.php", -1, "", AllowListMenu('{0EEDE62B-51BB-46D1-A6B6-B34E204C3205}produtos'), FALSE);
$RootMenu->AddMenuItem(6, "mmi_entregas", $Language->MenuPhrase("6", "MenuText"), "entregaslist.php", -1, "", AllowListMenu('{0EEDE62B-51BB-46D1-A6B6-B34E204C3205}entregas'), FALSE);
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
