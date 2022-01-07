<?php

// Global variable for table object
$usuario = NULL;

//
// Table class for usuario
//
class cusuario extends cTable {
	var $ID;
	var $_LOGIN;
	var $SENHA;
	var $NOME;
	var $_EMAIL;
	var $TELEFONE;
	var $ATIVO;
	var $LEVEL;
	var $memo;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'usuario';
		$this->TableName = 'usuario';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`usuario`";
		$this->DBID = 'DB';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 1;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// ID
		$this->ID = new cField('usuario', 'usuario', 'x_ID', 'ID', '`ID`', '`ID`', 3, -1, FALSE, '`ID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->ID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ID'] = &$this->ID;

		// LOGIN
		$this->_LOGIN = new cField('usuario', 'usuario', 'x__LOGIN', 'LOGIN', '`LOGIN`', '`LOGIN`', 200, -1, FALSE, '`LOGIN`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['LOGIN'] = &$this->_LOGIN;

		// SENHA
		$this->SENHA = new cField('usuario', 'usuario', 'x_SENHA', 'SENHA', '`SENHA`', '`SENHA`', 200, -1, FALSE, '`SENHA`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['SENHA'] = &$this->SENHA;

		// NOME
		$this->NOME = new cField('usuario', 'usuario', 'x_NOME', 'NOME', '`NOME`', '`NOME`', 200, -1, FALSE, '`NOME`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['NOME'] = &$this->NOME;

		// EMAIL
		$this->_EMAIL = new cField('usuario', 'usuario', 'x__EMAIL', 'EMAIL', '`EMAIL`', '`EMAIL`', 200, -1, FALSE, '`EMAIL`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['EMAIL'] = &$this->_EMAIL;

		// TELEFONE
		$this->TELEFONE = new cField('usuario', 'usuario', 'x_TELEFONE', 'TELEFONE', '`TELEFONE`', '`TELEFONE`', 200, -1, FALSE, '`TELEFONE`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->fields['TELEFONE'] = &$this->TELEFONE;

		// ATIVO
		$this->ATIVO = new cField('usuario', 'usuario', 'x_ATIVO', 'ATIVO', '`ATIVO`', '`ATIVO`', 3, -1, FALSE, '`ATIVO`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->ATIVO->OptionCount = 3;
		$this->ATIVO->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['ATIVO'] = &$this->ATIVO;

		// LEVEL
		$this->LEVEL = new cField('usuario', 'usuario', 'x_LEVEL', 'LEVEL', '`LEVEL`', '`LEVEL`', 3, -1, FALSE, '`LEVEL`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->LEVEL->OptionCount = 4;
		$this->LEVEL->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['LEVEL'] = &$this->LEVEL;

		// memo
		$this->memo = new cField('usuario', 'usuario', 'x_memo', 'memo', '`memo`', '`memo`', 201, -1, FALSE, '`memo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXTAREA');
		$this->fields['memo'] = &$this->memo;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`usuario`";
	}

	function SqlFrom() { // For backward compatibility
    	return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
    	$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
    	return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
    	$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
    	return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
    	$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
    	return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
    	$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
    	return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
    	$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
    	return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
    	$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		global $Security;

		// Add User ID filter
		if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
			$sFilter = $this->AddUserIDFilter($sFilter);
		}
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = $this->UserIDAllowSecurity;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		$cnt = -1;
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match("/^SELECT \* FROM/i", $sSql)) {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'SENHA')
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'SENHA') {
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			}
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('ID', $rs))
				ew_AddFilter($where, ew_QuotedName('ID', $this->DBID) . '=' . ew_QuotedValue($rs['ID'], $this->ID->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$conn = &$this->Connection();
		return $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`ID` = @ID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->ID->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@ID@", ew_AdjustSql($this->ID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "usuariolist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "usuariolist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("usuarioview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("usuarioview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "usuarioadd.php?" . $this->UrlParm($parm);
		else
			$url = "usuarioadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("usuarioedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("usuarioadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("usuariodelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "ID:" . ew_VarToJson($this->ID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->ID->CurrentValue)) {
			$sUrl .= "ID=" . urlencode($this->ID->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsHttpPost();
			$arKeys[] = $isPost ? ew_StripSlashes(@$_POST["ID"]) : ew_StripSlashes(@$_GET["ID"]); // ID

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->ID->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// ID
		// LOGIN
		// SENHA
		// NOME
		// EMAIL
		// TELEFONE
		// ATIVO
		// LEVEL
		// memo
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

		// memo
		$this->memo->ViewValue = $this->memo->CurrentValue;
		$this->memo->ViewCustomAttributes = "";

		// ID
		$this->ID->LinkCustomAttributes = "";
		$this->ID->HrefValue = "";
		$this->ID->TooltipValue = "";

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

		// ATIVO
		$this->ATIVO->LinkCustomAttributes = "";
		$this->ATIVO->HrefValue = "";
		$this->ATIVO->TooltipValue = "";

		// LEVEL
		$this->LEVEL->LinkCustomAttributes = "";
		$this->LEVEL->HrefValue = "";
		$this->LEVEL->TooltipValue = "";

		// memo
		$this->memo->LinkCustomAttributes = "";
		$this->memo->HrefValue = "";
		$this->memo->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// ID
		$this->ID->EditAttrs["class"] = "form-control";
		$this->ID->EditCustomAttributes = "";
		$this->ID->EditValue = $this->ID->CurrentValue;
		$this->ID->ViewCustomAttributes = "";

		// LOGIN
		$this->_LOGIN->EditAttrs["class"] = "form-control";
		$this->_LOGIN->EditCustomAttributes = "";
		$this->_LOGIN->EditValue = $this->_LOGIN->CurrentValue;
		$this->_LOGIN->PlaceHolder = ew_RemoveHtml($this->_LOGIN->FldCaption());

		// SENHA
		$this->SENHA->EditAttrs["class"] = "form-control ewPasswordStrength";
		$this->SENHA->EditCustomAttributes = "";
		$this->SENHA->EditValue = $this->SENHA->CurrentValue;
		$this->SENHA->PlaceHolder = ew_RemoveHtml($this->SENHA->FldCaption());

		// NOME
		$this->NOME->EditAttrs["class"] = "form-control";
		$this->NOME->EditCustomAttributes = "";
		$this->NOME->EditValue = $this->NOME->CurrentValue;
		$this->NOME->PlaceHolder = ew_RemoveHtml($this->NOME->FldCaption());

		// EMAIL
		$this->_EMAIL->EditAttrs["class"] = "form-control";
		$this->_EMAIL->EditCustomAttributes = "";
		$this->_EMAIL->EditValue = $this->_EMAIL->CurrentValue;
		$this->_EMAIL->PlaceHolder = ew_RemoveHtml($this->_EMAIL->FldCaption());

		// TELEFONE
		$this->TELEFONE->EditAttrs["class"] = "form-control";
		$this->TELEFONE->EditCustomAttributes = "";
		$this->TELEFONE->EditValue = $this->TELEFONE->CurrentValue;
		$this->TELEFONE->PlaceHolder = ew_RemoveHtml($this->TELEFONE->FldCaption());

		// ATIVO
		$this->ATIVO->EditAttrs["class"] = "form-control";
		$this->ATIVO->EditCustomAttributes = "";
		$this->ATIVO->EditValue = $this->ATIVO->Options(TRUE);

		// LEVEL
		$this->LEVEL->EditAttrs["class"] = "form-control";
		$this->LEVEL->EditCustomAttributes = "";
		if (!$Security->CanAdmin()) { // System admin
			$this->LEVEL->EditValue = $Language->Phrase("PasswordMask");
		} else {
		$this->LEVEL->EditValue = $this->LEVEL->Options(TRUE);
		}

		// memo
		$this->memo->EditAttrs["class"] = "form-control";
		$this->memo->EditCustomAttributes = "";
		$this->memo->EditValue = $this->memo->CurrentValue;
		$this->memo->PlaceHolder = ew_RemoveHtml($this->memo->FldCaption());

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->ID->Exportable) $Doc->ExportCaption($this->ID);
					if ($this->_LOGIN->Exportable) $Doc->ExportCaption($this->_LOGIN);
					if ($this->SENHA->Exportable) $Doc->ExportCaption($this->SENHA);
					if ($this->NOME->Exportable) $Doc->ExportCaption($this->NOME);
					if ($this->_EMAIL->Exportable) $Doc->ExportCaption($this->_EMAIL);
					if ($this->TELEFONE->Exportable) $Doc->ExportCaption($this->TELEFONE);
					if ($this->ATIVO->Exportable) $Doc->ExportCaption($this->ATIVO);
					if ($this->LEVEL->Exportable) $Doc->ExportCaption($this->LEVEL);
					if ($this->memo->Exportable) $Doc->ExportCaption($this->memo);
				} else {
					if ($this->ID->Exportable) $Doc->ExportCaption($this->ID);
					if ($this->_LOGIN->Exportable) $Doc->ExportCaption($this->_LOGIN);
					if ($this->SENHA->Exportable) $Doc->ExportCaption($this->SENHA);
					if ($this->NOME->Exportable) $Doc->ExportCaption($this->NOME);
					if ($this->_EMAIL->Exportable) $Doc->ExportCaption($this->_EMAIL);
					if ($this->TELEFONE->Exportable) $Doc->ExportCaption($this->TELEFONE);
					if ($this->ATIVO->Exportable) $Doc->ExportCaption($this->ATIVO);
					if ($this->LEVEL->Exportable) $Doc->ExportCaption($this->LEVEL);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->ID->Exportable) $Doc->ExportField($this->ID);
						if ($this->_LOGIN->Exportable) $Doc->ExportField($this->_LOGIN);
						if ($this->SENHA->Exportable) $Doc->ExportField($this->SENHA);
						if ($this->NOME->Exportable) $Doc->ExportField($this->NOME);
						if ($this->_EMAIL->Exportable) $Doc->ExportField($this->_EMAIL);
						if ($this->TELEFONE->Exportable) $Doc->ExportField($this->TELEFONE);
						if ($this->ATIVO->Exportable) $Doc->ExportField($this->ATIVO);
						if ($this->LEVEL->Exportable) $Doc->ExportField($this->LEVEL);
						if ($this->memo->Exportable) $Doc->ExportField($this->memo);
					} else {
						if ($this->ID->Exportable) $Doc->ExportField($this->ID);
						if ($this->_LOGIN->Exportable) $Doc->ExportField($this->_LOGIN);
						if ($this->SENHA->Exportable) $Doc->ExportField($this->SENHA);
						if ($this->NOME->Exportable) $Doc->ExportField($this->NOME);
						if ($this->_EMAIL->Exportable) $Doc->ExportField($this->_EMAIL);
						if ($this->TELEFONE->Exportable) $Doc->ExportField($this->TELEFONE);
						if ($this->ATIVO->Exportable) $Doc->ExportField($this->ATIVO);
						if ($this->LEVEL->Exportable) $Doc->ExportField($this->LEVEL);
					}
					$Doc->EndExportRow();
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// User ID filter
	function UserIDFilter($userid) {
		$sUserIDFilter = '`ID` = ' . ew_QuotedValue($userid, EW_DATATYPE_NUMBER, EW_USER_TABLE_DBID);
		return $sUserIDFilter;
	}

	// Add User ID filter
	function AddUserIDFilter($sFilter) {
		global $Security;
		$sFilterWrk = "";
		$id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
		if (!$this->UserIDAllow($id) && !$Security->IsAdmin()) {
			$sFilterWrk = $Security->UserIDList();
			if ($sFilterWrk <> "")
				$sFilterWrk = '`ID` IN (' . $sFilterWrk . ')';
		}

		// Call User ID Filtering event
		$this->UserID_Filtering($sFilterWrk);
		ew_AddFilter($sFilter, $sFilterWrk);
		return $sFilter;
	}

	// User ID subquery
	function GetUserIDSubquery(&$fld, &$masterfld) {
		global $UserTableConn;
		$sWrk = "";
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM `usuario`";
		$sFilter = $this->AddUserIDFilter("");
		if ($sFilter <> "") $sSql .= " WHERE " . $sFilter;

		// Use subquery
		if (EW_USE_SUBQUERY_FOR_MASTER_USER_ID) {
			$sWrk = $sSql;
		} else {

			// List all values
			if ($rs = $UserTableConn->Execute($sSql)) {
				while (!$rs->EOF) {
					if ($sWrk <> "") $sWrk .= ",";
					$sWrk .= ew_QuotedValue($rs->fields[0], $masterfld->FldDataType, EW_USER_TABLE_DBID);
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}
		if ($sWrk <> "") {
			$sWrk = $fld->FldExpression . " IN (" . $sWrk . ")";
		}
		return $sWrk;
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
