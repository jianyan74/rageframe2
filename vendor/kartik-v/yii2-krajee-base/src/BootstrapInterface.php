<?php

/**
 * @package   yii2-krajee-base
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2019
 * @version   2.0.5
 */

namespace kartik\base;

/**
 * BootstrapInterface includes bootstrap constants and any common method signatures
 *
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 */
interface BootstrapInterface
{
    /**
     * @var string bootstrap **extra small** size modifier - **deprecated** - use [[SIZE_X_SMALL]] instead
     */
    const SIZE_TINY = 'xs';

    /**
     * @var string bootstrap **extra small** size modifier
     */
    const SIZE_X_SMALL = 'xs';

    /**
     * @var string bootstrap **small** size modifier
     */
    const SIZE_SMALL = 'sm';

    /**
     * @var string bootstrap **medium** size modifier (this is the default size)
     */
    const SIZE_MEDIUM = 'md';

    /**
     * @var string bootstrap **large** size modifier
     */
    const SIZE_LARGE = 'lg';

    /**
     * @var string bootstrap **large** size modifier
     */
    const SIZE_X_LARGE = 'xl';

    /**
     * @var string Bootstrap panel
     */
    const BS_PANEL = 'panel';

    /**
     * @var string Bootstrap panel heading
     */
    const BS_PANEL_HEADING = 'panel-heading';

    /**
     * @var string Bootstrap panel title
     */
    const BS_PANEL_TITLE = 'panel-title';

    /**
     * @var string Bootstrap panel body
     */
    const BS_PANEL_BODY = 'panel-body';

    /**
     * @var string Bootstrap panel footer
     */
    const BS_PANEL_FOOTER = 'panel-footer';

    /**
     * @var string Bootstrap panel default contextual color
     */
    const BS_PANEL_DEFAULT = 'panel-default';

    /**
     * @var string Bootstrap panel primary contextual color
     */
    const BS_PANEL_PRIMARY = 'panel-primary';

    /**
     * @var string Bootstrap panel success contextual color
     */
    const BS_PANEL_SUCCESS = 'panel-success';

    /**
     * @var string Bootstrap panel info contextual color
     */
    const BS_PANEL_INFO = 'panel-info';

    /**
     * @var string Bootstrap panel warning contextual color
     */
    const BS_PANEL_WARNING = 'panel-warning';

    /**
     * @var string Bootstrap panel danger contextual color
     */
    const BS_PANEL_DANGER = 'panel-danger';

    /**
     * @var string Bootstrap badge style
     */
    const BS_BADGE = 'badge';

    /**
     * @var string Bootstrap label
     */
    const BS_LABEL = 'label';

    /**
     * @var string Bootstrap label default contextual color
     */
    const BS_LABEL_DEFAULT = 'label-default';

    /**
     * @var string Bootstrap label primary contextual color
     */
    const BS_LABEL_PRIMARY = 'label-primary';

    /**
     * @var string Bootstrap label success contextual color
     */
    const BS_LABEL_SUCCESS = 'label-success';

    /**
     * @var string Bootstrap label info contextual color
     */
    const BS_LABEL_INFO = 'label-info';

    /**
     * @var string Bootstrap label warning contextual color
     */
    const BS_LABEL_WARNING = 'label-warning';

    /**
     * @var string Bootstrap label danger contextual color
     */
    const BS_LABEL_DANGER = 'label-danger';

    /**
     * @var string Bootstrap table default contextual color
     */
    const BS_TABLE_ACTIVE = 'table-active';

    /**
     * @var string Bootstrap table primary contextual color
     */
    const BS_TABLE_PRIMARY = 'table-primary';

    /**
     * @var string Bootstrap table success contextual color
     */
    const BS_TABLE_SUCCESS = 'table-success';

    /**
     * @var string Bootstrap table info contextual color
     */
    const BS_TABLE_INFO = 'table-info';

    /**
     * @var string Bootstrap table warning contextual color
     */
    const BS_TABLE_WARNING = 'table-warning';

    /**
     * @var string Bootstrap table danger contextual color
     */
    const BS_TABLE_DANGER = 'table-danger';

    /**
     * @var string Bootstrap progress-bar active animated state
     */
    const BS_PROGRESS_BAR_ACTIVE = 'progress-bar-active';

    /**
     * @var string Bootstrap progress-bar primary contextual color
     */
    const BS_PROGRESS_BAR_PRIMARY = 'progress-bar-primary';

    /**
     * @var string Bootstrap progress-bar success contextual color
     */
    const BS_PROGRESS_BAR_SUCCESS = 'progress-bar-success';

    /**
     * @var string Bootstrap progress-bar info contextual color
     */
    const BS_PROGRESS_BAR_INFO = 'progress-bar-info';

    /**
     * @var string Bootstrap progress-bar warning contextual color
     */
    const BS_PROGRESS_BAR_WARNING = 'progress-bar-warning';

    /**
     * @var string Bootstrap progress-bar danger contextual color
     */
    const BS_PROGRESS_BAR_DANGER = 'progress-bar-danger';

    /**
     * @var string Bootstrap well style
     */
    const BS_WELL = 'well';

    /**
     * @var string Bootstrap well large style
     */
    const BS_WELL_LG = 'well-lg';

    /**
     * @var string Bootstrap well small style
     */
    const BS_WELL_SM = 'well-sm';

    /**
     * @var string Bootstrap well small style
     */
    const BS_THUMBNAIL = 'thumbnail';

    /**
     * @var string Bootstrap navbar right style
     */
    const BS_NAVBAR_DEFAULT = 'navbar-default';

