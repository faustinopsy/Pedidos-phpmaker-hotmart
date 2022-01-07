<?php

// endereco
// status

?>
<?php if ($endereco->Visible) { ?>
<!-- <h4 class="ewMasterCaption"><?php echo $endereco->TableCaption() ?></h4> -->
<table id="tbl_enderecomaster" class="table table-bordered table-striped ewViewTable">
	<tbody>
<?php if ($endereco->endereco->Visible) { // endereco ?>
		<tr id="r_endereco">
			<td><?php echo $endereco->endereco->FldCaption() ?></td>
			<td<?php echo $endereco->endereco->CellAttributes() ?>>
<span id="el_endereco_endereco">
<span<?php echo $endereco->endereco->ViewAttributes() ?>>
<?php echo $endereco->endereco->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($endereco->status->Visible) { // status ?>
		<tr id="r_status">
			<td><?php echo $endereco->status->FldCaption() ?></td>
			<td<?php echo $endereco->status->CellAttributes() ?>>
<span id="el_endereco_status">
<span>
<?php if (!ew_EmptyStr($endereco->status->ListViewValue())) { ?><img src="<?php echo $endereco->status->ListViewValue() ?>" alt=""<?php echo $endereco->status->ViewAttributes() ?>><?php } ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
<?php } ?>
