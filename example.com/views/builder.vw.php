<form name='builder' method='POST' action=''>
	<table border='0' cellpadding='0' cellspacing='0' width='100%'>
		<tr>
			<td>
				<table border='0' cellpadding='5' cellspacing='1'>
					<tr>
						<td><b><?=$LANGUAGE['classi_esistenti']; ?></b></td>
						<td>
							<select name='existent_class' id='existent_class'>
								<option value=''><?=$LANGUAGE['nuova']; ?></option><?php
								if(isset($classi) && is_array($classi) && count($classi)>0){
									foreach($classi as $c){
										?><option value='<?=$c;?>'><?=$c;?></option><?php
									}
								}
								?>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table border='0' cellpadding='5' cellspacing='1'>
					<tr>
						<td><b><?=$LANGUAGE['nome_della_classe']; ?></b></td>
						<td><input type='text' name='classname' id='classname' /></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table border='0' cellpadding='0' cellspacing='0' width='100%'>
					<tr>
						<td valign='top' width='100%'>
							<fieldset>
								<legend><?=$LANGUAGE['metodi']; ?></legend>
								<table border='0' cellpadding='5' cellspacing='1'>
									<tr>
										<td>
											<table border='0' cellpadding='5' cellspacing='1'>
												<thead>
													<tr>
														<th style='width: 70px;'><?=$LANGUAGE['visibilita']; ?></th>
														<th style='width: 140px;'><?=$LANGUAGE['nome']; ?></th>
														<th style='width: 140px;'><?=$LANGUAGE['codice']; ?></th>
														<th><?=$LANGUAGE['parametri']; ?></th>
														<th><a href='javascript:void(0);' onclick='add_method();'>+</a></th>
													</tr>
												</thead>
												<tbody id='btable_methods'>
													<tr id='main_method'>
														<td></td>
														<td valign='top'><?=$LANGUAGE['codice_principale']; ?></td>
														<td valign='top'></td>
														<td valign='top'><textarea name='start_code' id='start_code' rows='10' cols='80' style='margin:0px;' onkeyup="update_editor(this.value);"></textarea><div id='editor'><pre id='editor' class='brush: php;'></pre></div></td>
														<td></td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</table>
							</fieldset>
						</td>
						<td width='200' valign='top'>
							<fieldset>
								<legend><?=$LANGUAGE['attributi']; ?></legend>
								<table border='0' cellpadding='5' cellspacing='1'>
									<thead>
										<tr>
											<td align='center' width='80'><?=$LANGUAGE['visibilita']; ?></td>
											<td align='center' width='150'><?=$LANGUAGE['nome']; ?></td>
											<td align='center' width='150'><?=$LANGUAGE['valore_default']; ?></td>
											<td align='center' width='150'><?=$LANGUAGE['tipo']; ?></td>
											<td align='center' valign='top'><a href='javascript:void(0);' onclick='add_attr();'>+</a></td>
										</tr>
									</thead>
									<tbody id='btable_attributes'>
										<tr id='row_0'>
											<td>
												<select name='visibility[0]' id='visibility[0]'>
													<option value='public'>public</option>
													<option value='private'>private</option>
													<option value='var' selected='selected'>var</option>
												</select>
											</td>
											<td>
												<input type='text' name='name[0]' id='name[0]' value='' />
											</td>
											<td>
												<input type='text' name='value[0]' id='value[0]' value='' />
											</td>
											<td>
												<select name='type[0]' id='type[0]'>
													<option value='null'>null</option>
													<option value='string'>string</option>
													<option value='integer'>integer</option>
													<option value='number'>number</option>
													<option value='boolean'>boolean</option>
												</select>
											</td>
											<td>
												<a href='javascript:void(0);' onclick="remove_row('row_0');">X</a>
											</td>
										</tr>
									</tbody>
								</table>
							</fieldset>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align='center'><input type='submit' name='invia' value='Build' /></td>
		</tr>
	</table>
	<script type='text/javascript' language='Javascript'>
		var visibility = ['public','private','var'];
		var type = ['null','string','integer','number','boolean'];
		var default_visibility = 'var';
		var nattr = 1;
		var nmethod = 1;
		
		function update_editor(val){
			//document.getElementById('editor').innerHTML = "<pre id='editor' class='brush: php;'>"+val+"</pre>";
			//SyntaxHighlighter.highlight();
		}
		
		function add_attr(){
			var row,cell,input,select,a;
			
			row = document.createElement('tr');
			row.setAttribute('id', 'row_'+nattr);
			
			/*var cell = document.createElement('td');
			cell.innerHTML = "Tipo";
			row.appendChild(cell);*/
			
			cell = document.createElement('td');
			select = document.createElement('select');
			select.setAttribute('id','visibility['+nattr+']');
			select.setAttribute('name','visibility['+nattr+']');
			for(var i=0; i<visibility.length; i++){
				var option = document.createElement('option');
				option.innerHTML = visibility[i];
				option.setAttribute('value',visibility[i]);
				if(visibility[i] == default_visibility){
					option.setAttribute('selected','selected');
					option.selected = true;
				}
				select.appendChild(option);
			}
			cell.appendChild(select);
			row.appendChild(cell);
			
			cell = document.createElement('td');
			input = document.createElement('input');
			input.setAttribute('type','text');
			input.setAttribute('name','name['+nattr+']');
			input.setAttribute('id','name['+nattr+']');
			cell.appendChild(input);
			row.appendChild(cell);
			
			cell = document.createElement('td');
			input = document.createElement('input');
			input.setAttribute('type','text');
			input.setAttribute('name','value['+nattr+']');
			input.setAttribute('id','value['+nattr+']');
			cell.appendChild(input);
			row.appendChild(cell);
			
			cell = document.createElement('td');
			select = document.createElement('select');
			select.setAttribute('id','type['+nattr+']');
			select.setAttribute('name','type['+nattr+']');
			for(var i=0; i<type.length; i++){
				var option = document.createElement('option');
				option.innerHTML = type[i];
				option.setAttribute('value',type[i]);
				select.appendChild(option);
			}
			cell.appendChild(select);
			row.appendChild(cell);
			
			cell = document.createElement('td');
			a = document.createElement('a');
			a.setAttribute('href','javascript:void(0);');
			a.setAttribute('onclick','remove_row("row_'+nattr+'")');
			a.innerHTML = 'X';
			cell.appendChild(a);
			row.appendChild(cell);
			
			document.getElementById('btable_attributes').appendChild(row);
			
			nattr++;
		}
		
		function add_method(){
			var row,cell,input,textarea,a;
			
			row = document.createElement('tr');
			row.setAttribute('id', 'method_'+nmethod);
			
			cell = document.createElement('td');
			cell.setAttribute('valign','top');
			select = document.createElement('select');
			select.setAttribute('id','visibility_method['+nmethod+']');
			select.setAttribute('name','visibility_method['+nmethod+']');
			var option = document.createElement('option');
			option.innerHTML = "<?=$LANGUAGE['scegli']; ?>";
			option.setAttribute('value','');
			option.setAttribute('selected','selected');
			option.selected = true;
			select.appendChild(option);
			for(var i=0; i<visibility.length; i++){
				var option = document.createElement('option');
				option.innerHTML = visibility[i];
				option.setAttribute('value',visibility[i]);
				select.appendChild(option);
			}
			cell.appendChild(select);
			row.appendChild(cell);
			
			cell = document.createElement('td');
			cell.setAttribute('valign','top');
			input = document.createElement('input');
			input.setAttribute('id','name_method['+nmethod+']');
			input.setAttribute('name','name_method['+nmethod+']');
			input.setAttribute('type','text');
			input.setAttribute('size','20');
			cell.appendChild(input);
			row.appendChild(cell);
			
			cell = document.createElement('td');
			cell.setAttribute('valign','top');
			input = document.createElement('input');
			input.setAttribute('id','parameters_method['+nmethod+']');
			input.setAttribute('name','parameters_method['+nmethod+']');
			input.setAttribute('type','text');
			input.setAttribute('size','20');
			cell.appendChild(input);
			row.appendChild(cell);
			
			cell = document.createElement('td');
			cell.setAttribute('valign','top');
			textarea = document.createElement('textarea');
			textarea.setAttribute('id','code_method['+nmethod+']');
			textarea.setAttribute('name','code_method['+nmethod+']');
			textarea.setAttribute('rows','10');
			textarea.setAttribute('cols','80');
			textarea.setAttribute('style','margin:0px;');
			cell.appendChild(textarea);
			row.appendChild(cell);
			
			cell = document.createElement('td');
			cell.setAttribute('valign','top');
			a = document.createElement('a');
			a.setAttribute('href','javascript:void(0);');
			a.setAttribute('onclick','remove_row("method_'+nmethod+'")');
			a.innerHTML = 'X';
			cell.appendChild(a);
			row.appendChild(cell);
			
			document.getElementById('btable_methods').appendChild(row);
			
			editAreaLoader.init({
				id: "code_method["+nmethod+"]"	// id of the textarea to transform		
				,start_highlight: true
				,font_size: "8"
				,font_family: "verdana, monospace"
				,allow_resize: "y"
				,allow_toggle: false
				,language: "en"
				,syntax: "php",
				show_line_colors: true
			});
			
			nmethod++;
		}
		
		function remove_row(idrow){
			if (document.getElementById && document.getElementById(idrow)){
				var theNode = document.getElementById(idrow);
				theNode.parentNode.removeChild(theNode);
			}
			else if (document.all && document.all[idrow]){
				document.all[idrow].innerHTML='';
				document.all[idrow].outerHTML='';
			}
			// OBSOLETE CODE FOR NETSCAPE 4 
			else if (document.layers && document.layers[idrow]){
				document.layers[idrow].visibility='hide';
				delete document.layers[idrow];
			}
		}
	</script>
</form>
<?php
/*$this->html()->set_type('html')
	->table(array('border'=>0,'cellpadding'=>5,'cellspacing'=>1))
		->tr()
			->td()->b()->add_text("Nome della classe")->_b()->_td()
			->td()->input(array('type'=>'text','name'=>'classname','id'=>'classname'))->_td()
		->_tr()
	->_table()
	->br();
*/
