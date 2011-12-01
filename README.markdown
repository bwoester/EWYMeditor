EWYMeditor
==========

**EWYMeditor** is an extension for Yii framework. This extension is a wrapper for [WYMeditor](http://www.wymeditor.org/ "WYMeditor") which is a web-based **WYSIWYM** (What You See Is What You Mean) XHTML editor (not WYSIWYG).

Requirements
------------

* Yii 1.1 or above (tested on 1.1.8)
* jQuery
* jQuery UI (for resizable plugin)

Installation
------------

Move **EWYMeditor** folder in your applications extensions folder (default: `protected/extensions`).

Using extension
---------------

There are three ways to use this extension.

Least obstructive (great if you want to test this extension, you don't want change your view file or you have more than one input to apply this extension to) way is:

```php
<?php $this->widget( 'ext.EWYMeditor.EWYMeditor', array(
  'target' => 'textarea',
)); ?>
```

Or you can use a model to create textarea:

```php
<?php $this->widget( 'ext.EWYMeditor.EWYMeditor', array(
  'model' => $model, // Your model
  'attribute' => 'description', // Attribute for textarea
)); ?>
```

Or if you want to add textarea without model:

```php
<?php $this->widget( 'ext.EWYMeditor.EWYMeditor', array(
  'name' => 'nameOfYourInput',
)); ?>
```

Since the wymEditor basically hides the textarea it is working on, it needs to
synchronize data before submitting. By default, wymEditor therefore relies
on a marker: its documentation instructs you to add the css class `wymupdate`
to your submit button.

To get rid of this marker, the EWYMeditor widget overrides this default with a
rule, that will select the submit button of the form containing the wymEditor.

If this is not working for you, you can provide your own selector as option:

```php
<?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('id' => 'mySubmitButton')); ?>
```

```php
<?php $this->widget( 'ext.EWYMeditor.EWYMeditor', array(
  'updateSelector' => '#mySubmitButton',
)); ?>
```

Configuration
-------------

You can change some default settings:

```php
<?php $this->widget( 'ext.EWYMeditor.EWYMeditor', array(
  'target' => 'textarea',
  'plugins' => array( 'fullscreen', 'hovertools', 'tidy', 'resizable' ),
  'options' => array(
    'skin' => 'silver',
    // Check http://trac.wymeditor.org/trac/wiki/0.5/Customization for available options
  ),
)); ?>
```

* **target** is jQuery selector for which elements to apply WYMeditor.
* **plugins** a list of plugins to add to WYMeditor. Allowed options: `fullscreen`, `hovertools`, `tidy`, `resizable`. You can provided an array or comma delimited string (requires additional processing to convert to an array) for plugins parameter. If you use plugins and provide your own `postInit` through `options`, you will need to initialize plugins yourself.
* **options** sets the options for WYMeditor. All available options can be found on [WYMeditor customization](http://trac.wymeditor.org/trac/wiki/0.5/Customization "WYMeditor customization").

Resources
---------

* [WYMeditor](http://www.wymeditor.org/ "WYMeditor")

