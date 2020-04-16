<?php
class LayoutHelper extends AppHelper {
    public $helpers = array('Html', 'Session', 'Form');
    
    public function form_save($mode = null){
        if($mode == 'simple'){
            return $this->Form->button(___('Save & Close'), array('name' => 'saction', 'value' => '2')) . $this->Form->button(___('Cancel'), array('onclick' => "window.history.back();", 'type' => 'button'));
        }
        if($mode == 'simple_save'){
            return $this->Form->button(___('Save'), array('name' => 'saction', 'value' => '1')) . $this->Form->button(___('Cancel'), array('onclick' => "window.history.back();", 'type' => 'button'));
        }
        if($mode == 'simple_modify'){
            return $this->Form->button(___('Save'), array('name' => 'saction', 'value' => '1')) . $this->Form->button(___('Save & Close'), array('name' => 'saction', 'value' => '2')) . $this->Form->button(___('Cancel'), array('onclick' => "window.history.back();", 'type' => 'button'));
        }
        if($this->request->edit_mode == 'modify'){
            return $this->Form->button(___('Save & Close'), array('name' => 'saction', 'value' => '2')) . $this->Form->button(___('Save'), array('name' => 'saction', 'value' => '1')) . $this->Form->button(___('Copy'), array('name' => 'saction', 'value' => '4')) . $this->Form->button(___('Cancel'), array('onclick' => "window.history.back();", 'type' => 'button'));
        } else {
            return $this->Form->button(___('Save & Close'), array('name' => 'saction', 'value' => '2')) . $this->Form->button(___('Save'), array('name' => 'saction', 'value' => '3')) . $this->Form->button(___('Save & Repeat'), array('name' => 'saction', 'value' => '1')) . $this->Form->button(___('Save & Copy'), array('name' => 'saction', 'value' => '1_1')) . $this->Form->button(___('Cancel'), array('onclick' => "window.history.back();", 'type' => 'button'));
        }
    }
    
    public function __tree($fieldName, $options = array(), $disabled = false){
        $output = null;
        
        $keys = array();
        foreach(array_reverse($options['options'], true) as $parent => $vals){
            foreach($vals as $key => $val){
                if($_GET[$fieldName] == $key || in_array($key, $keys)) $keys[$key] = $parent;
            }
        }
        
        if(empty($keys)) $keys[0] = '0';
        if(!in_array($_GET[$fieldName], $keys) && !empty($options['options'][$_GET[$fieldName]])) $keys = array('0' => $_GET[$fieldName]) + $keys;
        
        foreach(array_reverse($keys, true) as $selected => $key){
            $output .= $this->Form->input($fieldName, array('label' => false, 'disabled' => $disabled, 'options' => $options['options'][$key], 'selected' => $selected, 'empty' => ___('Choose...'), 'onchange' => "window.location='{$this->here}?{$fieldName}='+this.value")) . "\r\n";
        }
        
        return $output;
    }
    
    public function __image($fieldName, $options = array()){
        $output = '<div class="input file">';
        $output .= '<label>' . $options['label'] . '</label>';
        if(!empty($this->data['ObjOptAttachDef']['file'])){
            $output .= '<div class="Attach"><a target="_blank" href="'.'/' . $this->data['ObjOptAttachDef']['location'] . '/large/' . $this->data['ObjOptAttachDef']['file'].'"><img align="middle" src="' . (in_array(ws_ext($this->data['ObjOptAttachDef']['file']), ws_ext_img()) ? '/' . $this->data['ObjOptAttachDef']['location'] . '/thumb/' . $this->data['ObjOptAttachDef']['file'] : '/img/ext/' . ws_ext($this->data['ObjOptAttachDef']['file']) . '.png') . '"></a><a onclick="$(this).parent().remove();$(\'#Attach_file\').show();$(\'#attachments_remove'.$this->data['ObjOptAttachDef']['id'].'\').val(\'1\');" class="ico ico_remove"></a>'.(in_array(ws_ext($this->data['ObjOptAttachDef']['file']), ws_ext_img()) ? '<a href="/admin/system/thumb_image/'.$this->data['ObjOptAttachDef']['id'].'" class="ajx_win ico ico_image-resize"></a><a href="/admin/system/crop_image/'.$this->data['ObjOptAttachDef']['id'].'" class="ajx_win ico ico_crop"></a>' : null).'<a href="/admin/system/rename_image/'.$this->data['ObjOptAttachDef']['id'].'" class="ajx_win ico ico_rename"></a></div>';
            $output .= $this->Form->input('attachments_remove.' . $this->data['ObjOptAttachDef']['id'], array('type' => 'hidden', 'value' => '0'));
            $output .= '<div id="Attach_file" style="display: none;">';
        } else $output .= '<div>';
        $output .= $this->Form->input($fieldName, array_merge($options, array('type' => 'file', 'label' => false, 'div' => false)));
        $output .= '</div>';
        $output .= '</div>';
        $output .= $this->_View->element('/admin/attachment', array('id' => $this->Form->_modelScope.'Attachment'));
        return $output;
    }

