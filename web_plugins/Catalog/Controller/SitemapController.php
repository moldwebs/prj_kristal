<?php
class SitemapController extends CatalogAppController {

    public $step = 300;

    public $paginate = array(
        'ObjItemList' => array(
            'paramType' => 'querystring',
            'limit' => CMS_PAGE_LIMIT,
            'order' => array(
                'ObjItemList.created' => 'desc'
            ),
            'conditions' => array('ObjItemList.rel_id IS NULL')
        )
    );

    public function beforeFilter() {
        parent::beforeFilter();

        Configure::write('TMP.no_rel_id' , '1');

        //$this->step = 10;

        //$this->set('manufacturers', $this->ObjItemList->find('list', array('tid' => 'manufacturer', 'order' => array('title' => 'asc'))));
    }

    public function afterFilter() {
        parent::afterFilter();

        Configure::write('TMP.no_rel_id' , '0');
    }

    public function index(){
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $def_locale = Configure::read('Config.def_language');
        if($def_locale == 'rus') $_locale = 'rom'; else $_locale = 'rus';

        Configure::write('Config.language', $def_locale);

        Configure::write('TMP.sql_no_cache', '1');
        Configure::write('PassCurrencyBehaviorbeforeFind', '1');
        Configure::write('PassCurrencyBehaviorafterFind', '1');

        //$this->ObjItemList->Behaviors->unload('Alias');
        $this->ObjItemList->Behaviors->unload('Search');
        $this->ObjItemList->Behaviors->unload('Attach');
        $this->ObjItemList->Behaviors->unload('Relation');
        $this->ObjItemList->Behaviors->unload('Catalog.Catalog');
        $this->ObjItemList->Behaviors->unload('Currency.Currency');
        if(Configure::read('PLUGIN.Specification') == '1') $this->ObjItemList->Behaviors->unload('Specification.Specification');

        $this->ObjItemList->unbindModel(array(
            'hasMany' => array('ObjOptAttach', 'ObjOptRelation'),
            'hasOne' => array('ObjOptAttachDef', 'ModCurrency'),
            'belongsTo' => array('Manufacturer', 'User'),
        ), false);

        App::uses('Xml', 'Utility');

        if(empty($_GET['page'])){

            $xmlArray = array();

            $xmlArray['sitemapindex'] = array('xmlns:' => 'http://www.sitemaps.org/schemas/sitemap/0.9');

            $count = $this->ObjItemList->query("SELECT COUNT(*) AS qnt FROM wb_obj_item_list WHERE tid = 'catalog' AND status = '1'");
            $count = $count[0][0]['qnt'];
            $max_date = $this->ObjItemList->query("SELECT MAX(created) AS max_date FROM wb_obj_item_list WHERE tid = 'catalog' AND status = '1'");
            $max_date = date("Y-m-d", strtotime($max_date[0][0]['max_date']));

            for($i=1;$i<=ceil($count/$this->step);$i++){
                $_array = array('loc' => FULL_BASE_URL . "/catalog/sitemap/index?page=" . $i);
                if($i == 1){
                    $_array['lastmod'] = $max_date;
                }
                $xmlArray['sitemapindex']['sitemap'][] = $_array;
            }

            $xmlObject = Xml::fromArray($xmlArray);
            $xmlString = $xmlObject->asXML();

            header('Content-Type: application/xml; charset=utf-8');

            echo $xmlString;
            exit;

        } else {
            $xmlArray = array();

            $xmlArray['urlset'] = array(
                'xmlns:' => 'http://www.sitemaps.org/schemas/sitemap/0.9',
                'xmlns:xhtml' => "http://www.w3.org/1999/xhtml"
            );

            $items = $this->ObjItemList->find('all', array('conditions' => array('ObjItemList.status' => '1'), 'order' => array('ObjItemList.created' => 'desc'), 'limit' => (($_GET['page']-1)*$this->step) . ',' . $this->step));
            foreach($items as $item){
                if(empty($item['Translates']['ObjItemList']['alias'][$_locale])) $item['Translates']['ObjItemList']['alias'][$_locale] = $item['Translates']['ObjItemList']['alias'][$def_locale];
                if(empty($item['Translates']['ObjItemTree']['alias'][$_locale])) $item['Translates']['ObjItemTree']['alias'][$_locale] = $item['Translates']['ObjItemTree']['alias'][$def_locale];

                $_array = array('loc' => FULL_BASE_URL . "/{$def_locale}/" . $item['ObjItemList']['alias']);
                if($item['Translates']['ObjItemList']['alias'][$def_locale] != $item['Translates']['ObjItemList']['alias'][$_locale] || $item['Translates']['ObjItemTree']['alias'][$def_locale] != $item['Translates']['ObjItemTree']['alias'][$_locale]){
                    $_array['xhtml:link'] = array(
                        '@rel' => 'alternate',
                        '@hreflang' => substr($_locale, 0, 2),
                        '@href' => FULL_BASE_URL . "/{$_locale}/" . $item['Translates']['ObjItemTree']['alias'][$_locale] . '/' . $item['Translates']['ObjItemList']['alias'][$_locale]
                    );
                }
                $_array['lastmod'] = date("Y-m-d", strtotime($item['ObjItemList']['created']));
                $xmlArray['urlset']['url'][] = $_array;
            }

            $xmlObject = Xml::fromArray($xmlArray);
            $xmlString = $xmlObject->asXML();

            header('Content-Type: application/xml; charset=utf-8');

            echo $xmlString;
            exit;

        }

        exit;
    }
}
