<?php
namespace robot72\modules\articles\models;

use yii\widgets\LinkSorter;
use yii\helpers\ArrayHelper;

/**
 * ItemsListSorter
 *
 * @author Robert Kuznetsov
 */
class ItemsLinkSorter extends LinkSorter 
{
    
    /**
     * Visualizations sorting links
     * 
     * @return string html code
     */
    public function renderSortLinks() 
    {
        $attributes = empty($this->attributes) ? array_keys($this->sort->attributes) : $this->attributes;
        $links = [];
        $html  = '';
        /*
        foreach ($attributes as $name) {
            $links[] = $this->sort->link($name);
            $html .= $this->sort->link($name)."\n<span class=\"fast_nav__divider\">l</span>\n";
        }
        */
        $html .= $this->sort->link('created', ['alt' => 'Самые новые']);
        $html .= "\n<span class=\"fast_nav__divider\">l</span>\n";
        $html .= '<a href="">Самые спорные</a>';
        $html .= "\n<span class=\"fast_nav__divider\">l</span>\n";
        $html .= $this->sort->link('hits', ['alt' => 'Самые интересные']);
        $html .= "\n<span class=\"fast_nav__divider\">l</span>\n";
        $html .= '<a href="">Самые полезные</a>';
        return $html;
        //Html::ul($links, array_merge($this->options, ['encode' => false]));
    }
    
}
