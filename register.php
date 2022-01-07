<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$register = NULL; // Initialize page object first

class cregister extends cusuario {

	// Page ID
	var $PageID = 'register';

	// Project ID
	var $ProjectID = "{0EEDE62B-51BB-46D1-A6B6-B34E204C3205}";

	// Page object name
	var $PageObjName = 'register';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		return TRUE;
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsHttpPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (usuario)
		if (!isset($GLOBALS["usuario"]) || get_class($GLOBALS["usuario"]) == "cusuario") {
			$GLOBALS["usuario"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["usuario"];
		}
		if (!isset($GLOBALS["usuario"])) $GLOBALS["usuario"] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'register', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect($this->DBID);

		// User table object (usuario)
		if (!isset($UserTable)) {
			$UserTable = new cusuario();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		$this->Page_Redirecting($url);

		 // Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewRegisterForm";

	//
	// Page main
	//
	function Page_Main() {
		global $UserTableConn, $Security, $Language, $gsLanguage, $gsFormError, $objForm;
		global $Breadcrumb;

		// Set up Breadcrumb
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("register", "RegisterPage", $url, "", "", TRUE);
		$bUserExists = FALSE;
		if (@$_POST["a_register"] <> "") {

			// Get action
			$this->CurrentAction = $_POST["a_register"];
			$this->LoadFormValues(); // Get form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else {
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add

				// Check for duplicate User ID
				$sFilter = str_replace("%u", ew_AdjustSql($this->_LOGIN->CurrentValue, EW_USER_TABLE_DBID), EW_USER_NAME_FILTER);

				// Set up filter (SQL WHERE clause) and get return SQL
				// SQL constructor in usuario class, usuarioinfo.php

				$this->CurrentFilter = $sFilter;
				$sUserSql = $this->SQL();
				if ($rs = $UserTableConn->Execute($sUserSql)) {
					if (!$rs->EOF) {
						$bUserExists = TRUE;
						$this->RestoreFormValues(); // Restore form values
						$this->setFailureMessage($Language->Phrase("UserExists")); // Set user exist message
					}
					$rs->Close();
				}
				if (!$bUserExists) {
					$this->SendEmail = TRUE; // Send email on add success
					if ($this->AddRow()) { // Add record
						if ($this->getSuccessMessage() == "")
							$this->setSuccessMessage($Language->Phrase("RegisterSuccess")); // Register success

						// Auto login user
						if ($Security->ValidateUser($this->_LOGIN->CurrentValue, $this->SENHA->FormValue, TRUE)) {

							// Nothing to do
						}
						$this->Page_Terminate("index.php"); // Return
					} else {
						$this->RestoreFormValues(); // Restore form values
					}
				}
		}

		// Render row
		if ($this->CurrentAction == "F") { // Confirm page
			$this->RowType = EW_ROWTYPE_VIEW; // Render view
		} else {
			$this->RowType = EW_ROWTYPE_ADD; // Render add
		}
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
	}

	// Load default values
	function LoadDefaultValues() {
		$this->_LOGIN->CurrentValue = NULL;
		$this->_LOGIN->OldValue = $this->_LOGIN->CurrentValue;
		$this->SENHA->CurrentValue = NULL;
		$this->SENHA->OldValue = $this->SENHA->CurrentValue;
		$this->NOME->CurrentValue = NULL;
		$this->NOME->OldValue = $this->NOME->CurrentValue;
		$this->_EMAIL->CurrentValue = NULL;
		$this->_EMAIL->OldValue = $this->_EMAIL->CurrentValue;
		$this->TELEFONE->CurrentValue = NULL;
		$this->TELEFONE->OldValue = $this->TELEFONE->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->_LOGIN->FldIsDetailKey) {
			$this->_LOGIN->setFormValue($objForm->GetValue("x__LOGIN"));
		}
		if (!$this->SENHA->FldIsDetailKey) {
			$this->SENHA->setFormValue($objForm->GetValue("x_SENHA"));
		}
		$this->SENHA->ConfirmValue = $objForm->GetValue("c_SENHA");
		if (!$this->NOME->FldIsDetailKey) {
			$this->NOME->setFormValue($objForm->GetValue("x_NOME"));
		}
		if (!$this->_EMAIL->FldIsDetailKey) {
			$this->_EMAIL->setFormValue($objForm->GetValue("x__EMAIL"));
		}
		if (!$this->TELEFONE->FldIsDetailKey) {
			$this->TELEFONE->setFormValue($objForm->GetValue("x_TELEFONE"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->_LOGIN->CurrentValue = $this->_LOGIN->FormValue;
		$this->SENHA->CurrentValue = $this->SENHA->FormValue;
		$this->NOME->CurrentValue = $this->NOME->FormValue;
		$this->_EMAIL->CurrentValue = $this->_EMAIL->FormValue;
		$this->TELEFONE->CurrentValue = $this->TELEFONE->FormValue;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->ID->setDbValue($rs->fields('ID'));
		$this->_LOGIN->setDbValue($rs->fields('LOGIN'));
		$this->SENHA->setDbValue($rs->fields('SENHA'));
		$this->NOME->setDbValue($rs->fields('NOME'));
		$this->_EMAIL->setDbValue($rs->fields('EMAIL'));
		$this->TELEFONE->setDbValue($rs->fields('TELEFONE'));
		$this->ATIVO->setDbValue($rs->fields('ATIVO'));
		$this->LEVEL->setDbValue($rs->fields('LEVEL'));
		$this->memo->setDbValue($rs->fields('memo'));
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->ID->DbValue = $row['ID'];
		$this->_LOGIN->DbValue = $row['LOGIN'];
		$this->SENHA->DbValue = $row['SENHA'];
		$this->NOME->DbValue = $row['NOME'];
		$this->_EMAIL->DbValue = $row['EMAIL'];
		$this->TELEFONE->DbValue = $row['TELEFONE'];
		$this->ATIVO->DbValue = $row['ATIVO'];
		$this->LEVEL->DbValue = $row['LEVEL'];
		$this->memo->DbValue = $row['memo'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// ID
		// LOGIN
		// SENHA
		// NOME
		// EMAIL
		// TELEFONE
		// ATIVO
		// LEVEL
		// memo

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// ID
		$this->ID->ViewValue = $this->ID->CurrentValue;
		$this->ID->ViewCustomAttributes = "";

		// LOGIN
		$this->_LOGIN->ViewValue = $this->_LOGIN->CurrentValue;
		$this->_LOGIN->ViewCustomAttributes = "";

		// SENHA
		$this->SENHA->ViewValue = $this->SENHA->CurrentValue;
		$this->SENHA->ViewCustomAttributes = "";

		// NOME
		$this->NOME->ViewValue = $this->NOME->CurrentValue;
		$this->NOME->ViewCustomAttributes = "";

		// EMAIL
		$this->_EMAIL->ViewValue = $this->_EMAIL->CurrentValue;
		$this->_EMAIL->ViewCustomAttributes = "";

		// TELEFONE
		$this->TELEFONE->ViewValue = $this->TELEFONE->CurrentValue;
		$this->TELEFONE->ViewCustomAttributes = "";

		// ATIVO
		if (strval($this->ATIVO->CurrentValue) <> "") {
			$this->ATIVO->ViewValue = $this->ATIVO->OptionCaption($this->ATIVO->CurrentValue);
		} else {
			$this->ATIVO->ViewValue = NULL;
		}
		$this->ATIVO->ViewCustomAttributes = "";

		// LEVEL
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->LEVEL->CurrentValue) <> "") {
			$this->LEVEL->ViewValue = $this->LEVEL->OptionCaption($this->LEVEL->CurrentValue);
		} else {
			$this->LEVEL->ViewValue = NULL;
		}
		} else {
			$this->LEVEL->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->LEVEL->ViewCustomAttributes = "";

			// LOGIN
			$this->_LOGIN->LinkCustomAttributes = "";
			$this->_LOGIN->HrefValue = "";
			$this->_LOGIN->TooltipValue = "";

			// SENHA
			$this->SENHA->LinkCustomAttributes = "";
			$this->SENHA->HrefValue = "";
			$this->SENHA->TooltipValue = "";

			// NOME
			$this->NOME->LinkCustomAttributes = "";
			$this->NOME->HrefValue = "";
			$this->NOME->TooltipValue = "";

			// EMAIL
			$this->_EMAIL->LinkCustomAttributes = "";
			$this->_EMAIL->HrefValue = "";
			$this->_EMAIL->TooltipValue = "";

			// TELEFONE
			$this->TELEFONE->LinkCustomAttributes = "";
			$this->TELEFONE->HrefValue = "";
			$this->TELEFONE->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// LOGIN
			$this->_LOGIN->EditAttrs["class"] = "form-control";
			$this->_LOGIN->EditCustomAttributes = "";
			$this->_LOGIN->EditValue = ew_HtmlEncode($this->_LOGIN->CurrentValue);
			$this->_LOGIN->PlaceHolder = ew_RemoveHtml($this->_LOGIN->FldCaption());

			// SENHA
			$this->SENHA->EditAttrs["class"] = "form-control ewPasswordStrength";
			$this->SENHA->EditCustomAttributes = "";
			$this->SENHA->EditValue = ew_HtmlEncode($this->SENHA->CurrentValue);
			$this->SENHA->PlaceHolder = ew_RemoveHtml($this->SENHA->FldCaption());

			// NOME
			$this->NOME->EditAttrs["class"] = "form-control";
			$this->NOME->EditCustomAttributes = "";
			$this->NOME->EditValue = ew_HtmlEncode($this->NOME->CurrentValue);
			$this->NOME->PlaceHolder = ew_RemoveHtml($this->NOME->FldCaption());

			// EMAIL
			$this->_EMAIL->EditAttrs["class"] = "form-control";
			$this->_EMAIL->EditCustomAttributes = "";
			$this->_EMAIL->EditValue = ew_HtmlEncode($this->_EMAIL->CurrentValue);
			$this->_EMAIL->PlaceHolder = ew_RemoveHtml($this->_EMAIL->FldCaption());

			// TELEFONE
			$this->TELEFONE->EditAttrs["class"] = "form-control";
			$this->TELEFONE->EditCustomAttributes = "";
			$this->TELEFONE->EditValue = ew_HtmlEncode($this->TELEFONE->CurrentValue);
			$this->TELEFONE->PlaceHolder = ew_RemoveHtml($this->TELEFONE->FldCaption());

			// Edit refer script
			// LOGIN

			$this->_LOGIN->HrefValue = "";

			// SENHA
			$this->SENHA->HrefValue = "";

			// NOME
			$this->NOME->HrefValue = "";

			// EMAIL
			$this->_EMAIL->HrefValue = "";

			// TELEFONE
			$this->TELEFONE->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->_LOGIN->FldIsDetailKey && !is_null($this->_LOGIN->FormValue) && $this->_LOGIN->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterUserName"));
		}
		if (!$this->SENHA->FldIsDetailKey && !is_null($this->SENHA->FormValue) && $this->SENHA->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterPassword"));
		}
		if (!$this->NOME->FldIsDetailKey && !is_null($this->NOME->FormValue) && $this->NOME->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->NOME->FldCaption(), $this->NOME->ReqErrMsg));
		}
		if (!$this->_EMAIL->FldIsDetailKey && !is_null($this->_EMAIL->FormValue) && $this->_EMAIL->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_EMAIL->FldCaption(), $this->_EMAIL->ReqErrMsg));
		}
		if (!$this->TELEFONE->FldIsDetailKey && !is_null($this->TELEFONE->FormValue) && $this->TELEFONE->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->TELEFONE->FldCaption(), $this->TELEFONE->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		if ($rsold) {
			$this->LoadDbValues($rsold);
		}
		$rsnew = array();

		// LOGIN
		$this->_LOGIN->SetDbValueDef($rsnew, $this->_LOGIN->CurrentValue, "", FALSE);

		// SENHA
		$this->SENHA->SetDbValueDef($rsnew, $this->SENHA->CurrentValue, "", FALSE);

		// NOME
		$this->NOME->SetDbValueDef($rsnew, $this->NOME->CurrentValue, "", FALSE);

		// EMAIL
		$this->_EMAIL->SetDbValueDef($rsnew, $this->_EMAIL->CurrentValue, "", FALSE);

		// TELEFONE
		$this->TELEFONE->SetDbValueDef($rsnew, $this->TELEFONE->CurrentValue, "", FALSE);

		// ID
		// Call Row Inserting event

		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->ID->setDbValue($conn->Insert_ID());
				$rsnew['ID'] = $this->ID->DbValue;
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);

			// Call User Registered event
			$this->User_Registered($rsnew);
		}
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// User Registered event
	function User_Registered(&$rs) {

	  //echo "User_Registered";
	}

	// User Activated event
	function User_Activated(&$rs) {

	  //echo "User_Activated";
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($register)) $register = new cregister();

