<?php

// endereco
// icon
// NOME
// TELEFONE

?>
<?php if ($entregas->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $entregas->TableCaption() ?></h4> -->
<table id="tbl_entregasmaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($entregas->endereco->Visible) { // endereco ?>
		<tr id="r_endereco">
			<td><?php echo $entregas->endereco->FldCaption() ?></td>
			<td<?php echo $entregas->endereco->CellAttributes() ?>>
<div id="orig_entregas_endereco" class="hide">
<span id="el_entregas_endereco">
<span<?php echo $entregas->endereco->ViewAttributes() ?>>
<?php echo $entregas->endereco->ListViewValue() ?></span>
</span>
</div>
<div id="gmap_x_endereco" class="ewGoogleMap" style="width: 200px; height: 200px;"></div>
<script type="text/javascript">
ewGoogleMaps[ewGoogleMaps.length] = ewGoogleMaps["gmap_x_endereco"] = jQuery.extend({"id":"gmap_x_endereco","width":200,"height":200,"latitude":null,"longitude":null,"address":null,"type":"ROADMAP","zoom":8,"title":null,"icon":null,"use_single_map":false,"single_map_width":500,"single_map_height":400,"show_map_on_top":true,"show_all_markers":true,"description":null}, {
	latitude: <?php echo ew_VarToJson($entregas->utmx->CurrentValue, "undefined") ?>,
	longitude: <?php echo ew_VarToJson($entregas->utmy->CurrentValue, "undefined") ?>,
	address: <?php echo ew_VarToJson($entregas->endereco->CurrentValue, "string") ?>,
	title: <?php echo ew_VarToJson($entregas->status->CurrentValue, "string") ?>,
	icon: <?php echo ew_VarToJson($entregas->icon->CurrentValue, "string") ?>
});
</script>
</td>
		</tr>
<?php } ?>
<?php if ($entregas->icon->Visible) { // icon ?>
		<tr id="r_icon">
			<td><?php echo $entregas->icon->FldCaption() ?></td>
			<td<?php echo $entregas->icon->CellAttributes() ?>>
<span id="el_entregas_icon">
<span>
<?php if (!ew_EmptyStr($entregas->icon->ListViewValue())) { ?><img src="<?php echo $entregas->icon->ListViewValue() ?>" alt=""<?php echo $entregas->icon->ViewAttributes() ?>><?php } ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($entregas->NOME->Visible) { // NOME ?>
		<tr id="r_NOME">
			<td><?php echo $entregas->NOME->FldCaption() ?></td>
			<td<?php echo $entregas->NOME->CellAttributes() ?>>
<span id="el_entregas_NOME">
<span<?php echo $entregas->NOME->ViewAttributes() ?>>
<?php echo $entregas->NOME->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($entregas->TELEFONE->Visible) { // TELEFONE ?>
		<tr id="r_TELEFONE">
			<td><?php echo $entregas->TELEFONE->FldCaption() ?></td>
			<td<?php echo $entregas->TELEFONE->CellAttributes() ?>>
<span id="el_entregas_TELEFONE">
<span<?php echo $entregas->TELEFONE->ViewAttributes() ?>>
<?php echo $entregas->TELEFONE->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
