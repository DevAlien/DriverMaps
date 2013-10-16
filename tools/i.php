<?php
include '../libs/spyc.php';
$appdir = '../app/';
$routing = Spyc::YAMLLoad('../app/config/default.routing.yaml');
$controllers = array();
foreach($routing as $a)
	$controllers[$a['param']['controller']][] = $a['param']['action'];

echo '<h1>Generating Controllers</h1>';
foreach($controllers as $controller => $actions){
	if(file_exists($appdir . 'controllers/Controller'.ucfirst($controller).'.php')){
		$fileC = str_replace("}\n?>", '', file_get_contents($appdir . 'controllers/Controller'.ucfirst($controller).'.php'));
		$fileContent = '';
		if(!is_writable($appdir . 'controllers/Controller'.ucfirst($controller).'.php')){
			echo 'Controller ' . $controller . ' already exists but <span style="color: red">we can\'t modify it, change the permissions to 777 of ( ' . $appdir . 'controllers/Controller'.ucfirst($controller).'.php )</span><br />';
			continue;
		}
		echo 'Controller ' . $controller . ' already exists, we will modify it<br /><ul>';
		foreach($actions as $action){
			if(!preg_match('/public function '.$action.'Action\(\) {/', $fileC)){
				$fileContent .= "\tpublic function " . $action ."Action() {\n\n\t}\n\n";
				echo '<li>Action ' . $action . ' => New Action, <span style="color: green">Written</span></li>';
			}
			else
				echo '<li>Action ' . $action . ' => Already exists</li>';
		}
		echo '</ul>';
		file_put_contents($appdir . 'controllers/Controller'.ucfirst($controller).'.php', $fileC . $fileContent . "}\n?>");
	}
	else{
		if(!is_writable($appdir . 'controllers')){
			echo 'Controller ' . $controller . ' does not exists but <span style="color: red">we can\'t create a file, change the permissions to 777 of ( ' . $appdir . 'controllers/ )</span><br />';
			continue;
		}
		$fileStart = "<?php\n\nclass Controller" . ucfirst($controller) . " extends Controller {\n\n";
		echo 'Controller ' . $controller . 'does not exists, <span style="color: green">File Created</span><br><ul>';
		foreach($actions as $action){
			$fileStart .= "\tpublic function " . $action ."Action() {\n\n\t}\n\n";
			echo '<li>Action ' . $action . ' => <span style="color: green">Written</span></li>';
		}
		echo '</ul>';
		file_put_contents($appdir . 'controllers/Controller' . ucfirst($controller) . '.php',$fileStart . "}\n?>");
	}
}

$models = Spyc::YAMLLoad('models.yaml');
echo '<br /><h1>Generating Models</h1>';
foreach($models as $model => $fields){
	if(file_exists($appdir . 'models/Model'.ucfirst($model).'.php')){
		if(!is_writable($appdir . 'models/Model'.ucfirst($model).'.php')){
			echo 'Model ' . $controller . ' already exists but <span style="color: red">we can\'t create a file, change the permissions to 777 of ( ' . $appdir . 'models/Model'.ucfirst($controller).'.php )</span><br />';
			continue;
		}
		$fileC = file_get_contents($appdir . 'models/Model'.ucfirst($model).'.php');
		$fileStart = "public function setTableDefinition() {\n\t\t" . '$this->setBeanName(\'' . $model . '\');';
		echo 'Model ' . $model . ' already exists, we will modify it<br><ul>';
		foreach($fields as $field => $specs){
			$fileStart .= "\n\t\t" . '$this->addColumn(\'' . $field . '\', array(' . "\n";
			$cs = count($specs);
			$csi = 1;
			echo '<li>Writing a field: ' . $field . '</li><ul>';
			foreach($specs as $spec => $value){
				$fileStart .= "\t\t\t'" . $spec . "' => ";
				$write = '<li>Writing Parameter: '. $spec . ' => ';
				if(is_array($value)){
					$fileStart .= 'array(';
					$i = 0;
					foreach($value as $v){
						if($i > 0){
							$fileStart .=  ', ';
							$write .=  ', ';
						}
						$fileStart .= printTyped($v);
						$write .= printTyped($v);
						$i++;
					}
					$fileStart .= ')'  . (($csi == $cs) ? "\n" : ",\n");
					echo $write . '</li>';
				}
				else{
					$fileStart .= printTyped($value) . (($csi == $cs) ? "\n": ",\n");
					echo $write . printTyped($value) . '</li>';
				}
				$csi++;
			}
			echo '</ul>';
			$fileStart .= "\t\t));";
		}
		echo '</ul>';
		$fileStart .= "\n\t}";
		
		$fileC = preg_replace('/public function setTableDefinition\(\) {([\S|\s]*?)}/', $fileStart, $fileC);
		file_put_contents($appdir . 'models/Model'.ucfirst($model).'.php', $fileC);
	}
	else {
		$fileStart = "<?php\n\nclass Model" . ucfirst($model) . " extends Model {\n\n\tpublic function setTableDefinition() {\n\t\t" . '$this->setBeanName(\'' . $model . '\');';
		$localActions = array();
		if(!is_writable($appdir . 'models')){
			echo 'Model ' . $controller . ' does not exists but <span style="color: red">we can\'t create a file, change the permissions to 777 of ( ' . $appdir . 'models )</span><br />';
			continue;
		}
		echo 'Model ' . $model . ' does not exists, <span style="color: green">File Created</span><br><ul>';
		foreach($fields as $field => $specs){
			$fileStart .= "\n\t\t" . '$this->addColumn(\'' . $field . '\', array(' . "\n";
			$cs = count($specs);
			$csi = 1;
			echo '<li>Writing a field: ' . $field . '</li><ul>';
			foreach($specs as $spec => $value){
				$fileStart .= "\t\t\t'" . $spec . "' => ";
				$write = '<li>Writing Parameter: '. $spec . ' => ';
				if(is_array($value)){
					$fileStart .= 'array(';
					$i = 0;
					foreach($value as $v){
						if($i > 0){
							$fileStart .=  ', ';
							$write .=  ', ';
						}
						$fileStart .= printTyped($v);
						$write .= printTyped($v);
						$i++;
					}
					$fileStart .= ')'  . (($csi == $cs) ? "\n" : ",\n");
					echo $write . '</li>';
				}
				else{
					$fileStart .= printTyped($value) . (($csi == $cs) ? "\n": ",\n");
					echo $write . printTyped($value) . '</li>';
				}
				$csi++;
			}
			echo '</ul>';
			$fileStart .= "\t\t));";
		}
		echo '</ul>';
		$fileStart .= "\n\t}";
		file_put_contents($appdir . 'models/Model' . ucfirst($model) . '.php',$fileStart . $fileContent . "}\n?>");
	}
}

function printTyped($string){
	switch(gettype($string)){
		case 'string':
			return '\''.$string.'\'';
			break;
		case 'integer':
		case 'double':
			return $string;
			break;
		case 'boolean':
			return ($string) ? 'true' : 'false';
			break;
	}
}
?>