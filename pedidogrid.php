<?php include_once "usuarioinfo.php" ?>
<?php

// Create page object
if (!isset($pedido_grid)) $pedido_grid = new cpedido_grid();

// Page init
$pedido_grid->Page_Init();

// Page main
$pedido_grid->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$pedido_grid->Page_Render();
?>
<?php if ($pedido->Export == "") { ?>
<script type="text/javascript">

// Form object
var fpedidogrid = new ew_Form("fpedidogrid", "grid");
fpedidogrid.FormKeyCountName = '<?php echo $pedido_grid->FormKeyCountName ?>';

// Validate form
fpedidogrid.Validate = function() {
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
		var checkrow = (gridinsert) ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
fpedidogrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "id_produto", false)) return false;
	return true;
}

// Form_CustomValidate event
fpedidogrid.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fpedidogrid.ValidateRequired = true;
<?php } else { ?>
fpedidogrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fpedidogrid.Lists["x_id_produto"] = {"LinkField":"x_id","Ajax":true,"AutoFill":false,"DisplayFields":["x_produto","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":""};

// Form object for search
</script>
<?php } ?>
<?php
if ($pedido->CurrentAction == "gridadd") {
	if ($pedido->CurrentMode == "copy") {
		$bSelectLimit = $pedido_grid->UseSelectLimit;
		if ($bSelectLimit) {
			$pedido_grid->TotalRecs = $pedido->SelectRecordCount();
			$pedido_grid->Recordset = $pedido_grid->LoadRecordset($pedido_grid->StartRec-1, $pedido_grid->DisplayRecs);
		} else {
			if ($pedido_grid->Recordset = $pedido_grid->LoadRecordset())
				$pedido_grid->TotalRecs = $pedido_grid->Recordset->RecordCount();
		}
		$pedido_grid->StartRec = 1;
		$pedido_grid->DisplayRecs = $pedido_grid->TotalRecs;
	} else {
		$pedido->CurrentFilter = "0=1";
		$pedido_grid->StartRec = 1;
		$pedido_grid->DisplayRecs = $pedido->GridAddRowCount;
	}
	$pedido_grid->TotalRecs = $pedido_grid->DisplayRecs;
	$pedido_grid->StopRec = $pedido_grid->DisplayRecs;
} else {
	$bSelectLimit = $pedido_grid->UseSelectLimit;
	if ($bSelectLimit) {
		if ($pedido_grid->TotalRecs <= 0)
			$pedido_grid->TotalRecs = $pedido->SelectRecordCount();
	} else {
		if (!$pedido_grid->Recordset && ($pedido_grid->Recordset = $pedido_grid->LoadRecordset()))
			$pedido_grid->TotalRecs = $pedido_grid->Recordset->RecordCount();
	}
	$pedido_grid->StartRec = 1;
	$pedido_grid->DisplayRecs = $pedido_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$pedido_grid->Recordset = $pedido_grid->LoadRecordset($pedido_grid->StartRec-1, $pedido_grid->DisplayRecs);

	// Set no record found message
	if ($pedido->CurrentAction == "" && $pedido_grid->TotalRecs == 0) {
		if (!$Security->CanList())
			$pedido_grid->setWarningMessage($Language->Phrase("NoPermission"));
		if ($pedido_grid->SearchWhere == "0=101")
			$pedido_grid->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$pedido_grid->setWarningMessage($Language->Phrase("NoRecord"));
	}
}
$pedido_grid->RenderOtherOptions();
?>
<?php $pedido_grid->ShowPageHeader(); ?>
<?php
$pedido_grid->ShowMessage();
?>
<?php if ($pedido_grid->TotalRecs > 0 || $pedido->CurrentAction <> "") { ?>
<div class="panel panel-default ewGrid">
<div id="fpedidogrid" class="ewForm form-inline">
<?php if ($pedido_grid->ShowOtherOptions) { ?>
<div class="panel-heading ewGridUpperPanel">
<?php
	foreach ($pedido_grid->OtherOptions as &$option)
		$option->Render("body");
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<div id="gmp_pedido" class="<?php if (ew_IsResponsiveLayout()) { echo "table-responsive "; } ?>ewGridMiddlePanel">
<table id="tbl_pedidogrid" class="table ewTable">
<?php echo $pedido->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Header row
$pedido_grid->RowType = EW_ROWTYPE_HEADER;

// Render list options
$pedido_grid->RenderListOptions();

// Render list options (header, left)
$pedido_grid->ListOptions->Render("header", "left");
?>
<?php if ($pedido->id_produto->Visible) { // id_produto ?>
	<?php if ($pedido->SortUrl($pedido->id_produto) == "") { ?>
		<th data-name="id_produto"><div id="elh_pedido_id_produto" class="pedido_id_produto"><div class="ewTableHeaderCaption"><?php echo $pedido->id_produto->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="id_produto"><div><div id="elh_pedido_id_produto" class="pedido_id_produto">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $pedido->id_produto->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($pedido->id_produto->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($pedido->id_produto->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
        </div></div></th>
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$pedido_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$pedido_grid->StartRec = 1;
$pedido_grid->StopRec = $pedido_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue($pedido_grid->FormKeyCountName) && ($pedido->CurrentAction == "gridadd" || $pedido->CurrentAction == "gridedit" || $pedido->CurrentAction == "F")) {
		$pedido_grid->KeyCount = $objForm->GetValue($pedido_grid->FormKeyCountName);
		$pedido_grid->StopRec = $pedido_grid->StartRec + $pedido_grid->KeyCount - 1;
	}
}
$pedido_grid->RecCnt = $pedido_grid->StartRec - 1;
if ($pedido_grid->Recordset && !$pedido_grid->Recordset->EOF) {
	$pedido_grid->Recordset->MoveFirst();
	$bSelectLimit = $pedido_grid->UseSelectLimit;
	if (!$bSelectLimit && $pedido_grid->StartRec > 1)
		$pedido_grid->Recordset->Move($pedido_grid->StartRec - 1);
} elseif (!$pedido->AllowAddDeleteRow && $pedido_grid->StopRec == 0) {
	$pedido_grid->StopRec = $pedido->GridAddRowCount;
}

// Initialize aggregate
$pedido->RowType = EW_ROWTYPE_AGGREGATEINIT;
$pedido->ResetAttrs();
$pedido_grid->RenderRow();
if ($pedido->CurrentAction == "gridadd")
	$pedido_grid->RowIndex = 0;
if ($pedido->CurrentAction == "gridedit")
	$pedido_grid->RowIndex = 0;
while ($pedido_grid->RecCnt < $pedido_grid->StopRec) {
	$pedido_grid->RecCnt++;
	if (intval($pedido_grid->RecCnt) >= intval($pedido_grid->StartRec)) {
		$pedido_grid->RowCnt++;
		if ($pedido->CurrentAction == "gridadd" || $pedido->CurrentAction == "gridedit" || $pedido->CurrentAction == "F") {
			$pedido_grid->RowIndex++;
			$objForm->Index = $pedido_grid->RowIndex;
			if ($objForm->HasValue($pedido_grid->FormActionName))
				$pedido_grid->RowAction = strval($objForm->GetValue($pedido_grid->FormActionName));
			elseif ($pedido->CurrentAction == "gridadd")
				$pedido_grid->RowAction = "insert";
			else
				$pedido_grid->RowAction = "";
		}

		// Set up key count
		$pedido_grid->KeyCount = $pedido_grid->RowIndex;

		// Init row class and style
		$pedido->ResetAttrs();
		$pedido->CssClass = "";
		if ($pedido->CurrentAction == "gridadd") {
			if ($pedido->CurrentMode == "copy") {
				$pedido_grid->LoadRowValues($pedido_grid->Recordset); // Load row values
				$pedido_grid->SetRecordKey($pedido_grid->RowOldKey, $pedido_grid->Recordset); // Set old record key
			} else {
				$pedido_grid->LoadDefaultValues(); // Load default values
				$pedido_grid->RowOldKey = ""; // Clear old key value
			}
		} else {
			$pedido_grid->LoadRowValues($pedido_grid->Recordset); // Load row values
		}
		$pedido->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($pedido->CurrentAction == "gridadd") // Grid add
			$pedido->RowType = EW_ROWTYPE_ADD; // Render add
		if ($pedido->CurrentAction == "gridadd" && $pedido->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$pedido_grid->RestoreCurrentRowFormValues($pedido_grid->RowIndex); // Restore form values
		if ($pedido->CurrentAction == "gridedit") { // Grid edit
			if ($pedido->EventCancelled) {
				$pedido_grid->RestoreCurrentRowFormValues($pedido_grid->RowIndex); // Restore form values
			}
			if ($pedido_grid->RowAction == "insert")
				$pedido->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$pedido->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($pedido->CurrentAction == "gridedit" && ($pedido->RowType == EW_ROWTYPE_EDIT || $pedido->RowType == EW_ROWTYPE_ADD) && $pedido->EventCancelled) // Update failed
			$pedido_grid->RestoreCurrentRowFormValues($pedido_grid->RowIndex); // Restore form values
		if ($pedido->RowType == EW_ROWTYPE_EDIT) // Edit row
			$pedido_grid->EditRowCnt++;
		if ($pedido->CurrentAction == "F") // Confirm row
			$pedido_grid->RestoreCurrentRowFormValues($pedido_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$pedido->RowAttrs = array_merge($pedido->RowAttrs, array('data-rowindex'=>$pedido_grid->RowCnt, 'id'=>'r' . $pedido_grid->RowCnt . '_pedido', 'data-rowtype'=>$pedido->RowType));

		// Render row
		$pedido_grid->RenderRow();

		// Render list options
		$pedido_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($pedido_grid->RowAction <> "delete" && $pedido_grid->RowAction <> "insertdelete" && !($pedido_grid->RowAction == "insert" && $pedido->CurrentAction == "F" && $pedido_grid->EmptyRow())) {
?>
	<tr<?php echo $pedido->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pedido_grid->ListOptions->Render("body", "left", $pedido_grid->RowCnt);
?>
	<?php if ($pedido->id_produto->Visible) { // id_produto ?>
		<td data-name="id_produto"<?php echo $pedido->id_produto->CellAttributes() ?>>
<?php if ($pedido->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<span id="el<?php echo $pedido_grid->RowCnt ?>_pedido_id_produto" class="form-group pedido_id_produto">
<select data-table="pedido" data-field="x_id_produto" data-value-separator="<?php echo ew_HtmlEncode(is_array($pedido->id_produto->DisplayValueSeparator) ? json_encode($pedido->id_produto->DisplayValueSeparator) : $pedido->id_produto->DisplayValueSeparator) ?>" id="x<?php echo $pedido_grid->RowIndex ?>_id_produto" name="x<?php echo $pedido_grid->RowIndex ?>_id_produto"<?php echo $pedido->id_produto->EditAttributes() ?>>
<?php
if (is_array($pedido->id_produto->EditValue)) {
	$arwrk = $pedido->id_produto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($pedido->id_produto->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $pedido->id_produto->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($pedido->id_produto->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($pedido->id_produto->CurrentValue) ?>" selected><?php echo $pedido->id_produto->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $pedido->id_produto->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `id`, `produto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `produtos`";
$sWhereWrk = "";
$pedido->id_produto->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$pedido->id_produto->LookupFilters += array("f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$pedido->Lookup_Selecting($pedido->id_produto, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $pedido->id_produto->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $pedido_grid->RowIndex ?>_id_produto" id="s_x<?php echo $pedido_grid->RowIndex ?>_id_produto" value="<?php echo $pedido->id_produto->LookupFilterQuery() ?>">
</span>
<input type="hidden" data-table="pedido" data-field="x_id_produto" name="o<?php echo $pedido_grid->RowIndex ?>_id_produto" id="o<?php echo $pedido_grid->RowIndex ?>_id_produto" value="<?php echo ew_HtmlEncode($pedido->id_produto->OldValue) ?>">
<?php } ?>
<?php if ($pedido->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?php echo $pedido_grid->RowCnt ?>_pedido_id_produto" class="form-group pedido_id_produto">
<select data-table="pedido" data-field="x_id_produto" data-value-separator="<?php echo ew_HtmlEncode(is_array($pedido->id_produto->DisplayValueSeparator) ? json_encode($pedido->id_produto->DisplayValueSeparator) : $pedido->id_produto->DisplayValueSeparator) ?>" id="x<?php echo $pedido_grid->RowIndex ?>_id_produto" name="x<?php echo $pedido_grid->RowIndex ?>_id_produto"<?php echo $pedido->id_produto->EditAttributes() ?>>
<?php
if (is_array($pedido->id_produto->EditValue)) {
	$arwrk = $pedido->id_produto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($pedido->id_produto->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $pedido->id_produto->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($pedido->id_produto->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($pedido->id_produto->CurrentValue) ?>" selected><?php echo $pedido->id_produto->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $pedido->id_produto->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `id`, `produto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `produtos`";
$sWhereWrk = "";
$pedido->id_produto->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$pedido->id_produto->LookupFilters += array("f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$pedido->Lookup_Selecting($pedido->id_produto, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $pedido->id_produto->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $pedido_grid->RowIndex ?>_id_produto" id="s_x<?php echo $pedido_grid->RowIndex ?>_id_produto" value="<?php echo $pedido->id_produto->LookupFilterQuery() ?>">
</span>
<?php } ?>
<?php if ($pedido->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span id="el<?php echo $pedido_grid->RowCnt ?>_pedido_id_produto" class="pedido_id_produto">
<span<?php echo $pedido->id_produto->ViewAttributes() ?>>
<?php echo $pedido->id_produto->ListViewValue() ?></span>
</span>
<input type="hidden" data-table="pedido" data-field="x_id_produto" name="x<?php echo $pedido_grid->RowIndex ?>_id_produto" id="x<?php echo $pedido_grid->RowIndex ?>_id_produto" value="<?php echo ew_HtmlEncode($pedido->id_produto->FormValue) ?>">
<input type="hidden" data-table="pedido" data-field="x_id_produto" name="o<?php echo $pedido_grid->RowIndex ?>_id_produto" id="o<?php echo $pedido_grid->RowIndex ?>_id_produto" value="<?php echo ew_HtmlEncode($pedido->id_produto->OldValue) ?>">
<?php } ?>
<a id="<?php echo $pedido_grid->PageObjName . "_row_" . $pedido_grid->RowCnt ?>"></a></td>
	<?php } ?>
<?php if ($pedido->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" data-table="pedido" data-field="x_id" name="x<?php echo $pedido_grid->RowIndex ?>_id" id="x<?php echo $pedido_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($pedido->id->CurrentValue) ?>">
<input type="hidden" data-table="pedido" data-field="x_id" name="o<?php echo $pedido_grid->RowIndex ?>_id" id="o<?php echo $pedido_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($pedido->id->OldValue) ?>">
<?php } ?>
<?php if ($pedido->RowType == EW_ROWTYPE_EDIT || $pedido->CurrentMode == "edit") { ?>
<input type="hidden" data-table="pedido" data-field="x_id" name="x<?php echo $pedido_grid->RowIndex ?>_id" id="x<?php echo $pedido_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($pedido->id->CurrentValue) ?>">
<?php } ?>
<?php

// Render list options (body, right)
$pedido_grid->ListOptions->Render("body", "right", $pedido_grid->RowCnt);
?>
	</tr>
<?php if ($pedido->RowType == EW_ROWTYPE_ADD || $pedido->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fpedidogrid.UpdateOpts(<?php echo $pedido_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($pedido->CurrentAction <> "gridadd" || $pedido->CurrentMode == "copy")
		if (!$pedido_grid->Recordset->EOF) $pedido_grid->Recordset->MoveNext();
}
?>
<?php
	if ($pedido->CurrentMode == "add" || $pedido->CurrentMode == "copy" || $pedido->CurrentMode == "edit") {
		$pedido_grid->RowIndex = '$rowindex$';
		$pedido_grid->LoadDefaultValues();

		// Set row properties
		$pedido->ResetAttrs();
		$pedido->RowAttrs = array_merge($pedido->RowAttrs, array('data-rowindex'=>$pedido_grid->RowIndex, 'id'=>'r0_pedido', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($pedido->RowAttrs["class"], "ewTemplate");
		$pedido->RowType = EW_ROWTYPE_ADD;

		// Render row
		$pedido_grid->RenderRow();

		// Render list options
		$pedido_grid->RenderListOptions();
		$pedido_grid->StartRowCnt = 0;
?>
	<tr<?php echo $pedido->RowAttributes() ?>>
<?php

// Render list options (body, left)
$pedido_grid->ListOptions->Render("body", "left", $pedido_grid->RowIndex);
?>
	<?php if ($pedido->id_produto->Visible) { // id_produto ?>
		<td data-name="id_produto">
<?php if ($pedido->CurrentAction <> "F") { ?>
<span id="el$rowindex$_pedido_id_produto" class="form-group pedido_id_produto">
<select data-table="pedido" data-field="x_id_produto" data-value-separator="<?php echo ew_HtmlEncode(is_array($pedido->id_produto->DisplayValueSeparator) ? json_encode($pedido->id_produto->DisplayValueSeparator) : $pedido->id_produto->DisplayValueSeparator) ?>" id="x<?php echo $pedido_grid->RowIndex ?>_id_produto" name="x<?php echo $pedido_grid->RowIndex ?>_id_produto"<?php echo $pedido->id_produto->EditAttributes() ?>>
<?php
if (is_array($pedido->id_produto->EditValue)) {
	$arwrk = $pedido->id_produto->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = ew_SameStr($pedido->id_produto->CurrentValue, $arwrk[$rowcntwrk][0]) ? " selected" : "";
		if ($selwrk <> "") $emptywrk = FALSE;		
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $pedido->id_produto->DisplayValue($arwrk[$rowcntwrk]) ?>
</option>
<?php
	}
	if ($emptywrk && strval($pedido->id_produto->CurrentValue) <> "") {
?>
<option value="<?php echo ew_HtmlEncode($pedido->id_produto->CurrentValue) ?>" selected><?php echo $pedido->id_produto->CurrentValue ?></option>
<?php
    }
}
if (@$emptywrk) $pedido->id_produto->OldValue = "";
?>
</select>
<?php
$sSqlWrk = "SELECT `id`, `produto` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `produtos`";
$sWhereWrk = "";
$pedido->id_produto->LookupFilters = array("s" => $sSqlWrk, "d" => "");
$pedido->id_produto->LookupFilters += array("f0" => "`id` = {filter_value}", "t0" => "3", "fn0" => "");
$sSqlWrk = "";
$pedido->Lookup_Selecting($pedido->id_produto, $sWhereWrk); // Call Lookup selecting
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
if ($sSqlWrk <> "") $pedido->id_produto->LookupFilters["s"] .= $sSqlWrk;
?>
<input type="hidden" name="s_x<?php echo $pedido_grid->RowIndex ?>_id_produto" id="s_x<?php echo $pedido_grid->RowIndex ?>_id_produto" value="<?php echo $pedido->id_produto->LookupFilterQuery() ?>">
</span>
<?php } else { ?>
<span id="el$rowindex$_pedido_id_produto" class="form-group pedido_id_produto">
<span<?php echo $pedido->id_produto->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $pedido->id_produto->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="pedido" data-field="x_id_produto" name="x<?php echo $pedido_grid->RowIndex ?>_id_produto" id="x<?php echo $pedido_grid->RowIndex ?>_id_produto" value="<?php echo ew_HtmlEncode($pedido->id_produto->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="pedido" data-field="x_id_produto" name="o<?php echo $pedido_grid->RowIndex ?>_id_produto" id="o<?php echo $pedido_grid->RowIndex ?>_id_produto" value="<?php echo ew_HtmlEncode($pedido->id_produto->OldValue) ?>">
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$pedido_grid->ListOptions->Render("body", "right", $pedido_grid->RowCnt);
?>
<script type="text/javascript">
fpedidogrid.UpdateOpts(<?php echo $pedido_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
</table>
<?php if ($pedido->CurrentMode == "add" || $pedido->CurrentMode == "copy") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridinsert">
<input type="hidden" name="<?php echo $pedido_grid->FormKeyCountName ?>" id="<?php echo $pedido_grid->FormKeyCountName ?>" value="<?php echo $pedido_grid->KeyCount ?>">
<?php echo $pedido_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($pedido->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="<?php echo $pedido_grid->FormKeyCountName ?>" id="<?php echo $pedido_grid->FormKeyCountName ?>" value="<?php echo $pedido_grid->KeyCount ?>">
<?php echo $pedido_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($pedido->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fpedidogrid">
</div>
<?php

// Close recordset
if ($pedido_grid->Recordset)
	$pedido_grid->Recordset->Close();
?>
</div>
</div>
<?php } ?>
<?php if ($pedido_grid->TotalRecs == 0 && $pedido->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($pedido_grid->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<?php if ($pedido->Export == "") { ?>
<script type="text/javascript">
fpedidogrid.Init();
</script>
<?php } ?>
<?php
$pedido_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$pedido_grid->Page_Terminate();
?>
