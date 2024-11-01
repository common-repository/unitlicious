
<div class="wrap">
	<h2>Unit.licio.us Settings</h2>
	
	<form method="post" action="options.php">

		<?php settings_fields( 'unitliciousOptions' ); ?>
		<?php $options = get_option( 'unitliciousSettings' ); ?>

		<h3>General Settings</h3>
	
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Post Bookmarks in Category</th>
				<td>
  					<select name="unitliciousSettings[category]">
					
						<?php 
							$currentCat = $options['category'];
							$categories = get_categories( 'hide_empty=0' ); 
							
							foreach ($categories as $cat) :
						?>
							
							<option value="<?php echo $cat->cat_ID; ?>" <?php if( $currentCat == $cat->cat_ID ): ?>selected="selected"<?php endif; ?>><?php echo $cat->cat_name; ?></option>
						
						<?php 
							endforeach; 
						?>
					</select>
				</td>
			</tr>
		</table>	
		
		
		<?php 
			$accounts = get_option( 'unitliciousAccounts' );
			
			$i = 0;
			
			if ( $accounts ) :
			
				foreach( $accounts as $account ) : 
		?>
			<h3>Unitlicious Account #<?php echo $i+1; ?></h3>
			
			<p>
				Leaving either 'username' or 'password' blank will delete the account.
			</p>
		
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Del.icio.us Username</th>
					<td>
						<input class="regular-text" type="text" name="unitliciousAccounts[<?php echo $i; ?>][user]" value="<?php echo $account['user']; ?>" />
						<span>Required.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Del.icio.us Password</th>
					<td>
						<input class="regular-text" type="password" name="unitliciousAccounts[<?php echo $i; ?>][pass]" value="<?php echo $account['pass']; ?>" />
						<span>Required.</span>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Bookmark Author</th>
					<td>
	  					<?php wp_dropdown_users('selected=' . $account['author'] . '&name=unitliciousAccounts[' . $i . '][author]' ); ?>
					</td>
				</tr>
			</table>
				
		<?php 
					$i++;	
				endforeach; 
			endif;
		?>
			
		<h3>New Unitlicious Account</h3>
	
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Del.icio.us Username</th>
				<td>
					<input class="regular-text" type="text" name="unitliciousAccounts[<?php echo $i; ?>][user]" />
					<span>Required.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Del.icio.us Password</th>
				<td>
					<input class="regular-text" type="password" name="unitliciousAccounts[<?php echo $i; ?>][pass]" />
					<span>Required.</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Bookmark Author</th>
				<td>
  					<?php wp_dropdown_users( 'name=unitliciousAccounts[' . $i .'][author]' ); ?>
				</td>
			</tr>
		</table>
		
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>
		