<div class="calendarConfigs <?php echo $wrapperClass; ?>" data-calendar-id="<?php echo $calendarID; ?>">
    <a class="removable">&times;</a>
    <h4><?php echo $calendarName; ?></h4>
    <div class="clearfix">
        <label>
            <input <?php if($overrideColors){echo 'checked="checked"'; } ?> type="checkbox" name="cal[<?php echo $calendarID; ?>][overrideColors]" value="1" data-toggle="table.table" <?php echo ($disableForms) ? 'disabled="disabled"' : ''; ?> />
            Override Default Event Colors
        </label>
        <table class="table table-bordered">
            <tr>
                <td class="colName">Background Color</td>
                <td>
                    <div class="calColorSwatch bg">
                        <input type="hidden" name="cal[<?php echo $calendarID; ?>][bgColor]" <?php echo ($disableForms) ? 'disabled="disabled"' : ''; ?> />
                    </div>
                </td>
            </tr>
            <tr>
                <td class="colName">Text Color</td>
                <td>
                    <div class="calColorSwatch text">
                        <input type="hidden" name="cal[<?php echo $calendarID; ?>][textColor]" <?php echo ($disableForms) ? 'disabled="disabled"' : ''; ?> />
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <input type="hidden" name="cal[<?php echo $calendarID; ?>][presence]" <?php echo ($disableForms) ? 'disabled="disabled"' : ''; ?> />
</div>