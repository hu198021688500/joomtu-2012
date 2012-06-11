<table>
	<thead></thead>
	<tbody>
		<?php if (!empty($users)){foreach ($users as $user) {?>
		<?php if ($user->uid != Yii::app()->user->id){?>
		<tr>
			<td><?php echo $user->name;?>(<a href="/user/index/id/<?php echo $user->uid;?>"><?php echo $user->email;?></a>)</td>
			<td><?php echo RelComponent::getRelStr($user->uid);?></td>
			<td>
				<select class="relTypes" name="relTypes">
				<?php foreach ($relTypes as $key=>$relType) {?>
					<option value="<?php echo $key;?>"><?php echo $relType;?></option>
				<?php }?>
				</select>
				<input type="hidden" name="uid" value="<?php echo $user->uid;?>" class="uid">
			</td>
		</tr>
		<?php }?>
		<?php }}?>
	</tbody>
</table>
<script type="text/javascript">
<!--
$(document).ready(function(){
	/*********  **************/
	$(".relTypes").change(function(){
		var uid = $(this).next(".uid").val();
		var type = $(this).val();
		$.ajax({
			type:"POST",
			url:"/rel/add",
			dataType:"json",
			data:{id:uid, type:type},
			success:function(json){
				alert(json.msg);
			}
		});
	});
});
//-->
</script>