    public function __attach($fieldName, $options = array()){
        $output = '<div class="input file">';
        $output .= '<label>' . $options['label'] . '</label>';
        if(!empty($this->data['ObjOptAttachType'][$fieldName]['file'])){
            $output .= '<div style="'.(!in_array(ws_ext($this->data['ObjOptAttachType'][$fieldName]['file']), ws_ext_img()) ? 'padding: 15px 0;' : null).'" class="Attach"><a target="_blank" href="'.'/' . $this->data['ObjOptAttachType'][$fieldName]['location'] . '/large/' . $this->data['ObjOptAttachType'][$fieldName]['file'].'"><img align="middle" src="' . (in_array(ws_ext($this->data['ObjOptAttachType'][$fieldName]['file']), ws_ext_img()) ? '/' . $this->data['ObjOptAttachType'][$fieldName]['location'] . '/thumb/' . $this->data['ObjOptAttachType'][$fieldName]['file'] : '/img/ext/' . ws_ext($this->data['ObjOptAttachType'][$fieldName]['file']) . '.png') . '"></a><a onclick="$(this).parent().remove();$(\'#Attach_file_'.$fieldName.'\').show();$(\'#attachments_remove'.$this->data['ObjOptAttachType'][$fieldName]['id'].'\').val(\'1\');" class="ico ico_remove"></a>'.'</div>';
            $output .= $this->Form->input('attachments_remove.' . $this->data['ObjOptAttachType'][$fieldName]['id'], array('type' => 'hidden', 'value' => '0'));
            $output .= '<div id="Attach_file_'.$fieldName.'" style="display: none;">';
        } else $output .= '<div>';
        $output .= $this->Form->input($this->Form->_modelScope.'.attachtype.' . $fieldName, array_merge($options, array('type' => 'file', 'label' => false, 'div' => false)));
        $output .= '</div>';
        $output .= '</div>';
        return $output;
    }

