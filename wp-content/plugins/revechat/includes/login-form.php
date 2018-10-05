<div id="revechat_already_have" class="box">
    <h3>Account Details</h3>
    <table class="form-table">
        <tbody>
        <tr>
            <th>
                <label for="edit-revechat-account-email">
                    <?php
                    _e("REVE Chat login email");
                    ?>
                </label>
            </th>
            <td>
                <input type="email" class="revechat_account_email regular-text" name="revechat_account_email" id="edit-revechat-account-email" placeholder="Enter your email address">
                <input type="hidden" name="<?php echo $accountId; ?>" value="<?php echo $val_accountId; ?>" id="revechat_aid">
            </td>
        </tr>
        </tbody>
    </table>
    <p class="submit">
        <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
    </p>
</div><!-- revechat_already_have -->