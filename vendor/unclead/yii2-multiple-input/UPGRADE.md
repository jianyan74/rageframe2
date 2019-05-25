Upgrading Instructions for yii2-multiple-widget
===============================================

!!!IMPORTANT!!!

The following upgrading instructions are cumulative. That is,
if you want to upgrade from version A to version C and there is
version B between A and C, you need to following the instructions
for both A and B.

Upgrade 2.12.0
--------------
- Ensure you use yii2 2.0.13 and higher, otherwise you have to use previous version of the widget

Upgrade from 2.2.0 tp 2.3.0
---------------------------

- Ensure that you set `id` option in case you are using js actions, otherwise your old code won't work.

Upgrade from 2.0.0 tp 2.0.1
---------------------------

- Change namespace prefix `yii\multipleinput\` to `unclead\multipleinput\`.

Upgrade from 1.4 to 2.0
-----------------------

- Rename option `limit` to `max`
- Change namespace prefix `unclead\widgets\` to `yii\multipleinput\`.

Upgrade from 1.3 to 1.4
-----------------------
- In scope of #97 was changed a behavior of rendering add button. The button renders now depends on option `addButtonPosition` and now this
option is not set by default. 


Upgrade from 1.2 to 1.3
-----------------------

- The mechanism of customization configuration by using index placeholder was changed in scope of implementing support of nested `MultipleInput`
If you customize configuration by using index placeholder you have to add ID of widget to the placeholder.
For example, `multiple_index` became `multiple_index_question_list`


Upgrade from version less then 1.1.0
------------------------------------

After installing version 1.1.0 you have to rename js events following the next schema:

- Event `init` rename to `afterInit` 
- Event `addNewRow` rename to `afterAddRow`
- Event `removeRow` rename to `afterDeleteRow` 