    public function __images($fieldName, $options = array()){
        $output_remove = null;
        $output = null;
        
        $output .= $this->Form->input("{$this->Form->_modelScope}.data.no_main_img", array('label' => ___('Main attachment'), 'options' => array('' => ___('Yes'), '1' => ___('No'), '3' => ___('Thumb')), 'selected' => $this->data[$this->Form->_modelScope]['data']['no_main_img']));
        $output .= '<div>&nbsp;</div>';
        
        //pr($this->data['ObjOptAttach']);
        
        $output .= '<div class="attachments_box uisortable">';
        if(!empty($this->data['ObjOptAttach'])) foreach($this->data['ObjOptAttach'] as $file){
            if(!empty($file['file'])){
                $output .= '<div>';
                if($file['location'] == 'video'){
                    $output .= '<div class="Attach" style="float: left; position: relative;"><a target="_blank" href="'.ws_video_url($file['file']).'"><img align="middle" src="' . ws_video_img($file['file']) . '"></a><a onclick="$(this).parent().parent().remove();$(\'#attachments_remove'.$file['id'].'\').val(\'1\');" class="ico ico_remove"></a>'  . '<span></span>' . '</div>';
                } else {
                    $output .= '<div class="Attach" style="float: left; position: relative;"><a target="_blank" href="'.'/' . $file['location'] . '/large/' . $file['file'].'"><img align="middle" src="' . (in_array(ws_ext($file['file']), ws_ext_img()) ? '/' . $file['location'] . '/thumb/' . $file['file'] : '/img/ext/' . ws_ext($file['file']) . '.png') . '"></a><a onclick="$(this).parent().parent().remove();$(\'#attachments_remove'.$file['id'].'\').val(\'1\');" class="ico ico_remove"></a>'.(in_array(ws_ext($file['file']), ws_ext_img()) ? '<a href="/admin/system/thumb_image/'.$file['id'].'" class="ajx_win ico ico_crop"></a>' : null) . '<span></span>' . '</div>';
                }
                $output .= '<div style="float: left; width: 900px;"><div class="n4 cl">';
                $output .= '<input type="hidden" name="data[attachments_args][order]['.$file['id'].']" value="1">';
                $output .= $this->Form->input("attachments_args.title.{$file['id']}", array('label' => ___('Title'), 'value' => $file['title'], 'type' => 'text'));
                $output .= $this->Form->input("attachments_args.source.{$file['id']}", array('label' => ___('Source'), 'value' => $file['source'], 'type' => 'text'));
                $output .= $this->Form->input("attachments_args.locale.{$file['id']}", array('label' => ___('Language'), 'empty' => ___('All'), 'selected' => $file['locale'], 'options' => Configure::read('CMS.activelanguages')));
                $output .= $this->Form->input("attachments_args.type.{$file['id']}", array('label' => ___('Type'), 'value' => $file['type'], 'type' => 'text', 'style' => 'width: 80px;'));
                $output .= '</div></div>';
                $output .= '<div style="clear: both;"></div>';
                $output .= '</div>';
                $output_remove .= $this->Form->input('attachments_remove.' . $file['id'], array('type' => 'hidden', 'value' => '0'));
            }
        }
        $output .= '</div>';
        $output .= '<div class="input file">';
        $output .= $output_remove;
        $output .= '<label>' . $options['label'] . '</label>';
        $output .= '<div>';
        $output .= $this->Form->input($fieldName . '.', array_merge($options, array('type' => 'file', 'label' => false, 'div' => false, 'multiple' => true)));
        $output .= '<a style="margin-bottom: -4px; margin-left: 5px;" href="/admin/system/url_video" class="ajx_win ico ico_video"></a>';
        $output .= '<a style="margin-bottom: -4px; margin-left: 5px;" href="/admin/system/url_image" class="ajx_win ico ico_link_img"></a>';
        $output .= '</div>';
        $output .= '</div>';
        //$output .= '<a onclick="$(\'#'.$this->Form->_modelScope.'Attachments\').parent().parent().append($(\'#'.$this->Form->_modelScope.'Attachments\').parent().clone().show());" style="margin: 15px 10px;" class="button icon add primary">'.___('add more').'</a>';
        $output .= $this->_View->element('/admin/attachment', array('id' => $this->Form->_modelScope.'Attachments'));
        return $output;
    }

    public function __imagetypeone($fieldName, $options = array()){
        $output .= '<div class="input file">';
        $output .= '<label>' . $options['label'] . '</label>';
        $output .= '<div>';
        $output .= $this->Form->input('attachonetype.'.$fieldName, array_merge($options, array('type' => 'file', 'label' => false, 'div' => false)));
        if(!empty($this->data['ObjOptAttachType'][$fieldName])){
            $file = $this->data['ObjOptAttachType'][$fieldName];
            $output .= $this->Form->input('attachments_remove.' . $file['id'], array('type' => 'hidden', 'value' => '0'));
            $output .= '<a href="/'.$file['location'].'/large/'.$file['file'].'" target="_blank" class="ico img_preview" preview="/getimages/100x100x2/thumb/'.$file['attach'].'"><img style="margin-bottom: -4px; margin-left: 5px;" height="16px" src="/getimages/16x16x1/thumb/'.$file['attach'].'" /></a>';
            $output .= '<a style="margin-bottom: -4px; margin-left: 10px;" href="javascript:void(0);" class="ico ico_del" onclick="$(\'#attachments_remove'.$file['id'].'\').val(\'1\');$(this).prev().remove();$(this).remove();"></a>';
        }
        $output .= '</div>';
        $output .= '</div>';        
        return $output;
    }

