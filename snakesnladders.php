<html>
<head>
</head>
<body>

<form method="post" action="test.php">
Grid Size: 
<input type="text" name="gridsize">
<button type="submit">Submit</button>
</form>

<?php

$grid_size = $_POST["gridsize"] ?? 0;

if (!empty($grid_size)) {
	$no_of_player = 3;
	$max_value = $grid_size * $grid_size;
	
	function roll_dice()
	{
		return rand(1,6);
	}
	
	$found_winner = false;
	$result = [];
	
	while(!$found_winner) {
		for($i=1; $i<= $no_of_player; $i++)
		{
			$dice_result = roll_dice();
			$current_position = 0;
			if (isset($result[$i]['dice_roll_history'])) {
				$current_position = array_sum($result[$i]['dice_roll_history']);
			}
			$result[$i]['dice_roll_history'][] = $dice_result;
			$current_coordinate = 0;
			if (($current_position + $dice_result) > $max_value) {
				$result[$i]['position_history'][] = $current_position;
				$current_coordinate = $current_position;
			} else {
				$result[$i]['position_history'][] = $current_position + $dice_result;
				$current_coordinate = $current_position + $dice_result;
			}
			$cnt = 1;
			$coordinate_fount = false;
			for($j=0; $j < $grid_size; $j++) {
				if (!$coordinate_fount) {
					if ($j % 2 == 0) {
						for($k=0; $k < $grid_size; $k++){
							if($cnt == $current_coordinate) {
								$result[$i]['coordinate_history'][] = '('.$k.','.$j.')';
								$coordinate_fount = true;
								break;
							}
							$cnt++;
						}
						
					} else {
						for($k=($grid_size-1); $k >= 0; $k--){
							if($cnt == $current_coordinate) {
								$result[$i]['coordinate_history'][] = '('.$k.','.$j.')';
								$coordinate_fount = true;
								break;
							}
							$cnt++;
						}
					}
				} else {
					break;
				}
			}
			
			if (($current_position + $dice_result) > $max_value) {
				$result[$i]['winner_status'] = '';
				continue;
			} else if (($current_position + $dice_result) == $max_value) {
				$found_winner = true;
				$result[$i]['winner_status'] = 'Winner';
				break;
			}
		}
	}

	?>
	<table>
		<tr>
			<th>Player No.</th>
			<th>Dice Roll History</th>
			<th>Position History</th>
			<th>Coordinate History</th>
			<th>Winner Status</th>
		</tr>
		<?php
			foreach($result as $key => $value){
				echo '<tr>';
				echo '<td>'.$key.'</td>';
				echo '<td>'.implode(' , ',$value['dice_roll_history']).'</td>';
				echo '<td>'.implode(' , ',$value['position_history']).'</td>';
				echo '<td>'.implode(' , ',$value['coordinate_history']).'</td>';
				echo '<td>'.(isset($value['winner_status']) ? $value['winner_status'] : '').'</td>';
				echo '</tr>';
			}
		?>
	</table>
	
	<?php
}

?>

</body>
</html>