// Page init
$register->Page_Init();

// Page main
$register->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$register->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "register";
var CurrentForm = fregister = new ew_Form("fregister", "register");

// Validate form
fregister.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "__LOGIN");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterUserName"));
			elm = this.GetElements("x" + infix + "_SENHA");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterPassword"));
			if ($(fobj.x_SENHA).hasClass("ewPasswordStrength") && !$(fobj.x_SENHA).data("validated"))
				return this.OnError(fobj.x_SENHA, ewLanguage.Phrase("PasswordTooSimple"));
			if (fobj.c_SENHA.value != fobj.x_SENHA.value)
				return this.OnError(fobj.c_SENHA, ewLanguage.Phrase("MismatchPassword"));
			elm = this.GetElements("x" + infix + "_NOME");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $usuario->NOME->FldCaption(), $usuario->NOME->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__EMAIL");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $usuario->_EMAIL->FldCaption(), $usuario->_EMAIL->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_TELEFONE");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $usuario->TELEFONE->FldCaption(), $usuario->TELEFONE->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
	return true;
}

// Form_CustomValidate event
fregister.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fregister.ValidateRequired = true;
<?php } else { ?>
fregister.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php $Breadcrumb->Render(); ?>
<?php echo $Language->SelectionForm(); ?>
<div class="clearfix"></div>
</div>
<?php $register->ShowPageHeader(); ?>
<?php
$register->ShowMessage();
?>
<form name="fregister" id="fregister" class="<?php echo $register->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($register->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $register->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="usuario">
<input type="hidden" name="a_register" id="a_register" value="A">
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<?php if ($usuario->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } ?>
<div>
<?php if ($usuario->_LOGIN->Visible) { // LOGIN ?>
	<div id="r__LOGIN" class="form-group">
		<label id="elh_usuario__LOGIN" for="x__LOGIN" class="col-sm-2 control-label ewLabel"><?php echo $usuario->_LOGIN->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $usuario->_LOGIN->CellAttributes() ?>>
<?php if ($usuario->CurrentAction <> "F") { ?>
<span id="el_usuario__LOGIN">
<input type="text" data-table="usuario" data-field="x__LOGIN" name="x__LOGIN" id="x__LOGIN" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($usuario->_LOGIN->getPlaceHolder()) ?>" value="<?php echo $usuario->_LOGIN->EditValue ?>"<?php echo $usuario->_LOGIN->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_usuario__LOGIN">
<span<?php echo $usuario->_LOGIN->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $usuario->_LOGIN->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="usuario" data-field="x__LOGIN" name="x__LOGIN" id="x__LOGIN" value="<?php echo ew_HtmlEncode($usuario->_LOGIN->FormValue) ?>">
<?php } ?>
<?php echo $usuario->_LOGIN->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($usuario->SENHA->Visible) { // SENHA ?>
	<div id="r_SENHA" class="form-group">
		<label id="elh_usuario_SENHA" for="x_SENHA" class="col-sm-2 control-label ewLabel"><?php echo $usuario->SENHA->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $usuario->SENHA->CellAttributes() ?>>
<?php if ($usuario->CurrentAction <> "F") { ?>
<span id="el_usuario_SENHA">
<div class="input-group" id="ig_x_SENHA">
<input type="text" data-password-strength="pst_x_SENHA" data-password-generated="pgt_x_SENHA" data-table="usuario" data-field="x_SENHA" name="x_SENHA" id="x_SENHA" value="<?php echo $usuario->SENHA->EditValue ?>" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($usuario->SENHA->getPlaceHolder()) ?>"<?php echo $usuario->SENHA->EditAttributes() ?>>
<span class="input-group-btn">
	<button type="button" class="btn btn-default ewPasswordGenerator" title="<?php echo ew_HtmlTitle($Language->Phrase("GeneratePassword")) ?>" data-password-field="x_SENHA" data-password-confirm="c_SENHA" data-password-strength="pst_x_SENHA" data-password-generated="pgt_x_SENHA"><?php echo $Language->Phrase("GeneratePassword") ?></button>
</span>
</div>
<span class="help-block" id="pgt_x_SENHA" style="display: none;"></span>
<div class="progress ewPasswordStrengthBar" id="pst_x_SENHA" style="display: none;">
	<div class="progress-bar" role="progressbar"></div>
</div>
</span>
<?php } else { ?>
<span id="el_usuario_SENHA">
<span<?php echo $usuario->SENHA->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $usuario->SENHA->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="usuario" data-field="x_SENHA" name="x_SENHA" id="x_SENHA" value="<?php echo ew_HtmlEncode($usuario->SENHA->FormValue) ?>">
<?php } ?>
<?php echo $usuario->SENHA->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($usuario->SENHA->Visible) { // SENHA ?>
	<div id="r_c_SENHA" class="form-group">
		<label id="elh_c_usuario_SENHA" for="c_SENHA" class="col-sm-2 control-label ewLabel"><?php echo $Language->Phrase("Confirm") ?> <?php echo $usuario->SENHA->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $usuario->SENHA->CellAttributes() ?>>
<?php if ($usuario->CurrentAction <> "F") { ?>
<span id="el_c_usuario_SENHA">
<input type="text" data-table="usuario" data-field="c_SENHA" name="c_SENHA" id="c_SENHA" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($usuario->SENHA->getPlaceHolder()) ?>" value="<?php echo $usuario->SENHA->EditValue ?>"<?php echo $usuario->SENHA->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_c_usuario_SENHA">
<span<?php echo $usuario->SENHA->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $usuario->SENHA->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="usuario" data-field="c_SENHA" name="c_SENHA" id="c_SENHA" value="<?php echo ew_HtmlEncode($usuario->SENHA->FormValue) ?>">
<?php } ?>
</div></div>
	</div>
<?php } ?>
<?php if ($usuario->NOME->Visible) { // NOME ?>
	<div id="r_NOME" class="form-group">
		<label id="elh_usuario_NOME" for="x_NOME" class="col-sm-2 control-label ewLabel"><?php echo $usuario->NOME->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $usuario->NOME->CellAttributes() ?>>
<?php if ($usuario->CurrentAction <> "F") { ?>
<span id="el_usuario_NOME">
<input type="text" data-table="usuario" data-field="x_NOME" name="x_NOME" id="x_NOME" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($usuario->NOME->getPlaceHolder()) ?>" value="<?php echo $usuario->NOME->EditValue ?>"<?php echo $usuario->NOME->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_usuario_NOME">
<span<?php echo $usuario->NOME->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $usuario->NOME->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="usuario" data-field="x_NOME" name="x_NOME" id="x_NOME" value="<?php echo ew_HtmlEncode($usuario->NOME->FormValue) ?>">
<?php } ?>
<?php echo $usuario->NOME->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($usuario->_EMAIL->Visible) { // EMAIL ?>
	<div id="r__EMAIL" class="form-group">
		<label id="elh_usuario__EMAIL" for="x__EMAIL" class="col-sm-2 control-label ewLabel"><?php echo $usuario->_EMAIL->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $usuario->_EMAIL->CellAttributes() ?>>
<?php if ($usuario->CurrentAction <> "F") { ?>
<span id="el_usuario__EMAIL">
<input type="text" data-table="usuario" data-field="x__EMAIL" name="x__EMAIL" id="x__EMAIL" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($usuario->_EMAIL->getPlaceHolder()) ?>" value="<?php echo $usuario->_EMAIL->EditValue ?>"<?php echo $usuario->_EMAIL->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_usuario__EMAIL">
<span<?php echo $usuario->_EMAIL->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $usuario->_EMAIL->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="usuario" data-field="x__EMAIL" name="x__EMAIL" id="x__EMAIL" value="<?php echo ew_HtmlEncode($usuario->_EMAIL->FormValue) ?>">
<?php } ?>
<?php echo $usuario->_EMAIL->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($usuario->TELEFONE->Visible) { // TELEFONE ?>
	<div id="r_TELEFONE" class="form-group">
		<label id="elh_usuario_TELEFONE" for="x_TELEFONE" class="col-sm-2 control-label ewLabel"><?php echo $usuario->TELEFONE->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="col-sm-10"><div<?php echo $usuario->TELEFONE->CellAttributes() ?>>
<?php if ($usuario->CurrentAction <> "F") { ?>
<span id="el_usuario_TELEFONE">
<input type="text" data-table="usuario" data-field="x_TELEFONE" name="x_TELEFONE" id="x_TELEFONE" size="30" maxlength="45" placeholder="<?php echo ew_HtmlEncode($usuario->TELEFONE->getPlaceHolder()) ?>" value="<?php echo $usuario->TELEFONE->EditValue ?>"<?php echo $usuario->TELEFONE->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_usuario_TELEFONE">
<span<?php echo $usuario->TELEFONE->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $usuario->TELEFONE->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="usuario" data-field="x_TELEFONE" name="x_TELEFONE" id="x_TELEFONE" value="<?php echo ew_HtmlEncode($usuario->TELEFONE->FormValue) ?>">
<?php } ?>
<?php echo $usuario->TELEFONE->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<?php if ($usuario->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_register.value='F';"><?php echo $Language->Phrase("RegisterBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_register.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
	</div>
</div>
</form>
<script type="text/javascript">
fregister.Init();
</script>
<?php
$register->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$register->Page_Terminate();
?>
