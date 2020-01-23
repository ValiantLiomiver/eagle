<?php

class builder extends Eagle_controller{
	
	function builder(){
		parent::__construct();
	}
	
	function builder_start(){
		if(!$this->post){
			$classi = array();
			$handle = opendir($this->get_path_controller());
			while (false !== ($entry = readdir($handle))) {
				if($entry!='.' && $entry!='..'){
					$classi[] = str_replace(".".$this->get_controller_ext(),'',$entry);
				}
			}
			
			//let's load italian language
			$LANGUAGE = $this->load()->language("italiano");
			
			$links = array(
				"<script type=\"text/javascript\" src=\"/media/js/edit_area/edit_area_full.js\"></script>",
				"<script type=\"text/javascript\">
					editAreaLoader.init({
						id: \"start_code\"	// id of the textarea to transform		
						,start_highlight: true
						,font_size: \"8\"
						,font_family: \"verdana, monospace\"
						,allow_resize: \"y\"
						,allow_toggle: false
						,language: \"".$LANGUAGE['lang']."\"
						,syntax: \"php\",
						show_line_colors: true	
					});
				</script>",
				'<link rel="stylesheet" type="text/css" href="/media/css/site.css" />'
			);
			
			$this->load()->view("parts/header_v2",array('title'=>"Mio progetto",'links'=>$links))
				->view("builder",array('classi'=>&$classi,'LANGUAGE'=>&$LANGUAGE))
			->view("parts/footer");
		}
		else{
			//var_dump($this->post);
			$classname = '';
			$start_code = '';
			$types = $names = $values = $name_methods = $code_methods = $parameters_methods = $visibility_methods = array();
			$this->set_type_post_var('classname','string',$classname);
			$this->set_type_post_var('visibility','string[]',$visibilities);
			$this->set_type_post_var('name','string[]',$names);
			$this->set_type_post_var('value','string[]',$values);
			$this->set_type_post_var('type','string[]',$types);
			$this->set_type_post_var('start_code','string',$start_code);
			
			$this->set_type_post_var('name_method','string[]',$name_methods);
			$this->set_type_post_var('code_method','string[]',$code_methods);
			$this->set_type_post_var('parameters_method','string[]',$parameters_methods);
			$this->set_type_post_var('visibility_method','string[]',$visibility_methods);
			//var_dump($types,$names,$values);
			$output = array();
			$output[] = "<?php\n";
			$output[] = "class ".$classname." extends Eagle_controller{\n";
			foreach($visibilities as $id=>$visibility){
				if(array_key_exists($id,$names) && strlen($names[$id])>0){
					$output[] = "\t".$visibility.' $'.$names[$id].((array_key_exists($id,$values) && strlen($values[$id])>0)?(" = ".$this->get4type($values[$id],((array_key_exists($id,$types) && $types[$id])?($types[$id]):('null')))):('')).";\n";
				}
			}
			
			$output[] = "\tfunction ".$classname."(){\n";
			$output[] = "\t\tparent::__construct();\n";
			$output[] = "\t}\n";
			
			$output[] = "\tfunction ".$classname."_start(){\n";
			$output[] = "\t\t".str_replace(array("\n"),array("\n\t\t"),$start_code)."\n";
			$output[] = "\t}\n";
			
			if(count($name_methods)>0){
				foreach($name_methods as $id=>$name_method){
					if(array_key_exists($id,$code_methods) && strlen($code_methods[$id])>0){
						$output[] = "\t".((array_key_exists($id,$visibility_methods) && strlen($visibility_methods[$id])>0)?($visibility_methods[$id]." "):(''))."function ".$name_method.((array_key_exists($id,$parameters_methods) && strlen($parameters_methods[$id])>0)?("(".$parameters_methods[$id].")"):("()"))."{\n";
						$output[] = "\t\t".str_replace(array("\n"),array("\n\t\t"),$code_methods[$id])."\n";
						$output[] = "\t}\n";
					}
				}
			}
			
			
			$output[] = "}";
			
			header('Content-Description: File Transfer');
			header("Content-Type: text/php");
			header('Content-Disposition: attachment; filename='.$classname.".".$this->extension);
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Content-Length: '.filesize($output));
			
			echo implode('',$output);
		}
	}
	
	private function get4type($val,$type){
		switch($type){
			case 'string': $val = "'".$val."'"; break;
			case 'integer': $val = intval(trim($val)); break;
			case 'number': $val = str_replace(',','.',trim($val)); break;
			case 'boolean': $val = (is_bool($val) || $val=='true')?'true':'false'; break;
			case 'null': default: break;
		}
		return $val;
	}
}
