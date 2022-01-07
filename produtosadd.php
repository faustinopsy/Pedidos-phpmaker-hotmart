<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg12.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql12.php") ?>
<?php include_once "phpfn12.php" ?>
<?php include_once "produtosinfo.php" ?>
<?php include_once "usuarioinfo.php" ?>
<?php include_once "userfn12.php" ?>
<?php

//
// Page class
//

$produtos_add = NULL; // Initialize page object first

class cprodutos_add extends cprodutos {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{0EEDE62B-51BB-46D1-A6B6-B34E204C3205}";

	// Table name
	var $TableName = 'produtos';

	// Page object name
	var $PageObjName = 'produtos_add';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
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
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
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

		// Table object (produtos)
		if (!isset($GLOBALS["produtos"]) || get_class($GLOBALS["produtos"]) == "cprodutos") {
			$GLOBALS["produtos"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["produtos"];
		}

		// Table object (usuario)
		if (!isset($GLOBALS['usuario'])) $GLOBALS['usuario'] = new cusuario();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'produtos', TRUE);

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
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("produtoslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

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
		global $EW_EXPORT, $produtos;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($produtos);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
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
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("produtoslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "produtosview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->imagem->Upload->Index = $objForm->Index;
		$this->imagem->Upload->UploadFile();
	}

	// Load default values
	function LoadDefaultValues() {
		$this->Categoria->CurrentValue = NULL;
		$this->Categoria->OldValue = $this->Categoria->CurrentValue;
		$this->produto->CurrentValue = NULL;
		$this->produto->OldValue = $this->produto->CurrentValue;
		$this->imagem->Upload->DbValue = NULL;
		$this->imagem->OldValue = $this->imagem->Upload->DbValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->Categoria->FldIsDetailKey) {
			$this->Categoria->setFormValue($objForm->GetValue("x_Categoria"));
		}
		if (!$this->produto->FldIsDetailKey) {
			$this->produto->setFormValue($objForm->GetValue("x_produto"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->Categoria->CurrentValue = $this->Categoria->FormValue;
		$this->produto->CurrentValue = $this->produto->FormValue;
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
		$this->id->setDbValue($rs->fields('id'));
		$this->Categoria->setDbValue($rs->fields('Categoria'));
		$this->produto->setDbValue($rs->fields('produto'));
		$this->imagem->Upload->DbValue = $rs->fields('imagem');
		if (is_array($this->imagem->Upload->DbValue) || is_object($this->imagem->Upload->DbValue)) // Byte array
			$this->imagem->Upload->DbValue = ew_BytesToStr($this->imagem->Upload->DbValue);
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF) return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->id->DbValue = $row['id'];
		$this->Categoria->DbValue = $row['Categoria'];
		$this->produto->DbValue = $row['produto'];
		$this->imagem->Upload->DbValue = $row['imagem'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// Categoria
		// produto
		// imagem

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

		// Categoria
		if (strval($this->Categoria->CurrentValue) <> "") {
			$sFilterWrk = "`id`" . ew_SearchString("=", $this->Categoria->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `id`, `categoia` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categoria`";
		$sWhereWrk = "";
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->Categoria, $sWhereWrk); // Call Lookup selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->Categoria->ViewValue = $this->Categoria->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->Categoria->ViewValue = $this->Categoria->CurrentValue;
			}
		} else {
			$this->Categoria->ViewValue = NULL;
		}
		$this->Categoria->ViewCustomAttributes = "";

		// produto
		$this->produto->ViewValue = $this->produto->CurrentValue;
		$this->produto->ViewCustomAttributes = "";

		// imagem
		if (!ew_Empty($this->imagem->Upload->DbValue)) {
			$this->imagem->ImageWidth = 100;
			$this->imagem->ImageHeight = 200;
			$this->imagem->ImageAlt = $this->imagem->FldAlt();
			$this->imagem->ViewValue = "produtos_imagem_bv.php?" . "id=" . $this->id->CurrentValue;
			$this->imagem->IsBlobImage = ew_IsImageFile("image" . ew_ContentExt(substr($this->imagem->Upload->DbValue, 0, 11)));
		} else {
			$this->imagem->ViewValue = "";
		}
		$this->imagem->ViewCustomAttributes = "";

			// Categoria
			$this->Categoria->LinkCustomAttributes = "";
			$this->Categoria->HrefValue = "";
			$this->Categoria->TooltipValue = "";

			// produto
			$this->produto->LinkCustomAttributes = "";
			$this->produto->HrefValue = "";
			$this->produto->TooltipValue = "";

			// imagem
			$this->imagem->LinkCustomAttributes = "";
			if (!empty($this->imagem->Upload->DbValue)) {
				$this->imagem->HrefValue = "produtos_imagem_bv.php?id=" . $this->id->CurrentValue;
				$this->imagem->LinkAttrs["target"] = "_blank";
				if ($this->Export <> "") $this->imagem->HrefValue = ew_ConvertFullUrl($this->imagem->HrefValue);
			} else {
				$this->imagem->HrefValue = "";
			}
			$this->imagem->HrefValue2 = "produtos_imagem_bv.php?id=" . $this->id->CurrentValue;
			$this->imagem->TooltipValue = "";
			if ($this->imagem->UseColorbox) {
				$this->imagem->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
				$this->imagem->LinkAttrs["data-rel"] = "produtos_x_imagem";

				//$this->imagem->LinkAttrs["class"] = "ewLightbox ewTooltip img-thumbnail";
				//$this->imagem->LinkAttrs["data-placement"] = "bottom";
				//$this->imagem->LinkAttrs["data-container"] = "body";

				$this->imagem->LinkAttrs["class"] = "ewLightbox img-thumbnail";
			}
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// Categoria
			$this->Categoria->EditAttrs["class"] = "form-control";
			$this->Categoria->EditCustomAttributes = "";
			if (trim(strval($this->Categoria->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id`" . ew_SearchString("=", $this->Categoria->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `id`, `categoia` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `categoria`";
			$sWhereWrk = "";
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->Categoria, $sWhereWrk); // Call Lookup selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->Categoria->EditValue = $arwrk;

			// produto
			$this->produto->EditAttrs["class"] = "form-control";
			$this->produto->EditCustomAttributes = "";
			$this->produto->EditValue = ew_HtmlEncode($this->produto->CurrentValue);
			$this->produto->PlaceHolder = ew_RemoveHtml($this->produto->FldCaption());

			// imagem
			$this->imagem->EditAttrs["class"] = "form-control";
			$this->imagem->EditCustomAttributes = "";
			if (!ew_Empty($this->imagem->Upload->DbValue)) {
				$this->imagem->ImageWidth = 100;
				$this->imagem->ImageHeight = 200;
				$this->imagem->ImageAlt = $this->imagem->FldAlt();
				$this->imagem->EditValue = "produtos_imagem_bv.php?" . "id=" . $this->id->CurrentValue;
				$this->imagem->IsBlobImage = ew_IsImageFile("image" . ew_ContentExt(substr($this->imagem->Upload->DbValue, 0, 11)));
			} else {
				$this->imagem->EditValue = "";
			}
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->imagem);

			// Edit refer script
			// Categoria

			$this->Categoria->HrefValue = "";

			// produto
			$this->produto->HrefValue = "";

			// imagem
			if (!empty($this->imagem->Upload->DbValue)) {
				$this->imagem->HrefValue = "produtos_imagem_bv.php?id=" . $this->id->CurrentValue;
				$this->imagem->LinkAttrs["target"] = "_blank";
				if ($this->Export <> "") $this->imagem->HrefValue = ew_ConvertFullUrl($this->imagem->HrefValue);
			} else {
				$this->imagem->HrefValue = "";
			}
			$this->imagem->HrefValue2 = "produtos_imagem_bv.php?id=" . $this->id->CurrentValue;
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

		// Categoria
		$this->Categoria->SetDbValueDef($rsnew, $this->Categoria->CurrentValue, NULL, FALSE);

		// produto
		$this->produto->SetDbValueDef($rsnew, $this->produto->CurrentValue, NULL, FALSE);

		// imagem
		if (!$this->imagem->Upload->KeepFile) {
			if (is_null($this->imagem->Upload->Value)) {
				$rsnew['imagem'] = NULL;
			} else {
				$this->imagem->Upload->Resize(100, 200);
				$this->imagem->ImageWidth = 100; // Resize width
				$this->imagem->ImageHeight = 200; // Resize height
				$rsnew['imagem'] = $this->imagem->Upload->Value;
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {

				// Get insert id if necessary
				$this->id->setDbValue($conn->Insert_ID());
				$rsnew['id'] = $this->id->DbValue;
				if (!$this->imagem->Upload->KeepFile) {
				}
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
		}

		// imagem
		ew_CleanUploadTempPath($this->imagem, $this->imagem->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, "produtoslist.php", "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
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
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($produtos_add)) $produtos_add = new cprodutos_add();

// Page init
$produtos_add->Page_Init();

// Page main
$produtos_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$produtos_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fprodutosadd = new ew_Form("fprodutosadd", "add");

// Validate form
fprodutosadd.Validate = function() {
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

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fprodutosadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fprodutosadd.ValidateRequired = true;
<?php } else { ?>
fprodutosadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fprodutosadd.Lists["x_Categoria"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_categoia","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

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
<?php $produtos_add->ShowPageHeader(); ?>
<?php
$produtos_add->ShowMessage();
?>
<form name="fprodutosadd" id="fprodutosadd" class="<?php echo $produtos_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($produtos_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $produtos_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="produtos">
<input type="hidden" name="a_add" id="a_add" value="A">
<div>
<?php if ($produtos->Categoria->Visible) { // Categoria ?>
	<div id="r_Categoria" class="form-group">
		<label id="elh_produtos_Categoria" for="x_Categoria" class="col-sm-2 control-label ewLabel"><?php echo $produtos->Categoria->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produtos->Categoria->CellAttributes() ?>>
<span id="el_produtos_Categoria">
<select data-table="produtos" data-field="x_Categoria" data-value-separator="<?php echo ew_HtmlEncode(is_array($produtos->Categoria->DisplayValueSeparator) ? json_encode($produtos->Categoria->DisplayValueSeparator) : $produtos->Categoria->DisplayValueSeparator) ?>" id="x_Categoria" name="x_Categoria"<?php echo $produtos->Categoria->EditAttributes() ?>>
<?php
if (is_array($produtos->Categoria->EditValue)) {
	$arwrk = $produtos->Categoria->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($produtos->Categoria->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $produtos->Categoria->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($produtos->Categoria->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($produtos->Categoria->CurrentValue) ?>" selected><?php echo $produtos->Categoria->CurrentValue ?></option>
<?php
    }
}
?>
</select>
<?php if (AllowAdd(CurrentProjectID() . "categoria")) { ?>
<button type="button" title="<?php echo ew_HtmlTitle($Language->Phrase("AddLink")) . "&nbsp;" . $produtos->Categoria->FldCaption() ?>" onclick="ew_AddOptDialogShow({lnk:this,el:'x_Categoria',url:'categoriaaddopt.php'});" class="ewAddOptBtn btn btn-default btn-sm" id="aol_x_Categoria"><span class="glyphicon glyphicon-plus ewIcon"></span><span class="hide"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $produtos->Categoria->FldCaption() ?></span></button>
<?php } ?>
<?php
$sSqlWrk = "SELECT `id`, `categoia` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `categoria`";
$sWhereWrk = "";
$produtos->Categoria->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$produtos->Categoria->LookupFilters += array("f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$produtos->Lookup_Selecting($produtos->Categoria, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $produtos->Categoria->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x_Categoria" id="s_x_Categoria" value="<?php echo $produtos->Categoria->LookupFilterQuery() ?>">
</span>
<?php echo $produtos->Categoria->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produtos->produto->Visible) { // produto ?>
	<div id="r_produto" class="form-group">
		<label id="elh_produtos_produto" for="x_produto" class="col-sm-2 control-label ewLabel"><?php echo $produtos->produto->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produtos->produto->CellAttributes() ?>>
<span id="el_produtos_produto">
<input type="text" data-table="produtos" data-field="x_produto" name="x_produto" id="x_produto" size="30" maxlength="25" placeholder="<?php echo ew_HtmlEncode($produtos->produto->getPlaceHolder()) ?>" value="<?php echo $produtos->produto->EditValue ?>"<?php echo $produtos->produto->EditAttributes() ?>>
</span>
<?php echo $produtos->produto->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($produtos->imagem->Visible) { // imagem ?>
	<div id="r_imagem" class="form-group">
		<label id="elh_produtos_imagem" class="col-sm-2 control-label ewLabel"><?php echo $produtos->imagem->FldCaption() ?></label>
		<div class="col-sm-10"><div<?php echo $produtos->imagem->CellAttributes() ?>>
<span id="el_produtos_imagem">
<div id="fd_x_imagem">
<span title="<?php echo $produtos->imagem->FldTitle() ? $produtos->imagem->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($produtos->imagem->ReadOnly || $produtos->imagem->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="produtos" data-field="x_imagem" name="x_imagem" id="x_imagem"<?php echo $produtos->imagem->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_imagem" id= "fn_x_imagem" value="<?php echo $produtos->imagem->Upload->FileName ?>">
<input type="hidden" name="fa_x_imagem" id= "fa_x_imagem" value="0">
<input type="hidden" name="fs_x_imagem" id= "fs_x_imagem" value="0">
<input type="hidden" name="fx_x_imagem" id= "fx_x_imagem" value="<?php echo $produtos->imagem->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_imagem" id= "fm_x_imagem" value="<?php echo $produtos->imagem->UploadMaxFileSize ?>">
</div>
<table id="ft_x_imagem" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $produtos->imagem->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div>
<div class="form-group">
	<div class="col-sm-offset-2 col-sm-10">
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $produtos_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div>
</div>
</form>
<script type="text/javascript">
fprodutosadd.Init();
</script>
<?php
$produtos_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$produtos_add->Page_Terminate();
?>
