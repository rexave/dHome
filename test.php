<?php
require_once ("includes/global_ui.php");

entete_page("test" , "");

?>

<div id="content">
	<div id="content-inner">
		<div style="margin-bottom:40px;">
			<div id="output" class="ui-state-highlight ui-corner-all">
				<p id="output-crontab" class="no-margin">
					<span id="min-out">*</span>
					<span id="hour-out">*</span>
					<span id="dom-out">*</span>
					<span id="mon-out">*</span>
					<span id="dow-out">*</span>
				</p>
			</div>
		</div>

		<div data-role="collapsible-set">
			<div data-role="collapsible">
			<h3>Minute</h3>
			<p>
				<div data-role="navbar">
					<ul>
						<li><a href="#" class="ui-btn-active ui-state-persist" onclick="display_cron('min-all');">every minute</a></li>
						<li><a href="#" onclick="display_cron('min-every');">every <em>n</em> minutes</a></li>
						<li><a href="#" onclick="display_cron('min-selected');">each selected minute</a></li>
					</ul>
				</div>
				<div id="div-min-all" class="div-min">
				</div>
				<div id="div-min-every" class="div-min" style="display:none;">
				<h3>every <em>n</em> minutes</h3>
					<input type="range" name="min-out_slider" id="min-out_slider" value="0" min="0" max="59" data-highlight="true" onchange="action_slider(this);"/>
				</div>
				<div id="div-min-selected" class="div-min" style="display:none;">
				<h3>each selected minute</h3>
					<div data-role="fieldcontain">
							<fieldset data-role="controlgroup" data-type="horizontal">
								<?php
									for($i=0;$i<60;$i++){
										echo '<input type="checkbox" name="min-out" id="min-out_'.$i.'" onClick="update_each(\'min\');">
										<label class="min-out_'.$i.'" for="min-out_'.$i.'" >';printf('%02s', $i); echo'</label>';
										if(($i+1)%5==0 && $i!=0) echo "</fieldset><fieldset data-role=\"controlgroup\" data-type=\"horizontal\">";
									}
								?>
							</fieldset>
					</div>
				</div>
			</p>
			</div>
			<div data-role="collapsible">
			<h3>Hour</h3>
			<p>
				<div data-role="navbar">
					<ul>
						<li><a href="#" class="ui-btn-active ui-state-persist" onclick="display_cron('hour-all');">every hour</a></li>
						<li><a href="#" onclick="display_cron('hour-every');">every <em>n</em> hour</a></li>
						<li><a href="#" onclick="display_cron('hour-selected');">each selected hour</a></li>
					</ul>
				</div>
				<div id="div-hour-all" class="div-hour">
				</div>
				<div id="div-hour-every" class="div-hour" style="display:none;">
					<h3>every <em>n</em> hours</h3>
					<input type="range" name="hour-out_slider" id="hour-out_slider" value="0" min="0" max="23" data-highlight="true" onchange="action_slider(this);"/>
				</div>
				<div id="div-hour-selected" class="div-hour" style="display:none;">
					<h3>each selected hour</h3>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup" data-type="horizontal">
							<?php
								for($i=0;$i<24;$i++){
									echo '<input type="checkbox" name="hour-out" id="hour-out_'.$i.'" onClick="update_each(\'hour\');">
									<label class="hour-out_'.$i.'" for="hour-out_'.$i.'" >';printf('%02s', $i); echo'</label>';
									if(($i+1)%5==0 && $i!=0) echo "</fieldset><fieldset data-role=\"controlgroup\" data-type=\"horizontal\">";
								}
							?>
						</fieldset>
					</div>
				</div>
			</p>
			</div>
			<div data-role="collapsible">
			<h3>Day of month</h3>
			<p>
				<div data-role="navbar">
					<ul>
						<li><a href="#" class="ui-btn-active ui-state-persist" onclick="display_cron('dom-all');">every day</a></li>
						<li><a href="#" onclick="display_cron('dom-selected');">each selected day</a></li>
					</ul>
				</div>
				<div id="div-dom-all" class="div-dom">
				</div>
				<div id="div-dom-selected" class="div-dom" style="display:none;">
					<h3>each selected day</h3>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup" data-type="horizontal">
							<?php
								for($i=0;$i<32;$i++){
									echo '<input type="checkbox" name="dom-out" id="dom-out_'.$i.'" onClick="update_each(\'dom\');">
									<label class="dom-out_'.$i.'" for="dom-out_'.$i.'" >';printf('%02s', $i); echo'</label>';
									if(($i+1)%5==0 && $i!=0) echo "</fieldset><fieldset data-role=\"controlgroup\" data-type=\"horizontal\">";
								}
							?>
						</fieldset>
					</div>
				</div>
			</p>
			</div>
			<div data-role="collapsible">
			<h3>Month</h3>
			<p>
				<div data-role="navbar">
					<ul>
						<li><a href="#" class="ui-btn-active ui-state-persist" onclick="display_cron('mon-all');">every month</a></li>
						<li><a href="#" onclick="display_cron('mon-selected');">each selected month</a></li>
					</ul>
				</div>
				<div id="div-mon-all" class="div-mon">
				</div>
				<div id="div-mon-selected" class="div-mon" style="display:none;">
					<h3>each selected month</h3>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup" data-type="horizontal">
							<?php
							
							$array_mon = split("\t","January	February	March	April	May	June	July	August	September	October	November	December");
							
								for($i=1;$i<13;$i++){
									echo '<input type="checkbox" name="mon-out" id="mon-out_'.$i.'" onClick="update_each(\'mon\');">
									<label class="mon-out_'.$i.'" for="mon-out_'.$i.'" >'.$array_mon[$i-1].'</label>';
									if(($i)%2==0) echo "</fieldset><fieldset data-role=\"controlgroup\" data-type=\"horizontal\">";
								}
							?>
						</fieldset>
					</div>
				</div>
			</p>
			</div>
			<div data-role="collapsible">
			<h3>Day of week</h3>
			<p>
				<div data-role="navbar">
					<ul>
						<li><a href="#" class="ui-btn-active ui-state-persist" onclick="display_cron('dow-all');">every day of the week</a></li>
						<li><a href="#" onclick="display_cron('dow-selected');">each selected day of the week</a></li>
					</ul>
				</div>
				<div id="div-dow-all" class="div-dow">
				</div>
				<div id="div-dow-selected" class="div-dow" style="display:none;">
					<h3>each selected day of the week</h3>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup" data-type="horizontal">
							<?php
							$array_dow = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
								for($i=0;$i<7;$i++){
									echo '<input type="checkbox" name="dow-out" id="dow-out_'.$i.'" onClick="update_each(\'dow\');">
									<label class="dow-out_'.$i.'" for="dow-out_'.$i.'" >'.$array_dow[$i].'</label>';
									if(($i+1)%2==0) echo "</fieldset><fieldset data-role=\"controlgroup\" data-type=\"horizontal\">";
								}
							?>
						</fieldset>
					</div>
				</div>
			</p>
			</div>
		</div>

		<script type="text/javascript" src="corntab/corntab.js"></script>
	</div>
</div>

<?php
pied_page();
?>