<table border='0' cellpadding='0' cellpadding='0' cellspacing='0'>
	<tr>
		<td>CIAO</td>
	</tr>
	<tr>
		<td>
			<form name='login' method='POST' action='<?=$this->segments[0]; ?>/auth'>
				<table border='0' cellpadding='5' cellspacing='1'>
					<tr>
						<td>Username</td>
						<td><input type='text' name='username' /></td>
					</tr>
					<tr>
						<td>Password</td>
						<td><input type='password' name='password' /></td>
					</tr>
					<tr>
						<td colspan='2' align='center'><input type='submit' name='login' value='Login' /></td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
</table>
