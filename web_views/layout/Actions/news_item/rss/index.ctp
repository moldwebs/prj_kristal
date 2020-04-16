<?php
foreach ($items as $item) {
    $postTime = strtotime($item['ObjItemList']['created']);

    $postLink = ws_url($item['ObjItemList']['alias'], false);

    // Remove & escape any HTML to make sure the feed content will validate.
    $bodyText = h(strip_tags($item['ObjItemList']['body']));
    $bodyText = $this->Text->truncate($bodyText, 400, array(
        'ending' => '...',
        'exact'  => true,
        'html'   => true,
    ));

    echo  $this->Rss->item(array(), array(
        'title' => $item['ObjItemList']['title'],
        'link' => $postLink,
        'guid' => array('url' => $postLink, 'isPermaLink' => 'true'),
        'description' => $bodyText,
        'pubDate' => $item['ObjItemList']['created']
    ));
}

?>