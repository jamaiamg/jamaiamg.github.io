<div id="revechat_new_account" class="box">

    <h3>Create a new REVE Chat account</h3>
    <table class="form-table">
        <tr>
            <th>
                <label for="edit-firstname">
                    <?php
                    _e("First Name")
                    ?>
                </label>
            </th>
            <td>
                <input type="text" class="regular-text" name="firstName" id="edit-firstname" placeholder="First Name">
            </td>
        </tr>


        <tr>
            <th>
                <label for="edit-lastname">
                    <?php
                    _e("Last Name")
                    ?>
                </label>
            </th>
            <td>
                <input type="text" class="regular-text" name="firstName" id="edit-lastname" placeholder="Last Name">
            </td>
        </tr>

        <tr>
            <th>
                <label for="edit-email">
                    <?php
                    _e('Email Address')
                    ?>
                </label>

            </th>
            <td>
                <input type="text" class="regular-text" name="email" id="edit-email" placeholder="Email address">
            </td>
        </tr>

        <tr>
            <th>
                <label for="edit-accountpassword">
                    <?php
                    _e('Password')
                    ?>
                </label>
            </th>
            <td>
                <input type="password" class="regular-text" name="edit-accountpassword" id="edit-accountpassword" placeholder="Password">
            </td>
        </tr>

         <tr>
            <th>
                <label for="edit-retypepassword">
                    <?php
                    _e('Confirm your password')
                    ?>
                </label>
            </th>
            <td>
                <input type="password" class="regular-text" name="edit-retypepassword" id="edit-retypepassword" placeholder="Confirm your password">
            </td>
        </tr>
    </table>
    <p class="submit">
        <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Create Account') ?>" />
    </p>
</div>