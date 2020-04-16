<?php
class ModelRoute extends CakeRoute
{
    public function parse($url)
    {
        $url_parts = explode('/', $url);
        if($url_parts[1] == 'admin') return false;
        
        if(strlen($url_parts[1]) == 3){
            $lang = $url_parts[1];
            
            if(count($url_parts) > 3){
                $url = $url_parts[3];
                $url_base = $url_parts[2];
            } else {
                $url = $url_parts[2];
            }
            
        } else {
            if(count($url_parts) > 2){
                $url = $url_parts[2];
                $url_base = $url_parts[1];
            } else {
                $url = $url_parts[1];
            }
        }

        
        $found_alias = ClassRegistry::init('CmsAlias')->find('first', array('conditions' => array('CmsAlias.alias' => trim($url, '/'))));
        
                
        if(!empty($found_alias)){
            $found_alias = $found_alias['CmsAlias'];
            $aliases = ClassRegistry::init('CmsAlias')->find('list', array('fields' => array('CmsAlias.locale', 'CmsAlias.alias'), 'conditions' => array('CmsAlias.tid' => $found_alias['tid'], 'CmsAlias.model' => $found_alias['model'], 'CmsAlias.foreign_key' => $found_alias['foreign_key'])));
            if(!empty($url_base)){
                $found_alias_base = ClassRegistry::init('CmsAlias')->find('first', array('conditions' => array('CmsAlias.alias' => trim($url_base, '/'))));
                if(!empty($found_alias_base)){
                    $found_alias_base = $found_alias_base['CmsAlias'];
                    $aliases_base = ClassRegistry::init('CmsAlias')->find('list', array('fields' => array('CmsAlias.locale', 'CmsAlias.alias'), 'conditions' => array('CmsAlias.tid' => $found_alias_base['tid'], 'CmsAlias.model' => $found_alias_base['model'], 'CmsAlias.foreign_key' => $found_alias_base['foreign_key'])));
                    Configure::write('CMS.path_locales_base', $aliases_base);
                }
            }
            
            $path_alias = Configure::read('CMS.path_alias');
            if(!empty($path_alias[$found_alias['tid']][$found_alias['model']])){
                Configure::write('CMS.path_locales', $aliases);
                Configure::write('CMS.path_here', Router::url(am($path_alias[$found_alias['tid']][$found_alias['model']], array($found_alias['foreign_key']))));
                return am($path_alias[$found_alias['tid']][$found_alias['model']], array('lang' => $lang, 'pass' => array($found_alias['foreign_key']), 'query' => array('page' => $page)));
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}