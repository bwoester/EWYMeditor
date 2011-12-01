<?php
/**
 * EWYMeditor class file.
 * 
 * @author Andrius Marcinkevicius <andrew.web@ifdattic.com>
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 * @copyright Copyright &copy; 2011 Andrius Marcinkevicius
 * @license Licensed under MIT license. http://ifdattic.com/MIT-license.txt
 * @version 1.0
 */

Yii::import( 'zii.widgets.jui.CJuiInputWidget', true );

/**
 * EWYMeditor adds a WYSIWYM (What You See Is What You Mean) XHTML editor.
 * 
 * @author Andrius Marcinkevicius <andrew.web@ifdattic.com>
 * @author Benjamin Wöster <benjamin.woester@gmail.com>
 */
class EWYMeditor extends CJuiInputWidget
{
  /**
   * @var array the plugins which should be added to editor.
   */
  public $plugins = array();
  
  /**
   * @var string apply wymeditor plugin to these elements.
   */
  public $target = null;
  
  /**
   * Url of published assets
   * @var string
   */
  private $_wymEditorUrl = '';

  /////////////////////////////////////////////////////////////////////////////

	public function init()
  {
    parent::init();
    $this->_publishAssets();
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Add WYMeditor to the page.
   */
  public function run()
  {
    // Add textarea to the page  
    if( $this->target === null )
    {
      list( $name, $id ) = $this->resolveNameID();
      
      if( $this->hasModel() )
        echo CHtml::activeTextArea( $this->model, $this->attribute, $this->htmlOptions );
      else
        echo CHtml::textArea( $name, $this->value, $this->htmlOptions );
    }
    
    // Add the plugins to editor
    if( $this->plugins !== array() )
    {
      $this->_addPlugins( $cs, $assets );
    }
    
    $options = CJavaScript::encode( $this->options );
    
    if( $this->target === null )
    {
      $this->_getClientScript()->registerScript( 'wym', "jQuery('#{$id}').wymeditor({$options});" );
    }
    else
    {
      $this->_getClientScript()->registerScript( 'wym', "jQuery('{$this->target}').wymeditor({$options});" );
    }
  }
  
  /////////////////////////////////////////////////////////////////////////////

  private function _publishAssets()
  {
    // "/assets/wymEditor" is the whole wymEditor package. Since we don't need
    // samples or the packaged jquery, we only publish what we need: the
    // wymeditor 
    $am = $this->_getAssetManager();
    $wymEditorFilepath = dirname(__FILE__) . '/assets/wymEditor/wymeditor';
    $this->_wymEditorUrl = $am->publish( $wymEditorFilepath );

    $cs = $this->_getClientScript();
    $cs->registerScriptFile( $this->_wymEditorUrl . '/jquery.wymeditor.min.js' );
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * Add plugins to the editor.
   * @var CClientScript the client script object.
   * @var string the path to the assets. 
   */
  private function _addPlugins( $cs, $assets )
  {
    // Available plugins array
    $plugins = array(
      'hovertools' => array(
        'file' => '/plugins/hovertools/jquery.wymeditor.hovertools.js',
        'init' => 'wym.hovertools();' ),
      'fullscreen' => array(
        'file' => '/plugins/fullscreen/jquery.wymeditor.fullscreen.js',
        'init' => 'wym.fullscreen();' ),
      'tidy' => array(
        'file' => '/plugins/tidy/jquery.wymeditor.tidy.js',
        'init' => 'var wymtidy = wym.tidy();wymtidy.init();' ),
      'resizable' => array(
        'file' => '/plugins/resizable/jquery.wymeditor.resizable.js',
        'init' => 'wym.resizable();' ),
    );
    
    // Replacement for 'postInit' option
    $postInit = array();
    
    // If string provided, convert it to an array
    if( !is_array( $this->plugins ) )
    {
      $this->plugins = explode( ',', $this->plugins );
      $this->plugins = array_map( 'trim', $this->plugins );
    }
    
    // Add all available plugins
    foreach( $this->plugins as $plugin )
    {
      if( isset( $plugins[$plugin] ) )
      {
        $cs->registerScriptFile( $assets . $plugins[$plugin]['file'],
          CClientScript::POS_END );
        $postInit[] = $plugins[$plugin]['init']; 
      }
    }
    
    // Replace 'postInit' option if user hasn't provided a custom one
    if( !isset( $this->options['postInit'] ) )
    {
      $this->options['postInit'] = "js:function(wym){"
        . implode( '', $postInit ) . "}";
    }
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * @return CAssetManager
   */
  private function _getAssetManager()
  {
    return Yii::app()->assetManager;
  }

  /////////////////////////////////////////////////////////////////////////////

  /**
   * @return CClientScript
   */
  private function _getClientScript()
  {
    return Yii::app()->clientScript;
  }

  /////////////////////////////////////////////////////////////////////////////

}
