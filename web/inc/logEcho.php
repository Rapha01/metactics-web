<?php
function logEcho($log)
{
	
	if(!empty($log))
	{
	?>
	
	<div class="container">
		<div class="row" style="margin-left:2%;">
			<div class="col-lg-6">
			
			<?php
			foreach ($log as $type => $properties) 
			{
				//echo $type, "<br />";
				// $properties = $GodArray[$type]
				foreach ($properties as $property => $value) 
				{
					// $value = $GodArray[$type][$property]
					?>
					<div class="alert alert-dismissible alert-<?php echo $type;?>">
						<button type="button" class="close" data-dismiss="alert">×</button>
						<h4><?php //echo $type;?></h4>
						<p><?php echo $value;?></p>
					</div>
						
					<?php
				}
			}
		}
		?>
			</div>
	
		</div>
	</div>
	
<?php
//unset($errorlog);
}
?>

