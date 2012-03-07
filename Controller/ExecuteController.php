<?php
App::uses('Folder','Utility');
App::uses('File', 'Utility');
App::uses('Model', 'Model');
App::uses('ConnectionManager', 'Model');
class ExecuteController extends AppController {


	public function index(){
		$Folder = new Folder(APP.'setup');
		if(!$Folder->path){
			throw new CakeException("Error Not Found  : ".APP.'setup');
		}
		$file = $Folder->read();
		if(!empty($file[1])){
			foreach ($file[1] as $key => $value) {
				$set[$value] = $value;
			}
			$this->set('files',$set);
		}else{
			throw new CakeException("Error Not Found Files in  : ".APP.'setup');
		}
	}

	public function run(){
		if($this->request->is('post')){
			$db = ConnectionManager::getDataSource('default');
			if(!$db->isConnected()) {
				$this->Session->setFlash(__('Could not connect to database.', true), 'default', array('class' => 'error'));
			} else {
				foreach($this->request->data['select_file'] as $file){
					$file_info = pathinfo($file);
					$modelClass = $file_info['filename'];
					$data = yaml_parse_file(APP.'setup/'.$file);
					$this->loadModel($modelClass);
					foreach($data as $key=>$row){
						$this->{$modelClass}->order = '';
						$this->{$modelClass}->create();
						$this->{$modelClass}->cacheQueries = false;
						$this->{$modelClass}->Behaviors->attach('Containable');
						$this->{$modelClass}->contain();
						$this->{$modelClass}->set($row);
						$this->{$modelClass}->Behaviors->disable('Tree');
						$this->{$modelClass}->save($row,false);
					}
					if(isset($this->{$modelClass}->actsAs) && array_key_exists('Tree',$this->{$modelClass}->actsAs)){
						$this->{$modelClass}->order = '';
						$this->{$modelClass}->Behaviors->enabled('Tree');
						$this->{$modelClass}->recover();
					}
				}
			}
		}else{
			$this->redirect(array('action'=>'index'));
		}
	}
}

