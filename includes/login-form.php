<div id="revechat_already_have">
    <h3>Account Details</h3>
    <table class="form-table">
        <tbody>
        <tr>
            <th>
                <label for="edit-revechat-account-email">
                    <?php
                    _e("REVE Chat Login Email");
                    ?>
                </label>
            </th>
            <td>
                <input type="email" class="revechat_account_email regular-text" name="revechat_account_email" id="edit-revechat-account-email">
                <input type="hidden" name="<?php echo $accountId; ?>" value="<?php echo $val_accountId; ?>" id="revechat_aid">
            </td>
        </tr>
        </tbody>
    </table>
</div><!-- revechat_already_have -->