    public function __image2path($fieldName, $options = array()){
        $output .= '<div class="input file">';
        $output .= '<label>' . $options['label'] . '</label>';
        $output .= '<div>';
        $output .= $this->Form->input('image2path.'.$fieldName, array_merge($options, array('type' => 'file', 'label' => false, 'div' => false, 'image2path' => 'data['.$this->Form->_modelScope.']['.$fieldName.']')));
        if(!empty($this->data[$this->Form->_modelScope][$fieldName])){
            $output .= '<a href="/'.$this->data[$this->Form->_modelScope][$fieldName].'" target="_blank" class="ico img_preview" preview="/'.$this->data[$this->Form->_modelScope][$fieldName].'"><img style="margin-bottom: -4px; margin-left: 5px;" height="16px" width="16px" src="/'.$this->data[$this->Form->_modelScope][$fieldName].'" /></a>';
            $output .= '<a style="margin-bottom: -4px; margin-left: 10px;" href="javascript:void(0);" class="ico ico_del" onclick="$(\'[name=&quot;'.'data['.$this->Form->_modelScope.']['.$fieldName.']'.'&quot;]\').val(\'\');$(this).prev().remove();$(this).remove();"></a>';
        }
        $output .= '<input type="hidden" name="'.'data['.$this->Form->_modelScope.']['.$fieldName.']'.'" value="'.$this->data[$this->Form->_modelScope][$fieldName].'" />';
        $output .= '</div>';
        $output .= '</div>';        
        return $output;
    }

    public function __checkbox($fieldName, $options = array()){
        $output = null;
        $output .= '<div class="input checkbox">';
        $output .= "<label>{$options['label']}</label>";
        foreach($options['options'] as $key => $val){
            $output .= '<div class="input_checkbox">' . $this->Form->checkbox("{$fieldName}.{$key}", array('hiddenField' => false)) . " {$val}" . '</div>';
        }
        $output .= '</div>';
        return $output;
    }
    
    public function __input($fieldName, $options = array()){
        $output = null;
        foreach(Configure::read('CMS.activelanguages') as $_lng => $lng){
            if($_lng == Configure::read('Config.def_language')){
                if(count(Configure::read('CMS.activelanguages')) > 1){
                    $output .= $this->Form->input("Translates.{$this->Form->_modelScope}.{$fieldName}.{$_lng}", am($options, array('div' => array('div_lng' => substr($_lng, 0, 2)), 'label' => $options['label'] . " <span class='small'>[".strtoupper(___(substr($_lng, 0, 2)))."]&nbsp;<a href='javascript:void(0)' class='make_uppercase'>&nbsp;^&nbsp;</a></span>&nbsp;<a class='ico ico_translate link_inpt make_translate'></a>")));
                } else {
                    $output .= $this->Form->input($fieldName, am($options, array('label' => $options['label'] . " <span class='small'><a href='javascript:void(0)' class='make_uppercase'>&nbsp;^&nbsp;</a></span>")));
                }
            } else {
                $output .= $this->Form->input("Translates.{$this->Form->_modelScope}.{$fieldName}.{$_lng}", am($options, array('is_translate' => '1', 'div' => array('style' => 'display: none;', 'div_lng' => substr($_lng, 0, 2)), 'label' => $options['label'] . " <span class='small'>[".strtoupper(___(substr($_lng, 0, 2)))."]&nbsp;<a href='javascript:void(0)' class='make_uppercase'>&nbsp;^&nbsp;</a></span>&nbsp;<a class='ico ico_translate link_inpt make_translate'></a>")));
            }
            
        }
        return $output;
    }    
    
