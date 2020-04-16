<?php
App::uses('View', 'View');

class CmsView extends View {

    public $cmstheme = null;

	protected $_passedVars = array(
		'viewVars', 'autoLayout', 'ext', 'helpers', 'view', 'layout', 'name', 'theme', 'cmstheme',
		'layoutPath', 'viewPath', 'request', 'plugin', 'passedArgs', 'cacheAction'
	);

    public function templ_func(){
        exit('TEMPL FUNC');
    }

    protected function _sort_paths($_paths) {
        $paths = array();
        $data = array();
        foreach($_paths as $path){
            if(strpos($path, 'web_themes/') !== false) $data[1][] = $path; else
            if(strpos($path, 'web_views/') !== false) $data[2][] = $path; else
            if(strpos($path, 'app/') !== false) $data[3][] = $path; else
            if(strpos($path, 'lib/') !== false) $data[4][] = $path; else
            $data[5][] = $path;
        }
        foreach($data as $val){
            foreach($val as $_val){
                $paths[] = $_val;
            }
        }
        return $paths;
    }

	protected function cms_evaluate($viewFile, $dataForView) {
		//extract($dataForView);
		//ob_start();
		//include $viewFile;
		//return ob_get_clean();
	}

	public function aelement($name, $data = array(), $options = array()) {
		$file = $plugin = null;

		if (isset($options['plugin'])) {
			$name = Inflector::camelize($options['plugin']) . '.' . $name;
		}

		if (!isset($options['callbacks'])) {
			$options['callbacks'] = false;
		}

		if (isset($options['cache'])) {
			$contents = $this->_elementCache($name, $data, $options);
			if ($contents !== false) {
				return $contents;
			}
		}

		$file = $this->_getAElementFilename($name);
		if ($file) {
			return $this->_renderElement($file, $data, $options);
		}

		if (empty($options['ignoreMissing'])) {
			list ($plugin, $name) = pluginSplit($name, true);
			$name = str_replace('/', DS, $name);
			$file = $plugin . 'Elements' . DS . $name . $this->ext;
			trigger_error(__d('cake_dev', 'Element Not Found: %s', $file), E_USER_NOTICE);
		}
	}

 	protected function _getAElementFilename($name) {
	    if(substr($name, 0, 8) == 'elements'){
           return $this->_getElementFileName(substr($name, 9));
	    }
		$subDir = null;

		if (!is_null($this->subDir)) {
			$subDir = $this->subDir . DS;
		}

		if ($name === null) {
			$name = $this->view;
		}
		$name = str_replace('/', DS, $name);
		list($plugin, $name) = $this->pluginSplit($name);
        $_plugin = Configure::read('Config.view_tid');

		if (strpos($name, DS) === false && $name[0] !== '.') {
			$name = $this->viewPath . DS . $subDir . Inflector::underscore($name);
		} elseif (strpos($name, DS) !== false) {
			if ($name[0] === DS || $name[1] === ':') {
				if (is_file($name)) {
					return $name;
				}
				$name = trim($name, DS);
			} elseif ($name[0] === '.') {
				$name = substr($name, 3);
			} elseif (!$plugin || $this->viewPath !== $this->name) {
				$name = $this->viewPath . DS . $subDir . $name;
			}
		}

        if(substr_count($name, 'Item') || substr_count($name, 'Base')){
            if(substr_count($name, ucfirst($_plugin)) && strlen($name) > strlen($_plugin)){
                $name = str_replace(ucfirst($_plugin), '', $name);
            }
            if(substr_count($name, ucfirst($plugin)) && strlen($name) > strlen($plugin)){
                $name = str_replace(ucfirst($plugin), '', $name);
            }
        }

        if(substr_count($name, 'admin_pbl_')){
            $name = str_replace('admin_pbl_', 'admin_', $name);
        }

		$paths = $this->_paths($plugin);
        $paths = $this->_sort_paths($paths);
		$exts = $this->_getExtensions();

        $sub_paths = array('Actions', '');
        if(!empty($this->cmstheme)){
            if(strpos($this->cmstheme, '_') !== false){
                $sub_paths = am(array(strstr($this->cmstheme, '-', true) . DS .  'Actions'), $sub_paths);
            }
            $sub_paths = am(array($this->cmstheme . DS .  'Actions'), $sub_paths);
        }

		$cases = array(strtolower($plugin . '_' . str_replace(strtolower($plugin), '', $name)), $name);
        if(!empty($_name) || 1==1){
            $cases = am(array(strtolower($_plugin . '_' . $name), strtolower($plugin . '_' . $name)), $cases);
        }

        foreach ($exts as $ext) foreach ($paths as $path) foreach ($sub_paths as $sub_path) foreach($cases as $case){
            if (file_exists($path . $sub_path . DS .  $case . $ext)) {
                return $path . $sub_path . DS .  $case . $ext;
			}
        }

		return false;
	}

