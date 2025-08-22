<nav class="panel">
  <div class="panel-block">
    <div class="columns" style="width:100%">
      <div class="column is-half ">
        <div class="m-2">
          <?php echo wp_nonce_field('check_user_data_for_premium_features', '_wpnonce', true, false); ?>
          <article id="bt_st_buy_premium_login_panel" class="panel is-danger">
            <p class="panel-heading">
              Login to activate premium
            </p>
            <div class="panel-block">
              <div class="control">
                <div class="field">
                  <p class="control has-icons-left has-icons-right">
                    <input id="bt_st_buy_premium_login_email" class="input is-medium" type="email" placeholder="Email">
                    <span class="icon is-small is-left">
                      <span class="dashicons dashicons-email"></span>
                    </span>
                    <span class="icon is-small is-right">
                      <i class="fas fa-check"></i>
                    </span>
                  </p>
                </div>
                <div class="field">
                  <p class="control has-icons-left">
                    <input id="bt_st_buy_premium_login_password" class="input is-medium" type="password"
                      placeholder="Password">
                    <span class="icon is-small is-left">
                      <span class="dashicons  dashicons-admin-network"></span>
                    </span>
                  </p>
                </div>

                <div class="field">
                  <p class="control">
                    <button type="button" id="bt_st_buy_premium_login_submit_btn" class="button is-medium is-fullwidth">
                      <span class="icon">
                        <i class="dashicons dashicons-lock"></i>
                      </span>
                      <span>Login & Activate</span>
                    </button>
                  </p>
                </div>
              </div>


            </div>


          </article>
          <div id="bt_st_buy_premium_login_message" class="notification is-danger is-light">
            Your license is not active.
          </div>




        </div>

      </div>
      <div class="column is-half ">
        <div class="m-2">

          <article class="panel is-primary">
            <p class="panel-heading">
              How to activate premium?
            </p>
            <a class="panel-block is-active">
              <span class="panel-icon">
                <i class="fas fa-book" aria-hidden="true"></i>
              </span>
              Step 1. Create new account and purchase premium license. (link given below)
            </a>
            <a class="panel-block">
              <span class="panel-icon">
                <i class="fas fa-book" aria-hidden="true"></i>
              </span>
              Step 2. Login using the same creditials in the form.
            </a>
            <a class="panel-block">
              <span class="panel-icon">
                <i class="fas fa-book" aria-hidden="true"></i>
              </span>
              Step 3. Thats it!! Enjoy premium features and priority support.
            </a>

          </article>


          <div>
            <a href="https://shipment-tracker-for-woocommerce.bitss.tech/" target="_blank"
              class="ml-1 button is-focused is-medium is-fullwidth">Buy Premium License</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</nav>
<div class="m-2 bt_sst_is_premium_remove_container">
    <button type="submit" id="bt_sst_premium_remove" class="button is-danger is-medium is-fullwidth">
      <span class="icon">
        <i class="dashicons dashicons-no-alt"></i>
      </span>
      <span>Remove Premium</span>
    </button>
</div>
