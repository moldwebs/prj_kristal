<?php
    function utf8_fopen_read($fileName) {
        if($data = file_get_contents($fileName)){
            $fc = iconv('CP1251', 'UTF-8', $data); 
            $handle = fopen("php://memory", "rw"); 
            fwrite($handle, $fc); 
            fseek($handle, 0); 
            return $handle; 
        } else {
            return false;
        }
    } 

    function query2array($data, $col, $key){
        $return = array();
        foreach($data as $row){
            $return[$row[$col][$key]] = $row[$col];
        }
        return $return;
    }
    
    function pra($data){
        foreach($data as $key => $val){
            if($key == 'cms_blocks') echo $val;
            if(is_array($val) || is_object($val)){
                pra($val);
            } else {
                //if(substr_count($val, 'eeeeeeefffffffffff') > 0) echo "<br>{$key}=>{$val}";
            }
        }
    }

    function ws_number($data){
        if(strpos($data, ',') !== false && strpos($data, '.') !== false){
            $data = str_replace(array(','), array(''), $data); 
        } else if(strpos($data, ',') !== false){
            $data = str_replace(array('.', ','), array('', '.'), $data); 
        }
        $data = str_replace(array(' '), array(''), $data);
        return round(trim($data), 2);
    }
    
    function ws_tree2form($data){
        
    }

    function ws_tree2list($data){
        $return = array();
        foreach($data as $key => $val){
            if(is_array($val)){
                if(substr($key, 0, strlen('conc_')) == 'conc_'){
                    $key = substr($key, strlen('conc_'));
                    $return[] = array('key' => $key, 'val' => implode('.', $val));
                } else {
                    $return[] = $key;
                    foreach($val as $_key => $_val){
                        if(is_array($_val)){
                            if(substr($_key, 0, strlen('conc_')) == 'conc_'){
                                $_key = substr($_key, strlen('conc_'));
                                $return[] = array('key' => $_key, 'val' => implode('.', $_val));
                            } else {
                                $return[] = $_key;
                                foreach($_val as $__key => $__val){
                                    $return[] = array('key' => $__key, 'val' => $__val);
                                }
                            }
                        } else {
                            $return[] = array('key' => $_key, 'val' => $_val);
                        }
                    }
                }
            } else {
                $return[] = array('key' => $key, 'val' => $val);
            }
        }
        return $return;
    }

    function ws_price_format($data, $currency, $value_dec = null){
        if(!empty($data)){
            
            $price_dec_max = Configure::read('CMS.settings.catalog.price_dec_max');
            if($price_dec_max > 0 && $data['value'] <= $price_dec_max){
                $value_dec = 2;
            }
            
            if($value_dec > 0){
                $value_dec_trim = '';
            } else {
                $value_dec_trim = '.00';
            }

            $data['value'] = str_replace($value_dec_trim, '', number_format($data['value'], $value_dec, '.', ''));
            $data['old'] = str_replace($value_dec_trim, '', number_format($data['old'], $value_dec, '.', ''));

            $data['html_value'] = str_replace($value_dec_trim, '', number_format($data['value'], $value_dec, '.', ' '));
            $data['html_currency'] = $currency;
            $data['html_old'] = str_replace($value_dec_trim, '', number_format($data['old'], $value_dec, '.', ' '));
        }
        /*                
        $data['html_value'] = str_replace('.00', '', number_format($data['value'], 2, '.', ' '));
        $data['html_currency'] = $currency;
        $data['html_old'] = str_replace('.00', '', number_format($data['old'], 2, '.', ' '));
        */

        return $data;
    }

    function ws_farray($start, $end){
        $return = array();
        if($start < $end){
            for($i=$start;$i<=$end;$i++){
                $return[$i] = $i;
            }
        } else {
            for($i=$start;$i>=$end;$i--){
                $return[$i] = $i;
            }
        }
        return $return;
    }

    function ws_array2opt($options, $selected = null){
        $output = null;
        foreach($options as $key => $val){
            if(is_array($val)){
                $output .= "<optgroup label=\"{$key}\"> \r\n";
                foreach($val as $_key => $_val){
                    $output .= "<option value=\"{$_key}\" ".((string)$selected === (string)$_key ? ' selected="selected"' : null).">$_val</option> \r\n";
                }
                $output .= "</optgroup> \r\n";
            } else {
                $output .= "<option value=\"{$key}\" ".((string)$selected === (string)$key ? ' selected="selected"' : null).">$val</option> \r\n";
            }
            
        }
        return $output;
    }

    function price($data){
        return $data . ' ' . Configure::read('Obj.currencies')[key(Configure::read('CMS.currency'))]['title'];
    }
    
    function cprice($data, $currency = true){
        $data = number_format((($data * Configure::read('Obj.currencies_vals')[key(Configure::read('CMS.currency'))])/Configure::read('Obj.currency')['value']), Configure::read('CMS.currency_decimals'), '.', '');
        return $data . ($currency ? ' ' . Configure::read('Obj.currency')['title'] : null);
    }

    function dprice($data){
        $data = number_format((($data * Configure::read('Obj.currency')['value'])/Configure::read('Obj.currencies_vals')[key(Configure::read('CMS.currency'))]), Configure::read('CMS.currency_decimals'), '.', '');
        return $data;
    }

    function get_array_val($path, $array){
        if (!empty($path)) {
            foreach (explode('.', $path) as $key) {
                if (isset($array[$key])) {
                    $array = $array[$key];
                } else {
                    return null;
                }
            }
        }
        return $array;
    }
    
    function get_url($field){
        $url = $_SERVER['REQUEST_URI'];
        $url = preg_replace("/(&|\?|){$field}=[^&]*(&|)/", '', $url);
        $url = $url . (strpos($url, '?') !== false ? '&' : '?') . "{$field}=";
        return $url;
    }
    
    function url_base64_encode($data){
        return urlencode(base64_encode($data));
    }
    
    function ws_js_error($error){
        $error = addslashes($error);
        exit("mxalert('{$error}');");
    }
    
    function ws_nr($input, $count){
        return str_pad($input, $count, "0", STR_PAD_LEFT);
    }
    
    function sqlimplode($sep = ',', $data, $tags = false){
        return implode($sep, sqls($data, $tags));
    }
    
    function daterange($date, $long, $style = null){
        if(empty($style)) $style = 'd M. Y';
        if(empty($long)){
            return ___date($style, strtotime($date));
        } else {
            return ___date($style, strtotime($date)) . " - " . ___date($style, strtotime($date) + ($long * 86400));
            //return date("d", strtotime($date)) . (date("m", strtotime($date)) != date("m", strtotime($date) + ($long * 86400)) ? date(" M.", strtotime($date)) : null) . (date("Y", strtotime($date)) != date("Y", strtotime($date) + ($long * 86400)) ? date(" Y", strtotime($date)) : null) . '-' . date("d M. Y", strtotime($date) + ($long * 86400));
        }
    }
    
    function datelast($date){
        if(strtotime($date) > strtotime(date("Y-m-d"))){
            $hours = (time() - strtotime($date))/60;
            
            if($hours >= 120){
                echo floor($hours/60) . ' ' . ___('hours ago');
            } else if($hours >= 60){
                echo ___('one hour ago');
            } else {
                echo floor($hours) . ' ' . ___('minuts ago');;
            }
        } else {
            echo date("d.m.Y", strtotime($date));
        }
        //echo '3 ore in urma';
    }

    function array_insert (&$array, $position, $insert_array) {
        $first_array = array_splice ($array, 0, $position); 
        $array = array_merge ($first_array, $insert_array, $array); 
    } 
    
    
    function cms_uid($alias = null, $tid = null){
        if($tid == 'force' && CMS_UID_REL > 0){
            return CMS_UID_REL;
        }
        if(CMS_UID_REL > 0){
            $uid_include = Configure::read('CMS.uid_include');
            if(!empty($uid_include) && in_array($tid . '__' . $alias, $uid_include)){
                return CMS_UID_REL;
            }
            $uid_exclude = Configure::read('CMS.uid_exclude');
            if(!empty($uid_exclude) && !in_array($tid . '__' . $alias, $uid_exclude)){
                return CMS_UID_REL;
            }
        }
        return CMS_UID;
    }
    
    function get_uid(Model $model, $type = null, $tid = null){
        if(CMS_UID_REL > 0){
            if($tid == 'cms_slider' && $model->alias == 'ObjItemTree') return CMS_UID_REL;
        }
        if(CMS_UID > 0){
            //if($model->alias == 'ObjOptField') pr($model->schema());
            if($model->alias == 'Session' && CMS_UID_ADMIN == '1') return '0';
            if($type != 'beforeFind'){
                return CMS_UID;
            }
            if($permits = Configure::read('CMS.allow_uid')){
                if($type == 'beforeFind' && (in_array($model->alias, array('CmsTranslate', 'CmsAlias')) || array_key_exists('foreign_key', $model->schema()))) return array('0', CMS_UID);
                if(in_array($model->alias, $permits) || in_array($model->alias . '_' . $tid, $permits)) return CMS_UID; else return '0';
            }
            if($permits = Configure::read('CMS.denny_uid')){
                if($type == 'beforeFind'  && (in_array($model->alias, array('CmsTranslate', 'CmsAlias')) || array_key_exists('foreign_key', $model->schema()))) return array('0', CMS_UID);
                if(in_array($model->alias, $permits) || in_array($model->alias . '_' . $tid, $permits)) return '0'; else return CMS_UID;
            }
        }
        return CMS_UID;
    }

    function ev($data, $cfg = array()){
        if(preg_match_all('/^(.*?)vimeo\.com\/(.*?)$/', $data, $match)){
            e("//player.vimeo.com/video/{$match[2][0]}?title=0&amp;byline=0&amp;portrait=0&amp;color=c9ff23&amp;autoplay={$cfg['autoplay']}");
        } else e($data);
    }

    function ws_htmlspecialchars($data){
        if(!is_array($data)) return htmlspecialchars($data);
        $return_data = array();
        foreach($data as $key => $val){
            if(is_array($val)){
                $return_data[$key] = ws_htmlspecialchars($val);
            } else {
                $return_data[$key] = htmlspecialchars($val);
                //$return_data[$key] = preg_replace('/[^a-zA-Z0-9\p{Cyrillic}0-9\s\-\_\,\.\:\/]/u', '', $val);
                //$return_data[$key] = preg_replace('/(<link[^>]+rel="[^"]*stylesheet"[^>]*>|<img[^>]*>|style="[^"]*")|<script[^>]*>.*?<\/script>|<style[^>]*>.*?<\/style>|<!--.*?-->/is', '', $val);
                //$return_data[$key] = strip_tags($return_data[$key]);
            }
        }
        return $return_data;
    }

    function old_pr($data){
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }
	
	function _pr($data)
	{
		// capture the output of print_r
		$out = print_r($data, true);

		$out = preg_replace_callback('/([ \t]*)(\[[^\]]+\][ \t]*\=\>[ \t]*[a-z0-9 \t_]+)\n[ \t]*\(/iU', "callbackFunction", $out);

		// replace ')' on its own on a new line (surrounded by whitespace is ok) with '</div>
		$out = preg_replace('/^\\s*\\)\\s*$/m', '</div>', $out);

		// print the javascript function toggleDisplay() and then the transformed output
		echo '<script language="Javascript">function toggleDisplay(id) { document.getElementById(id).style.display = (document.getElementById(id).style.display == "block") ? "none" : "block"; }</script>'."<pre>$out</pre>";
	}	

	function callbackFunction($matches) {
		$id = substr(md5(rand().$matches[0]), 0, 7);
		return "$matches[1]<a href=\"javascript:toggleDisplay('$id');\">$matches[2]</a><div id='$id' style=\"display: none;\">'";
	}

    function _prd($data){
        _pr('<hr>');
        _pr('BEGIN');
        _pr($data);
        _pr('<hr>');
    }

    function e_mail($data){
        if(preg_match_all('/src=("[^"]*")/i', $data, $srcs)){
            foreach($srcs[0] as $src){
                if(substr($src, 5, 4) != 'http'){
                    $_src = str_replace('src="', 'src="' . FULL_BASE_URL . '/', $src);
                    $data = str_replace($src, $_src, $data);
                }
            }
        }
        echo $data;
    }

    function e($data){
        echo $data;
    }
    
    function et($text, $length = 100, $options = array()){
        e(String::truncate($text, $length, $options));
    }

    function eth($text, $length = 1000, $options = array()){
        $text = strip_tags($text);
        e(String::truncate($text, $length, $options));
    }
        
    function eurl($url){
        e(ws_url($url));
    }
    
    function aurl($obj, $alias){
        $path = Configure::read("CMS.path_alias.{$obj['tid']}.{$alias}");
        return am($path, array('admin' => false), $obj['id']);
    }

    function ___e($data, $args = null){
        if(!empty($_GET['debuglocale']) || strpos($_SERVER['HTTP_REFERER'], 'debuglocale=1') !== false){
            echo "[{$data}]";
        } else {
            echo ___($data, $args);
        }
    }
    
    function ___($data, $args = null){
        //global $dbi;
        
        $return = $data;
        
        //if(!$dbi) $dbi = ConnectionManager::getDataSource('default');
        
        if(!empty($args) && !is_array($args)) $args = array_slice(func_get_args(), 1);
        
        $tpl_lang = Configure::read('Config.tpl_language');
        
        if(empty($tpl_lang)){
            if(Configure::read('is_admin') == '1'){
                $_return = __($data, $args);
                if($_return != $return) return $_return;
            }
            $_lang = Configure::read('Config.language');
        } else {
            $_lang = $tpl_lang;
            $bef_lang = Configure::read('Config.language');
            Configure::write('Config.language', $tpl_lang);
        }
        
        $translates = Configure::read('CMS.translates');
        if(!empty($translates[$_lang][$data])){
            $return = (!empty($args) ? vsprintf($translates[$_lang][$data], $args) : $translates[$_lang][$data]);
        } else if(!empty($translates[$_lang][$data.' '])){
            $return = (!empty($args) ? vsprintf($translates[$_lang][$data.' '], $args) : $translates[$_lang][$data.' ']);
        } else {
            /*
            $_lngs = Configure::read('CMS.activelanguages');
            if(!isset($translates[$_lang][$data]) && !empty($_lngs) && Configure::read('is_admin') != '1'){
            //if(!empty($_lngs)){
                foreach($_lngs as $_lng => $__lng){
                    if(!isset($translates[$_lng][$data])){
                        $trnslt = I18n::translate($data, null, null, 6, null, $_lng);
                        $dbi->query("INSERT INTO `wb_cms_translate`(`uid`, `locale`, `key`, `value`) VALUES('".CMS_UID."', '{$_lng}', '".mysql_escape_string($data)."', '".($data != $trnslt ? mysql_escape_string($trnslt) : null)."')");
                        Cache::clearGroup('query');
                        $translates[$_lng][$data] = $trnslt;
                    }
                }
                Configure::write('CMS.translates', $translates);
            }
            */
            $return = __($data, $args);
        }
        if(!empty($bef_lang)) Configure::write('Config.language', $bef_lang);
        return $return;
    }

    function ws_usort($data, $ordering){
        //$items = ws_usort($items, array('s_code' => 'asc', 's_color' => 'asc'));
        uasort($data, function($rowA, $rowB) use ($ordering) {
            foreach($ordering as $key=>$sortDirection){
                switch($sortDirection){
                    case 'desc':
                        $direction=-1;
                        break;
                    case 'asc':
                    default:
                        $direction=1;
                        break;
                }
                if ($rowA[$key] > $rowB[$key]) {
                    return $direction;
                } else if ($rowA[$key] < $rowB[$key]){
                    return $direction*-1;
                }
            }
            return 0;
        });
        return $data;
    }

    function ___date($data, $unix){
        $params = array(
            'd' => '%d',
            'D' => '%a',
            'l' => '%A',
            'j' => '%e',
            'N' => '%u',
            'w' => '%w',
            'W' => '%W',
            'M' => '%b',
            'F' => '%B',
            'm' => '%m',
            'o' => '%G',
            'y' => '%y',
            'Y' => '%Y',
            'H' => '%H',
            'h' => '%I',
            'g' => '%l',
            'a' => '%P',
            'A' => '%p',
            'i' => '%M',
            's' => '%S',
            'U' => '%s',
            'O' => '%z',
            'T' => '%Z',
        );

        $_data = str_split($data);
        $data = null;
        foreach($_data as $char){
            if(array_key_exists($char, $params)){
                $data .= $params[$char];
            } else {
                $data .= $char;
            }
        }
        
        setlocale(LC_TIME, strtolower(substr(Configure::read('Config.language'), 0, 2)) . '_' . strtoupper(substr(Configure::read('Config.language'), 0, 2)) . '.UTF-8');
        $date = strftime($data, $unix);
        //$date = utf8_encode($date);
        
        
        return $date;
    }
    
    function ___date_old($data, $unix){
        $days = array('Monday' => ___('Monday'), 'Tuesday' => ___('Tuesday'), 'Wednesday' => ___('Wednesday'), 'Thursday' => ___('Thursday'), 'Friday' => ___('Friday'), 'Saturday' => ___('Saturday'), 'Sunday' => ___('Sunday'));
        $months = array('January' => ___('January'), 'February' => ___('February'), 'March' => ___('March'), 'April' => ___('April'), 'May' => ___('May'), 'June' => ___('June'), 'July' => ___('July'), 'August' => ___('August'), 'September' => ___('September'), 'October' => ___('October'), 'November' => ___('November'), 'December' => ___('December'));
        $months_short = array('Jan' => ___('Jan'), 'Feb' => ___('Feb'), 'Mar' => ___('Mar'), 'Apr' => ___('Apr'), 'May' => ___('May'), 'Jun' => ___('Jun'), 'Jul' => ___('Jul'), 'Aug' => ___('Aug'), 'Sep' => ___('Sep'), 'Oct' => ___('Oct'), 'Nov' => ___('Nov'), 'Dec' => ___('Dec'));
        
        $date = date($data, $unix);
        
        $date = str_replace(array_keys($days), array_values($days), $date);
        $date = str_replace(array_keys($months), array_values($months), $date);
        $date = str_replace(array_keys($months_short), array_values($months_short), $date);
        
        return $date;
    }

    function sqls($data, $tags = false){
        if(is_array($data)){
            foreach($data as $key => $val){
                $data[$key] = sqls($val, $tags = false);
                //$data[$key] = ($tags ? "'" : null).mysql_escape_string($val).($tags ? "'" : null);
            }
            return $data;
        } else {
            return ($tags ? "'" : null).mysql_escape_string($data).($tags ? "'" : null);
        }
    }
    
    function date_stl_1($date){
        if(strtotime($date) > 0){
            return "<span title='".date("H:i:s", strtotime($date))."'>".date("d.m.Y", strtotime($date))."</span>";
        } else return '';
    }
    
    function ifstrstr($haystack, $needle, $before_needle = false){
        if(strpos($haystack, $needle) !== false){
            return strstr($haystack, $needle, $before_needle);
        } else{
            return $haystack;
        }
    }
    
    function config_add($key, $val){
        $data = Configure::read($key);
        if(!is_array($data)) $data = array();
        $data = $data + $val;
        
        if(substr($key, 0, 8) == 'CMS.mod_'){
            $sorted = array();
            foreach($data as $_key => $_val){
                if($_key == 'mod_base'){
                    $sorted[1][$_key] = $_val;
                } else if(substr($_key, 0, 4) == 'mod_'){
                    $sorted[2][$_key] = $_val;
                } else {
                    $sorted[0][$_key] = $_val;
                }
            }
            $data_sorted = array();
            for($i = 0; $i <= 2; $i++){
                if(!empty($sorted[$i])) foreach($sorted[$i] as $_key => $_val){
                    $data_sorted[$_key] = $_val;
                }
            }
            Configure::write($key, $data_sorted);
        } else {
            Configure::write($key, $data);
        }
    }
    
    function ws_trim($data){
        return preg_replace("/(^\s+)|(\s+$)/us", "", trim(html_entity_decode($data)));
    }
    
    function ws_clean($data){
        $data = str_replace('&nbsp;', ' ', $data);
        $data = preg_replace('/(<\/[^>]+?>)(<[^>\/][^>]*?>)/', '$1 $2', $data);
        $data = strip_tags($data);
        $data = trim(preg_replace('/\s\s+/', ' ', $data));
        return $data;
    }
    
    function ws_qlist($data){
        $return = array();
        foreach($data as $key => $val){
            $return[$val[key($val)]['id']] = $val[key($val)]['title'];
        }
        return $return;
    }
    
    function ws_expl($sep = null, $data = null, $key = false){
        $expl = explode($sep, $data);
        return ($key !== false ? $expl[$key] : $expl);
    }
    
    function ws_explode_vals($sep = null, $data = null){
        $return = array();
        if(!empty($data)) foreach(explode($sep, $data) as $val){
            $return[$val] = $val;
        }
        return $return;
    }
    
    function ws_get_range($min = null, $max = null, $step = null, $type = null){
        $return = array();
        
        if($type == 'year' && empty($max)) $max = date("Y");
        if(empty($min)) $min = 0;
        if(empty($step)) $step = 1;
        while($min <= $max){
            $return[$min] = $min;
            $min = $min + $step;
        }
        return $return;
    }
    
    function ws_ext($file){
        $path_parts = pathinfo($file);
        return preg_replace("/[^A-Za-z0-9]/", '', strtolower($path_parts['extension']));
    }
    
    function ws_name($file){
        $path_parts = pathinfo($file);
        return $path_parts['filename'];
    }
    
    function ws_ext_img(){
        return array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff');
    }

    function ws_vh(){
        return array('1' => ___('Visible', true), '0' => ___('Hidden', true));
    }
    
    function ws_hv(){
        return array('0' => ___('Hidden', true), '1' => ___('Visible', true));
    }
    
    function ws_ny(){
        return array('0' => ___('No', true), '1' => ___('Yes', true));
    }

    function ws_yn(){
        return array('1' => ___('Yes', true), '0' => ___('No', true));
    }
    
    function nl2array($data){
        $return = array();
        if(!empty($data)) foreach(preg_split('/[\r\n]+/', $data) as $line){
            if(strpos($line, '=') !== false){
                list($key, $val) = explode('=', $line);
                $return[$key] = $val;
            } else {
                $return[] = $line;
            }
        }
        return $return;
    }

    function br2nl($buff = '') {
        $buff = preg_replace('#<br[/\s]*>#si', "\n", $buff);
        $buff = trim($buff);

        return $buff;
    }
    
	function ws_e_code($data = null){
		if($data != '' || $data > 0) echo "<a class='no_tipsy' title='".___('Click for copy', true)."' href='javascript:void(0)' style='color: #666666' onclick='prompt(\"Code\", \"{$data}\")'>[{$data}]</a>";
	}

    function ws_url($url, $alias = null){
        $active_language = Configure::read('Config.language');
        $default_language = Configure::read('Config.def_language');
        $req_language = Configure::read('Config.req_language');
        $active_language_link = (count(Configure::read('CMS.activelanguages')) > 1 ? "/{$active_language}" : null);
        
        $active_language_link_prepend = (strpos($_SERVER['REQUEST_URI'], $active_language_link) !== false ? $active_language_link . '/' : (!empty($req_language) ? '/' . $req_language . '/' : '/'));
        
        if(preg_match_all('/\{(.*)\}/', $url, $matches)){
            $data = explode('.', $matches[1][0]);
            $aliases = Configure::read('CMS.aliases');
            if(!empty($aliases[$data[0]][$data[1]][$data[2]][$active_language])){
                $return = $active_language_link_prepend . ltrim($aliases[$data[0]][$data[1]][$data[2]][$active_language], '/') . str_replace($matches[0][0], '', strstr($url, $matches[0][0]));
            } else if(!empty($aliases[$data[0]][$data[1]][$data[2]][$default_language])){
                $return = $active_language_link_prepend . ltrim($aliases[$data[0]][$data[1]][$data[2]][$default_language], '/') . str_replace($matches[0][0], '', strstr($url, $matches[0][0]));
            } else if(!empty($aliases[$data[0]][$data[1]][$data[2]])){
                $return = $active_language_link_prepend . ltrim(reset($aliases[$data[0]][$data[1]][$data[2]]), '/') . str_replace($matches[0][0], '', strstr($url, $matches[0][0]));
            } else {
                $return = $active_language_link_prepend . ltrim(str_replace($matches[0][0], '', $url), '/');
            }
        } else {
            if($url != '/'){
                if(substr($url, 0, 1) == '#' || substr($url, 0, 4) == 'http'){
                    return $url;
                } else {
                    $return = $active_language_link_prepend . ltrim($url, '/');
                }
            } else {
                $return = $active_language_link_prepend;
            }
        }
        if($return != '/') $return = rtrim($return, '/');
        return str_replace('//', '/', $return);
    }

    function ws_bb2html($bbtext){
      $bbtags = array(
        '[heading1]' => '<h1>','[/heading1]' => '</h1>',
        '[heading2]' => '<h2>','[/heading2]' => '</h2>',
        '[heading3]' => '<h3>','[/heading3]' => '</h3>',
        '[h1]' => '<h1>','[/h1]' => '</h1>',
        '[h2]' => '<h2>','[/h2]' => '</h2>',
        '[h3]' => '<h3>','[/h3]' => '</h3>',
    
        '[paragraph]' => '<p>','[/paragraph]' => '</p>',
        '[para]' => '<p>','[/para]' => '</p>',
        '[p]' => '<p>','[/p]' => '</p>',
        '[left]' => '<p style="text-align:left;">','[/left]' => '</p>',
        '[right]' => '<p style="text-align:right;">','[/right]' => '</p>',
        '[center]' => '<p style="text-align:center;">','[/center]' => '</p>',
        '[justify]' => '<p style="text-align:justify;">','[/justify]' => '</p>',
    
        '[bold]' => '<span style="font-weight:bold;">','[/bold]' => '</span>',
        '[italic]' => '<span style="font-weight:bold;">','[/italic]' => '</span>',
        '[underline]' => '<span style="text-decoration:underline;">','[/underline]' => '</span>',
        '[b]' => '<span style="font-weight:bold;">','[/b]' => '</span>',
        '[i]' => '<span style="font-weight:bold;">','[/i]' => '</span>',
        '[u]' => '<span style="text-decoration:underline;">','[/u]' => '</span>',
        '[break]' => '<br>',
        '[br]' => '<br>',
        '[newline]' => '<br>',
        '[nl]' => '<br>',
        
        '[unordered_list]' => '<ul>','[/unordered_list]' => '</ul>',
        '[list]' => '<ul>','[/list]' => '</ul>',
        '[ul]' => '<ul>','[/ul]' => '</ul>',
    
        '[ordered_list]' => '<ol>','[/ordered_list]' => '</ol>',
        '[ol]' => '<ol>','[/ol]' => '</ol>',
        '[list_item]' => '<li>','[/list_item]' => '</li>',
        '[li]' => '<li>','[/li]' => '</li>',
        
        '[*]' => '<li>','[/*]' => '</li>',
        '[code]' => '<code>','[/code]' => '</code>',
        '[preformatted]' => '<pre>','[/preformatted]' => '</pre>',
        '[pre]' => '<pre>','[/pre]' => '</pre>',	    
      );
    
      $bbtext = str_ireplace(array_keys($bbtags), array_values($bbtags), $bbtext);
    
      $bbextended = array(
        "/\[url](.*?)\[\/url]/i" => "<a href=\"http://$1\" title=\"$1\">$1</a>",
        "/\[url=(.*?)\](.*?)\[\/url\]/i" => "<a href=\"$1\" title=\"$1\">$2</a>",
        "/\[email=(.*?)\](.*?)\[\/email\]/i" => "<a href=\"mailto:$1\">$2</a>",
        "/\[mail=(.*?)\](.*?)\[\/mail\]/i" => "<a href=\"mailto:$1\">$2</a>",
        "/\[img\]([^[]*)\[\/img\]/i" => "<img src=\"$1\" alt=\" \" />",
        "/\[image\]([^[]*)\[\/image\]/i" => "<img src=\"$1\" alt=\" \" />",
        "/\[image_left\]([^[]*)\[\/image_left\]/i" => "<img src=\"$1\" alt=\" \" class=\"img_left\" />",
        "/\[image_right\]([^[]*)\[\/image_right\]/i" => "<img src=\"$1\" alt=\" \" class=\"img_right\" />",
      );
    
      foreach($bbextended as $match=>$replacement){
        $bbtext = preg_replace($match, $replacement, $bbtext);
      }
      return nl2br($bbtext);
    }
    
    function ws_video_code($url){
        $query_string = array();
        parse_str(parse_url($url, PHP_URL_QUERY), $query_string);
        return $query_string["v"];
    }

    function ws_video_file($url = null){
        if(strpos($url, 'youtu') !== false){
            $regexstr = '~
                # Match Youtube link and embed code
                (?:                             # Group to match embed codes
                    (?:<iframe [^>]*src=")?       # If iframe match up to first quote of src
                    |(?:                        # Group to match if older embed
                        (?:<object .*>)?      # Match opening Object tag
                        (?:<param .*</param>)*  # Match all param tags
                        (?:<embed [^>]*src=")?  # Match embed tag to the first quote of src
                    )?                          # End older embed code group
                )?                              # End embed code groups
                (?:                             # Group youtube url
                    https?:\/\/                 # Either http or https
                    (?:[\w]+\.)*                # Optional subdomains
                    (?:                         # Group host alternatives.
                    youtu\.be/                  # Either youtu.be,
                    | youtube\.com              # or youtube.com
                    | youtube-nocookie\.com     # or youtube-nocookie.com
                    )                           # End Host Group
                    (?:\S*[^\w\-\s])?           # Extra stuff up to VIDEO_ID
                    ([\w\-]{11})                # $1: VIDEO_ID is numeric
                    [^\s]*                      # Not a space
                )                               # End group
                "?                              # Match end quote if part of src
                (?:[^>]*>)?                       # Match any extra stuff up to close brace
                (?:                             # Group to match last embed code
                    </iframe>                 # Match the end of the iframe
                    |</embed></object>          # or Match the end of the older embed
                )?                              # End Group of last bit of embed code
                ~ix';
     
            preg_match($regexstr, $url, $matches);
     
            return $matches[1] . '.youtube';
        }
        if(strpos($url, 'vimeo') !== false){
            $regexstr = '~
                # Match Vimeo link and embed code
                (?:<iframe [^>]*src=")?       # If iframe match up to first quote of src
                (?:                         # Group vimeo url
                    https?:\/\/             # Either http or https
                    (?:[\w]+\.)*            # Optional subdomains
                    vimeo\.com              # Match vimeo.com
                    (?:[\/\w]*\/videos?)?   # Optional video sub directory this handles groups links also
                    \/                      # Slash before Id
                    ([0-9]+)                # $1: VIDEO_ID is numeric
                    [^\s]*                  # Not a space
                )                           # End group
                "?                          # Match end quote if part of src
                (?:[^>]*></iframe>)?        # Match the end of the iframe
                (?:<p>.*</p>)?              # Match any title information stuff
                ~ix';
     
            preg_match($regexstr, $url, $matches);
     
            return $matches[1] . '.vimeo';
        }
        return false;
    }

    function ws_video_img($video = null){
        if(ws_ext($video) == 'youtube'){
            return 'http://img.youtube.com/vi/' . str_replace('.youtube', '', $video) . '/sddefault.jpg';
        } else if(ws_ext($video) == 'video'){
            return 'http://img.youtube.com/vi/' . str_replace('.video', '', $video) . '/sddefault.jpg';
        } else if(ws_ext($video) == 'vimeo'){
            return 'http://i.vimeocdn.com/video/' . str_replace('.vimeo', '', $video) . '_640.jpg';
        }
        return false;
    }

    function ws_video_url($video = null){
        if(ws_ext($video) == 'youtube'){
            return 'https://www.youtube.com/watch?v=' . str_replace('.youtube', '', $video);
        } else if(ws_ext($video) == 'video'){
            return 'https://www.youtube.com/watch?v=' . str_replace('.video', '', $video);
        } else if(ws_ext($video) == 'vimeo'){
            return 'https://vimeo.com/' . str_replace('.vimeo', '', $video);
        }
        return false;
    }

    function ws_video_frame($video = null){
        if(ws_ext($video) == 'youtube'){
            return '//youtube.com/embed/' . str_replace('.youtube', '', $video);
        } else if(ws_ext($video) == 'video'){
            return '//youtube.com/embed/' . str_replace('.video', '', $video);
        } else if(ws_ext($video) == 'vimeo'){
            return '//player.vimeo.com/video/' . str_replace('.vimeo', '', $video);
        }
        return false;
    }

    
    function ___array($data){
        if(!empty($data)) foreach($data as $key => $val){
            if(is_array($val)){
                $data[$key] = ___array($val);
            } else {
                $data[$key] = ___($val);
            }
        }
        return $data;
    }
 
    function __ws_alias($text, $strict = true) {
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d.]+~u', '-', $text);

        // trim
        $text = trim($text, '-');
        setlocale(LC_CTYPE, 'en_GB.utf8');
        // transliterate
        if (function_exists('iconv')) {
           $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w.]+~', '', $text);
        if (empty($text)) {
           return 'empty_$';
        }
        if ($strict) {
            $text = str_replace(".", "_", $text);
        }
        $text = trim($text, '-');
        return $text;
    }

    function ws_alias($str, $options = array()) {
    	// Make sure string is in UTF-8 and strip invalid UTF-8 characters
    	$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
    	
    	$defaults = array(
    		'delimiter' => '-',
    		'limit' => null,
    		'lowercase' => true,
    		'replacements' => array(),
    		'transliterate' => true,
    	);
    	
    	// Merge options
    	$options = array_merge($defaults, $options);
    	
    	$char_map = array(
    		// Latin
    		'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 
    		'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 
    		'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 
    		'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 
    		'ß' => 'ss', 
    		'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 
    		'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 
    		'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 
    		'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 
    		'ÿ' => 'y',
    
    		// Latin symbols
    		'©' => '(c)',
    
    		// Greek
    		'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
    		'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
    		'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
    		'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
    		'Ϋ' => 'Y',
    		'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
    		'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
    		'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
    		'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
    		'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
    
    		// Turkish
    		'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
    		'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g', 
    
    		// Russian
    		'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
    		'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
    		'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
    		'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
    		'Я' => 'Ya',
    		'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
    		'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
    		'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
    		'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
    		'я' => 'ya',
    
    		// Ukrainian
    		'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
    		'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
    
    		// Czech
    		'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 
    		'Ž' => 'Z', 
    		'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
    		'ž' => 'z', 
    
    		// Polish
    		'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z', 
    		'Ż' => 'Z', 
    		'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
    		'ż' => 'z',
    
    		// Latvian
    		'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 
    		'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
    		'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
    		'š' => 's', 'ū' => 'u', 'ž' => 'z'
    	);
    	
    	// Make custom replacements
    	$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
    	
    	// Transliterate characters to ASCII
    	if ($options['transliterate']) {
    		$str = str_replace(array_keys($char_map), $char_map, $str);
    	}
    	
    	// Replace non-alphanumeric characters with our delimiter
    	$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
    	
    	// Remove duplicate delimiters
    	$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
    	
    	// Truncate slug to max. characters
    	$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
    	
    	// Remove delimiter from ends
    	$str = trim($str, $options['delimiter']);
    	
        $return = ($options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str);
        
        setlocale(LC_CTYPE, 'en_GB.utf8');
        $return = iconv('utf-8', 'us-ascii//TRANSLIT', $return);
        
    	return $return;
    }

    function amc($first, $second) {
        $result = array();
        foreach($first as $key => $value) {
            $result[$key] = $value;
        }
        foreach($second as $key => $value) {
            $result[$key] = $value;
        }
    
        return $result;
    }

    
    function ws_rmdir($path, $clean = false){
        if (is_dir($path) === true){
            $files = array_diff(scandir($path), array('.', '..'));
            foreach ($files as $file){
                ws_rmdir(realpath($path) . '/' . $file, $clean);
            }
            if($clean){
                return ;
            } else {
                return rmdir($path);
            }
        } else if(is_file($path) === true){
            return unlink($path);
        }
        return false;
    }
    
    function get_all_files($path){
        $all_files = array();
        
        foreach(scandir($path) as $file){
            if($file == '.' || $file == '..') continue;
            if(is_dir($path . DS . $file)){
                $all_files = array_merge($all_files, get_all_files($path . DS . $file));
            } else {
                $all_files[] = $path . DS . $file;
            }
        }
        
        return $all_files;
    }