	public function telement($name, $data = array(), $options = array()) {
		$file = $plugin = null;

		if (isset($options['plugin'])) {
			$name = Inflector::camelize($options['plugin']) . '.' . $name;
		}

		if (!isset($options['callbacks'])) {
			$options['callbacks'] = false;
		}

		if (isset($options['cache'])) {
			$contents = $this->_elementCache($name, $data, $options);
			if ($contents !== false) {
				return $contents;
			}
		}

		$file = $this->_getTElementFilename($name);
		if ($file) {
			return $this->_renderElement($file, $data, $options);
		}

		if (empty($options['ignoreMissing'])) {
			list ($plugin, $name) = pluginSplit($name, true);
			$name = str_replace('/', DS, $name);
			$file = $plugin . 'Elements' . DS . $name . $this->ext;
			trigger_error(__d('cake_dev', 'Element Not Found: %s', $file), E_USER_NOTICE);
		}
	}


 	protected function _getTElementFilename($name) {
		list($plugin, $name) = $this->pluginSplit($name);

        if(strpos($name, '/')) list($_plugin, $name) = explode('/', $name); else $_plugin = null;

        if(strpos($name, ',') !== false){
            $_name = explode(',', $name);
            $name = $_name[0];
			$__name = $_name[2];
            $_name = $_name[1];
        }

		$paths = $this->_paths($plugin);
        $paths = $this->_sort_paths($paths);
		$exts = $this->_getExtensions();

        $sub_paths = array('Templates');
        if(!empty($this->cmstheme)){
            if(strpos($this->cmstheme, '_') !== false){
                $sub_paths = am(array(strstr($this->cmstheme, '-', true) . DS .  'Templates'), $sub_paths);
            }
            $sub_paths = am(array($this->cmstheme . DS .  'Templates'), $sub_paths);
        }

		$cases = array(strtolower($_plugin) . DS . $name);
        if(!empty($_name)){
            array_unshift($cases, strtolower($_plugin) . DS . $name . DS . $_name, $name . DS . $_name);
            if(!empty($__name)) array_unshift($cases, strtolower($_plugin) . DS . $name . DS . $_name . '-' . $__name, $name . DS . $_name . '-' . $__name);
        }

        foreach ($exts as $ext) foreach ($paths as $path) foreach ($sub_paths as $sub_path) foreach($cases as $case){
			if (file_exists($path . $sub_path . DS .  $case . $ext)) {
                return $path . $sub_path . DS .  $case . $ext;
			}
        }

		return false;
	}


 	protected function _getElementFileName($name) {
		list($plugin, $name) = $this->pluginSplit($name);

        if(strpos($name, '/')) list($_plugin, $name) = explode('/', $name); else $_plugin = null;

        if(strpos($name, ',') !== false){
            $_name = explode(',', $name);
            $name = $_name[0];
            $_name = $_name[1];
        }

		$paths = $this->_paths($plugin);
        $paths = $this->_sort_paths($paths);
		$exts = $this->_getExtensions();

        $sub_paths = array('Elements');
        if(!empty($this->cmstheme)){
            if(strpos($this->cmstheme, '_') !== false){
                $sub_paths = am(array(strstr($this->cmstheme, '-', true) . DS .  'Elements'), $sub_paths);
            }
            $sub_paths = am(array($this->cmstheme . DS .  'Elements'), $sub_paths);
        }

		$cases = array(strtolower($_plugin) . DS . $name, $name);
        if(!empty($_name)){
            $cases = am(array(strtolower($_plugin) . DS . $name  . '_' . $_name, strtolower($_plugin) . DS . $_name), $cases);
        }

        foreach ($exts as $ext) foreach ($paths as $path) foreach ($sub_paths as $sub_path) foreach($cases as $case){
            //_pr($path . $sub_path . DS .  $case . $ext);
            if (file_exists($path . $sub_path . DS .  $case . $ext)) {
                return $path . $sub_path . DS .  $case . $ext;
			}
        }

		return false;
	}