    /**
     * @var string Bootstrap navbar toggle style
     */
    const BS_NAVBAR_TOGGLE = 'navbar-toggle';

    /**
     * @var string Bootstrap navbar right style
     */
    const BS_NAVBAR_RIGHT = 'navbar-right';

    /**
     * @var string Bootstrap navbar button style
     */
    const BS_NAVBAR_BTN = 'navbar-btn';

    /**
     * @var string Bootstrap navbar fixed top style
     */
    const BS_NAVBAR_FIXTOP = 'navbar-fixed-top';

    /**
     * @var string Bootstrap NAV stacked style
     */
    const BS_NAV_STACKED = 'nav-stacked';

    /**
     * @var string Bootstrap NAV item style
     */
    const BS_NAV_ITEM = 'nav-item';

    /**
     * @var string Bootstrap NAV link style
     */
    const BS_NAV_LINK = 'nav-link';

    /**
     * @var string Bootstrap page item style
     */
    const BS_PAGE_ITEM = 'page-item';

    /**
     * @var string Bootstrap page link style
     */
    const BS_PAGE_LINK = 'page-link';

    /**
     * @var string Bootstrap list inline item style
     */
    const BS_LIST_INLINE_ITEM = 'list-inline-item';

    /**
     * @var string Bootstrap default button style
     */
    const BS_BTN_DEFAULT = 'btn-default';

    /**
     * @var string Bootstrap image responsive style
     */
    const BS_IMG_RESPONSIVE = 'img-responsive';

    /**
     * @var string Bootstrap image circle style
     */
    const BS_IMG_CIRCLE = 'img-circle';

    /**
     * @var string Bootstrap image rounded style
     */
    const BS_IMG_ROUNDED = 'img-rounded';

    /**
     * @var string Bootstrap radio style
     */
    const BS_RADIO = 'radio';

    /**
     * @var string Bootstrap checkbox style
     */
    const BS_CHECKBOX = 'checkbox';

    /**
     * @var string Bootstrap large input style
     */
    const BS_INPUT_LG = 'input-lg';

    /**
     * @var string Bootstrap small input style
     */
    const BS_INPUT_SM = 'input-sm';

    /**
     * @var string Bootstrap label control style
     */
    const BS_CONTROL_LABEL = 'control-label';

    /**
     * @var string Bootstrap condensed table style
     */
    const BS_TABLE_CONDENSED = 'table-condensed';

    /**
     * @var string Bootstrap carousel item style
     */
    const BS_CAROUSEL_ITEM = 'carousel-item';

    /**
     * @var string Bootstrap carousel item next style
     */
    const BS_CAROUSEL_ITEM_NEXT = 'carousel-item-next';

    /**
     * @var string Bootstrap carousel item previous style
     */
    const BS_CAROUSEL_ITEM_PREV = 'carousel-item-prev';

    /**
     * @var string Bootstrap carousel item left style
     */
    const BS_CAROUSEL_ITEM_LEFT = 'carousel-item-left';

    /**
     * @var string Bootstrap carousel item right style
     */
    const BS_CAROUSEL_ITEM_RIGHT = 'carousel-item-right';

    /**
     * @var string Bootstrap carousel control left style
     */
    const BS_CAROUSEL_CONTROL_LEFT = 'carousel-control-left';

    /**
     * @var string Bootstrap carousel control right style
     */
    const BS_CAROUSEL_CONTROL_RIGHT = 'carousel-control-right';

    /**
     * @var string Bootstrap help block style
     */
    const BS_HELP_BLOCK = 'form-text';

    /**
     * @var string Bootstrap pull right style
     */
    const BS_PULL_RIGHT = 'pull-right';

    /**
     * @var string Bootstrap pull left style
     */
    const BS_PULL_LEFT = 'pull-left';

    /**
     * @var string Bootstrap center block style
     */
    const BS_CENTER_BLOCK = 'center-block';

    /**
     * @var string Bootstrap hide print style
     */
    const BS_HIDE = 'hide';

    /**
     * @var string Bootstrap hidden print style
     */
    const BS_HIDDEN_PRINT = 'hidden-print';

    /**
     * @var string Bootstrap hidden extra small style
     */
    const BS_HIDDEN_XS = 'hidden-xs';

    /**
     * @var string Bootstrap hidden small style
     */
    const BS_HIDDEN_SM = 'hidden-sm';

    /**
     * @var string Bootstrap hidden medium style
     */
    const BS_HIDDEN_MD = 'hidden-md';

    /**
     * @var string Bootstrap hidden large style
     */
    const BS_HIDDEN_LG = 'hidden-lg';

    /**
     * @var string Bootstrap hidden print block style
     */
    const BS_VISIBLE_PRINT = 'visible-print-block';

    /**
     * @var string Bootstrap visible extra small style
     */
    const BS_VISIBLE_XS = 'visible-xs';

    /**
     * @var string Bootstrap visible small style
     */
    const BS_VISIBLE_SM = 'visible-sm';

    /**
     * @var string Bootstrap visible medium style
     */
    const BS_VISIBLE_MD = 'visible-md';

    /**
     * @var string Bootstrap visible large style
     */
    const BS_VISIBLE_LG = 'visible-lg';

    /**
     * @var string Bootstrap form control static style
     */
    const BS_FORM_CONTROL_STATIC = 'form-control-static';

    /**
     * @var string Bootstrap dropdown divider style
     */
    const BS_DROPDOWN_DIVIDER = 'dropdown-divider';

    /**
     * @var string Bootstrap show style
     */
    const BS_SHOW = 'show';
}