    public function map_input($fieldName, $options = array()){
        $output = null;
        $output .= $this->Form->input($fieldName, am($options, array('label' => $options['label'] . "<a class='ico ico_map link_inpt map_coordonates'></a>")));
        return $output;
    }
    
    
	public function adminMenus($menus, $options = array(), $depth = 0) {
		$options = Hash::merge(array(
			'children' => true,
			'htmlAttributes' => array(),
		), $options);

		$out = null;
		$sorted = Hash::sort($menus, '{s}.weight', 'ASC');

		foreach ($sorted as $menu) {
			$htmlAttributes = $options['htmlAttributes'];

			if (empty($menu['htmlAttributes']['class'])) {
				$menuClass = Inflector::slug(strtolower('menu-' . $menu['title']), '-');
				$menu['htmlAttributes'] = Hash::merge(array(
					'class' => $menuClass
				), $menu['htmlAttributes']);
			}
			$title = '';
			if (empty($menu['icon'])) {
				$menu['htmlAttributes'] += array('icon' => 'white');
			} else {
				$menu['htmlAttributes'] += array('icon' => $menu['icon']);
			}
			$title .= $menu['title'];
			$children = '';
			if (!empty($menu['children'])) {
				$childClass = 'sub-nav ';
				$childClass .= ' submenu-' . Inflector::slug(strtolower($menu['title']), '-');
				if ($depth > 0) {
					$childClass .= ' dropdown-menu';
				}
				$children = $this->adminMenus($menu['children'], array(
					'children' => true,
					'htmlAttributes' => array('class' => $childClass),
				), $depth + 1);
                
                if(strpos($children, 'current')!== false){
                    $menu['htmlAttributes']['class'] .= ' current';
                }
                
				$menu['htmlAttributes']['class'] .= ' hasChild dropdown-close';
			}
			$menu['htmlAttributes']['class'] .= ' sidebar-item';

			if ($this->url($menu['url']) == $this->request->here || $this->url($menu['url']) == $this->Session->read('Nav.active_top_menu')) {
				if (isset($menu['htmlAttributes']['class'])) {
					$menu['htmlAttributes']['class'] .= ' current';
				} else {
					$menu['htmlAttributes']['class'] = 'current';
				}
                
                if(!empty($menu['buttons'])){
                    $_out = null;
                    foreach($menu['buttons'] as $button){
                        $button['htmlAttributes'] = array('class' => $button['class'] . ' button primary');
                        if($this->url($button['url']) == $this->request->here || $this->url($button['url']) == $this->Session->read('Nav.active_top_button') || ($this->url($button['url']) == $this->Session->read('Nav.active_top_menu') && !$this->Session->check('Nav.active_top_button'))) $button['htmlAttributes']['class'] .= ' active';
                        if(is_array($button['url'])) $button['url'] = am($button['url'], array('?' => array('tb' => '1')));
                        $_out  .= $this->Html->link($button['title'], $button['url'], $button['htmlAttributes']);
                    }
                    Configure::write('top_buttons', $_out);
                }
                
			}
            if(is_array($menu['url'])) $menu['url'] = am($menu['url'], array('?' => array('tm' => '1')));
			$link = $this->Html->link($title, $menu['url'], $menu['htmlAttributes']);
			$liOptions = array();
			if (!empty($children) && $depth > 0) {
				$liOptions['class'] = ' dropdown-submenu';
			}
			$out  .= $this->Html->tag('li', $link . $children, $liOptions);
		}
		return $this->Html->tag('ul', $out, $htmlAttributes);
	}

    
    public function top_buttons_add(){
        if($top_buttons_add = Configure::read('top_buttons_add')){
            $_out = null;
            foreach($top_buttons_add as $button){
                $button['htmlAttributes'] = array('class' => $button['class'] . ' button pill primary');
                if($this->url($button['url'], false, false) == $this->request->here) $button['htmlAttributes']['class'] .= ' active';
                if(is_array($button['url'])) $button['url'] = am($button['url'], array('?' => array('tb' => '1')));
                $_out  .= $this->Html->link($button['title'], $button['url'], $button['htmlAttributes']);
            }
            return $_out;
        }
    }
    public function top_buttons_replace(){
        if($top_buttons_add = Configure::read('top_buttons_replace')){
            $_out = null;
            foreach($top_buttons_add as $button){
                $button['htmlAttributes'] = array('class' => $button['class'] . ' button primary');
                if($this->url($button['url'], false, false) == $this->request->here) $button['htmlAttributes']['class'] .= ' active';
                if(is_array($button['url'])) $button['url'] = am($button['url'], array('?' => array('tb' => '1')));
                $_out  .= $this->Html->link($button['title'], $button['url'], $button['htmlAttributes']);
            }
            return $_out;
        }
    }
}