	protected function _getViewFileName($name = null) {
	    if(substr($name, 0, 8) == 'elements'){
           return $this->_getElementFileName(substr($name, 9));
	    }
		$subDir = null;

		if (!is_null($this->subDir)) {
			$subDir = $this->subDir . DS;
		}

		if ($name === null) {
			$name = $this->view;
		}
		$name = str_replace('/', DS, $name);
		list($plugin, $name) = $this->pluginSplit($name);
        $_plugin = Configure::read('Config.view_tid');

		if (strpos($name, DS) === false && $name[0] !== '.') {
			$name = $this->viewPath . DS . $subDir . Inflector::underscore($name);
		} elseif (strpos($name, DS) !== false) {
			if ($name[0] === DS || $name[1] === ':') {
				if (is_file($name)) {
					return $name;
				}
				$name = trim($name, DS);
			} elseif ($name[0] === '.') {
				$name = substr($name, 3);
			} elseif (!$plugin || $this->viewPath !== $this->name) {
				$name = $this->viewPath . DS . $subDir . $name;
			}
		}

        if(substr_count($name, 'Item') || substr_count($name, 'Base')){
            if(substr_count($name, ucfirst($_plugin)) && strlen($name) > strlen($_plugin)){
                $name = str_replace(ucfirst($_plugin), '', $name);
            }
            if(substr_count($name, ucfirst($plugin)) && strlen($name) > strlen($plugin)){
                $name = str_replace(ucfirst($plugin), '', $name);
            }
        }

        if(substr_count($name, 'admin_pbl_')){
            $name = str_replace('admin_pbl_', 'admin_', $name);
        }

		$paths = $this->_paths($plugin);
        $paths = $this->_sort_paths($paths);
		$exts = $this->_getExtensions();

        $sub_paths = array('Actions', '');
        if(!empty($this->cmstheme)){
            if(strpos($this->cmstheme, '_') !== false){
                $sub_paths = am(array(strstr($this->cmstheme, '-', true) . DS .  'Actions'), $sub_paths);
            }
            $sub_paths = am(array($this->cmstheme . DS .  'Actions'), $sub_paths);
        }

		$cases = array(strtolower($plugin . '_' . str_replace(strtolower($plugin), '', $name)), $name);
		if($this->layout != 'default') $cases = am(array(strtolower($plugin . '_' . str_replace(strtolower($plugin), '', $name)) . '-' . $this->layout, $name . '-' . $this->layout), $cases); ;
        if(!empty($_name) || 1==1){
            $cases = am(array(strtolower($_plugin . '_' . $name), strtolower($plugin . '_' . $name)), $cases);
            if($this->layout != 'default') $cases = am(array(strtolower($_plugin . '_' . $name) . '-' . $this->layout, strtolower($plugin . '_' . $name) . '-' . $this->layout), $cases);
        }

        //pr($cases);


        foreach ($exts as $ext) foreach ($paths as $path) foreach ($sub_paths as $sub_path) foreach($cases as $case){
            if (file_exists($path . $sub_path . DS .  $case . $ext)) {
                return $path . $sub_path . DS .  $case . $ext;
			}
        }

		$defaultPath = $paths[0];

		if ($this->plugin) {
			$pluginPaths = App::path('plugins');
			foreach ($paths as $path) {
				if (strpos($path, $pluginPaths[0]) === 0) {
					$defaultPath = $path;
					break;
				}
			}
		}
		throw new MissingViewException(array('file' => $defaultPath . 'Actions' . DS .  strtolower($_plugin . '_' . $name) . $this->ext));
	}

	protected function _getLayoutFileName($name = null) {

		if ($name === null) {
			$name = $this->layout;
		}
		$subDir = null;

		if ($this->layoutPath !== null) {
			$subDir = $this->layoutPath . DS;
		}
		list($plugin, $name) = $this->pluginSplit($name);
		$paths = $this->_paths($plugin);
        $paths = $this->_sort_paths($paths);

        $_file = array();

        if(!empty($this->cmstheme)){
            $_file[] =  $this->cmstheme . DS . 'Layouts' . DS . $subDir . $name;
            $_file[] =  $this->cmstheme . DS . 'Layouts' . DS . $subDir . 'default';
        }

        $_file[] = 'Layouts' . DS . $subDir . $name;
        $_file[] = 'Layouts' . DS . $subDir . 'default';

        foreach($_file as $file){
    		$exts = $this->_getExtensions();
    		foreach ($exts as $ext) {
    			foreach ($paths as $path) {
    				if (file_exists($path . $file . $ext)) {
    					return $path . $file . $ext;
    				}
    			}
    		}
        }

		throw new MissingLayoutException(array('file' => $paths[0] . $file . $this->ext));
	}


}