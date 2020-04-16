<?php
Router::connect("/payment/:action/*", array('plugin' => 'payment', 'controller' => 'payment'));