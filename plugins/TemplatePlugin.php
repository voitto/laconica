<?php
/**
 * Plugin to render old skool templates
 *
 * Captures rendered parts in the output buffer, passes them through a template file: tpl/[act].html
 *
 * @category  Plugin
 * @package   Laconica
 * @author    Brian Hendrickson <brian@megapump.com>
 * @copyright 2009 Megapump, Inc.
 * @license   http://www.fsf.org/licensing/licenses/agpl-3.0.html GNU Affero General Public License version 3.0
 * @link      http://megapump.com/
 */

if (!defined('LACONICA')) {
    exit(1);
}

define('TEMPLATEPLUGIN_VERSION', '0.1');

class TemplatePlugin extends Plugin {
  
  var $blocks = array();
  
  function __construct() {
    parent::__construct();
  }
  
  // <%styles%>
  // <%scripts%>
  // <%search%>
  // <%feeds%>
  // <%description%>
  // <%head%>
  function onStartShowHead( &$act ) {
    $this->clear_xmlWriter($act);
    $act->extraHead();
    $this->blocks['head'] = $act->xw->flush();
    $act->showStylesheets();
    $this->blocks['styles'] = $act->xw->flush();    
    $act->showScripts();
    $this->blocks['scripts'] = $act->xw->flush();    
    $act->showFeeds();
    $this->blocks['feeds'] = $act->xw->flush();    
    $act->showOpenSearch();
    $this->blocks['search'] = $act->xw->flush();    
    $act->showDescription();
    $this->blocks['description'] = $act->xw->flush();
    return false;
  }
  
  // <%bodytext%>
  function onStartShowContentBlock( &$act ) {
    $this->clear_xmlWriter($act);
    return true;
  }
  function onEndShowContentBlock( &$act ) {
    $this->blocks['bodytext'] = $act->xw->flush();
  }
  
  // <%localnav%>
  function onStartShowLocalNavBlock( &$act ) {
    $this->clear_xmlWriter($act);
    return true;
  }
  function onEndShowLocalNavBlock( &$act ) {
    $this->blocks['localnav'] = $act->xw->flush();
  }
  
  // <%export%>
  function onStartShowExportData( &$act ) {
    $this->clear_xmlWriter($act);
    return true;
  }
  function onEndShowExportData( &$act ) {
    $this->blocks['export'] = $act->xw->flush();
  }
  
  // <%subscriptions%>
  // <%subscribers%>
  // <%groups%>
  // <%statistics%>
  // <%cloud%>
  // <%groupmembers%>
  // <%groupstatistics%>
  // <%groupcloud%>
  // <%popular%>
  // <%groupsbyposts%>
  // <%featuredusers%>
  // <%groupsbymembers%>
  function onStartShowSections( &$act ) {
    global $action;
    $this->clear_xmlWriter($act);
    switch ($action) {
      case "showstream":
        $act->showSubscriptions();
        $this->blocks['subscriptions'] = $act->xw->flush();
        $act->showSubscribers();
        $this->blocks['subscribers'] = $act->xw->flush();
        $act->showGroups();
        $this->blocks['groups'] = $act->xw->flush();
        $act->showStatistics();
        $this->blocks['statistics'] = $act->xw->flush();
        $cloud = new PersonalTagCloudSection($act, $act->user);
        $cloud->show();
        $this->blocks['cloud'] = $act->xw->flush();
        break;
      case "showgroup":
        $act->showMembers();
        $this->blocks['groupmembers'] = $act->xw->flush();
        $act->showStatistics();
        $this->blocks['groupstatistics'] = $act->xw->flush();
        $cloud = new GroupTagCloudSection($act, $act->group);
        $cloud->show();
        $this->blocks['groupcloud'] = $act->xw->flush();
        break;
      case "public":
        $pop = new PopularNoticeSection($act);
        $pop->show();
        $this->blocks['popular'] = $act->xw->flush();
        $gbp = new GroupsByPostsSection($act);
        $gbp->show();
        $this->blocks['groupsbyposts'] = $act->xw->flush();
        $feat = new FeaturedUsersSection($act);
        $feat->show();
        $this->blocks['featuredusers'] = $act->xw->flush();
        break;
      case "groups":
        $gbp = new GroupsByPostsSection($act);
        $gbp->show();
        $this->blocks['groupsbyposts'] = $act->xw->flush();
        $gbm = new GroupsByMembersSection($act);
        $gbm->show();
        $this->blocks['groupsbymembers'] = $act->xw->flush();
        break;
    }
    return false;
  }
  
  // <%header%>
  function onStartShowHeader( &$act ) {
    $this->clear_xmlWriter($act);
    return true;
  }
  function onEndShowHeader( &$act ) {
    $this->blocks['header'] = $act->xw->flush();
  }
  
  // <%footer%>
  function onStartShowFooter( &$act ) {
    $this->clear_xmlWriter($act);
    return true;
  }
  function onEndShowFooter( &$act ) {
    $this->blocks['footer'] = $act->xw->flush();
  }
  
  function onEndEndHTML($act) {
    
    global $user, $action, $config;
    
    $tpl_file = 'tpl/index.html';
    
    $output = file_get_contents( $tpl_file );
    
    $vars = array(
      'action'=>$action,
      'title'=>$act->title(). " - ". common_config('site', 'name')
    );
    
    $tags = array();
    
    $pattern='/<%([a-z]+)%>/';
    
    if ( 1 <= preg_match_all( $pattern, $output, $found ))
      $tags[] = $found;
    
    foreach( $tags[0][1] as $pos=>$tag ) {
      if (isset($this->blocks[$tag]))
        $vars[$tag] = $this->blocks[$tag];
      elseif (!isset($vars[$tag]))
        $vars[$tag] = '';
    }
    
    foreach( $vars as $key=>$val )
      $output = str_replace( '<%'.$key.'%>', $val, $output );
    
    echo $output;
    
    return true;
    
  }
  
  function onStartShowHTML( &$act ) {
    $this->clear_xmlWriter($act);
    return true;
  }
  
  function clear_xmlWriter( &$act ) {
    $act->xw->openMemory();
    $act->xw->setIndent(true);
  }
  
}
