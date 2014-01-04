<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery.dynotable.js'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        /*
         * dynoTable configuration options
         * These are the options that are available with their default values
         */
        $('#t1').dynoTable({
            removeClass: '.row-remover', //class for the clickable row remover
            cloneClass: '.row-cloner', //class for the clickable row cloner
            addRowTemplateId: '#add-template', //id for the "add row template"
            addRowButtonId: '#add-row', //id for the clickable add row button, link, etc
            lastRowRemovable: true, //If true, ALL rows in the table can be removed, otherwise there will always be at least one row
            orderable: true, //If true, table rows can be rearranged
            dragHandleClass: ".drag-handle", //class for the click and draggable drag handle
            insertFadeSpeed: "slow", //Fade in speed when row is added
            removeFadeSpeed: "fast", //Fade in speed when row is removed
            hideTableOnEmpty: true, //If true, table is completely hidden when empty
            onRowRemove: function () {
                //Do something when a row is removed
            },
            onRowClone: function (clonedRow) {
                //Do something when a row is cloned
                clonedRow.find('input[name="PropValue[prop_value_id][]"]').val("");
            },
            onRowAdd: function () {
                //Do something when a row is added
            },
            onTableEmpty: function () {
                //Do something when ALL rows have been removed
            },
            onRowReorder: function () {
                //Do something when table rows have been rearranged
            }
        });
    });
</script>

<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'item-prop-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    ),
    'enableAjaxValidation' => false,
));
$form = new TbActiveForm();

if ($model->hasErrors()): ?>
    <div class="control-group">
        <?php echo $form->errorSummary($model); ?>
    </div>
<?php endif; ?>
<div class="control-group"><p class="help-block">带 <span class="required">*</span> 的字段为必填项.</p></div>
<?php
echo $form->dropDownListControlGroup($model, 'category_id', $model->getCategory());
echo $form->dropDownListControlGroup($model, 'parent_prop_id', $props);
echo $form->textFieldControlGroup($model, 'prop_name');
echo $form->textFieldControlGroup($model, 'prop_alias');
echo $form->inlineRadioButtonListControlGroup($model, 'type', $model->allType());
foreach (array('is_key_prop' => 'allKey', 'is_sale_prop' => 'allSale', 'is_color_prop' => 'allColor', 'must' => 'allMust', 'multi' => 'allMulti', 'status' => 'allStatus') as $k => $v) {
    echo $form->dropDownListControlGroup($model, $k, call_user_func(array($model, $v)));
}
?>

<h2><a id="add-row" href="#">添加属性值</a></h2>
<fieldset>
    <legend>属性值</legend>
    <div class="PropValues">
        <table id="t1" class="example">
            <tr>
                <th>移动</th>
                <th>属性值名称</th>
                <th>克隆</th>
                <th>删除</th>
            </tr>
            <?php if ($model->isNewRecord) { ?>
                <tr id="add-template">
                    <td class="icons">
                        <img class="drag-handle"
                             src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/drag.png"
                             alt="click and drag to rearrange"/>
                    </td>
                    <td>
                        <input id="tf1" type="text" name="PropValue[value_name][]"/>
                    </td>
                    <td class="icons">
                        <img class="row-cloner"
                             src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/clone.png"
                             alt="Clone Row"/>
                    </td>
                    <td class="icons">
                        <img class="row-remover"
                             src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/remove.png"
                             alt="Remove Row"/>
                    </td>
                </tr>
            <?php
            } else {
                $cri = new CDbCriteria(array(
                    'condition' => 'item_prop_id =' . $model->item_prop_id,
                    'order' => 'sort_order asc, prop_value_id asc'
                ));
                $propValues = PropValue::model()->findAll($cri);

                foreach ($propValues as $k => $sv) {
                    ?>
                    <tr id="update-template">
                        <td class="icons">
                            <img class="drag-handle"
                                 src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/drag.png"
                                 alt="click and drag to rearrange"/>
                        </td>
                        <td>
                            <input type="hidden" name="PropValue[prop_value_id][]"
                                   value="<?php echo $sv->prop_value_id; ?>"/>
                            <input id="tf1__c" type="text" name="PropValue[value_name][]"
                                   value="<?php echo $sv->value_name ?>"/>
                        </td>
                        <td class="icons">
                            <img class="row-cloner"
                                 src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/clone.png"
                                 alt="Clone Row"/>
                        </td>
                        <td class="icons">
                            <img class="row-remover"
                                 src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/remove.png"
                                 alt="Remove Row"/>
                        </td>
                    </tr>
                <?php } ?>

                <tr id="add-template">
                    <td class="icons">
                        <img class="drag-handle"
                             src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/drag.png"
                             alt="click and drag to rearrange"/>
                    </td>
                    <td>
                        <input type="hidden" name="PropValue[prop_value_id][]"/>
                        <input id="tf1" type="text" name="PropValue[value_name][]"/>
                    </td>
                    <td class="icons">
                        <img class="row-cloner"
                             src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/clone.png"
                             alt="Clone Row"/>
                    </td>
                    <td class="icons">
                        <img class="row-remover"
                             src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/remove.png"
                             alt="Remove Row"/>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</fieldset>

<?php
if (!$is_view) {
    echo TbHtml::formActions(array(
        TbHtml::submitButton('Submit', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
        TbHtml::resetButton('Reset'),
    ));
}
$this->endWidget();
?>
