<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Bt_Sst_WC_Shipment_Email extends WC_Email{


    public function __construct(  ) {
        // set ID, this simply needs to be a unique name
        $this->id = 'bt_sst_wc_shipment_email';

        // Is a customer email
		$this->customer_email = true;

        // this is the title in WooCommerce Email settings
        $this->title = 'Shipment Status';

        // this is the description in WooCommerce email settings
        $this->description = 'Shipment Status Notification emails are sent when a shipment status of an order is changed.';

        // these are the default heading and subject lines that can be overridden using the settings
        $this->heading = '📦 Your Package Update!';

        $this->subject = '📦 Your Package Update!';

        // these define the locations of the templates that this email should use, we'll just use the new order template since this email is similar
        $this->template_base  = plugin_dir_path( __FILE__ ) . 'templates/';
        $this->template_html  = 'customer-order-shipment.php';
        $this->template_plain = 'customer-order-shipment-plain.php';

        // Trigger on new paid orders
        //add_action( 'woocommerce_order_status_pending_to_processing_notification', array( $this, 'trigger' ) );
        add_filter( 'woocommerce_email_subject_' . $this->id , array($this,'custom_subject'), 1, 2 );
        add_action( 'bt_shipment_status_changed',  array( $this, 'bt_shipment_status_changed_callback' ) ,10,3);
       // add_action( 'woocommerce_email_after_order_table', array( $this, 'custom_content_to_processing_customer_email' ), 10, 4 );

        // Call parent constructor to load any other defaults not explicity defined here
        parent::__construct();

        // this sets the recipient to the settings defined below in init_form_fields()
       // $this->recipient = 'Customer';

        // if none was entered, just use the WP admin email as a fallback
        // if ( ! $this->recipient )
        //     $this->recipient = get_option( 'admin_email' );
    }

    function custom_subject( $subject, $order ) {
        try{
            $tracking_current = bt_get_shipping_tracking($order->get_id());
            if($tracking_current && isset($tracking_current["tracking_data"])){
                $current_status = $tracking_current["tracking_data"]["current_status"];
                if($current_status == "delivered"){
                    $subject = '📦 Your Package has been Delivered.';
                }else if($current_status == "out-for-delivery"){
                    $subject = '📦 Your Package is Out for Delivery!';
                }else if($current_status == "in-transit"){
                    $subject = '📦 Your Package is in Transit!';
                }
            }
            
        }catch(Exception $e){
        }
        return $subject;
    }

    function custom_content_to_processing_customer_email( $order, $sent_to_admin, $plain_text, $email ) {

        //if( 'Bt_Sst_WC_Shipment_Email' == $email->id ){
   
            echo do_shortcode("[bt_shipping_tracking_form_2 email=true order_id='".$order->get_id()."']");
    
       // }
    
    }


    public function bt_shipment_status_changed_callback( $order_id,$shipment_obj,$shipment_obj_old) {

        //making sure that status has changed
        if($shipment_obj_old == null || $shipment_obj->current_status != $shipment_obj_old->current_status){
            
            //latest shipment tracking:
            $courier_name = $shipment_obj->courier_name;
            $current_status = $shipment_obj->current_status;
            $awb = $shipment_obj->awb;
            $tracking_url = $shipment_obj->tracking_url;

            //previous shipment tracking:
            $old_courier_name = $shipment_obj_old->courier_name;
            $old_status = $shipment_obj_old->current_status;
            $old_awb = $shipment_obj_old->awb;
            $old_tracking_url = $shipment_obj_old->tracking_url;

            // do stuff
            if($this->should_send_msg('email', $old_status , $current_status)){
                $this->trigger($order_id);
            }
        
        }
    }

    private function should_send_msg($event_name, $old_status , $current_status ){
		
		$bt_sst_shipment_from_what_send_messages = carbon_get_theme_option( 'bt_sst_shipment_from_what_send_messages' );

		if (!in_array($event_name, $bt_sst_shipment_from_what_send_messages, true)) {
			return false;
		}

        if(empty( $current_status)){
            return false;
        }

        if( $old_status == $current_status){
            return false;
        }

        $selected_events = $this->get_option( 'email_events', array( 'in_transit', 'out_for_delivery', 'delivered' ) );
        if ( ! in_array( strtolower( $current_status ), $selected_events, true ) ) {
            return false;
        }
		
		return true;
	}

   

    public function trigger( $order_id ) {

         if ( ! $this->is_enabled() ) {
            return;
        }

        // bail if no order ID is present
        if ( ! $order_id )
            return;

        // setup order object
        $this->object = new WC_Order( $order_id );

        // bail if shipping method is not expedited
       // if ( ! in_array( $this->object->get_shipping_method(), array( 'Three Day Shipping', 'Next Day Shipping' ) ) )
       //     return;

        // replace variables in the subject/headings
        $this->find[] = '{order_date}';
        $this->replace[] = date_i18n( woocommerce_date_format(), strtotime( $this->object->order_date ) );

        $this->find[] = '{order_number}';
        $this->replace[] = $this->object->get_order_number();

        $this->recipient = $this->object->get_billing_email();

        if ( ! $this->is_enabled() || ! $this->get_recipient() )
            return;
        
        $this->object->add_order_note( "Email sent to customer: ". $this->get_recipient() . "\n\n- Shipment tracker for woocommerce", false );
					
        // woohoo, send the email!
        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
    }

    // Generate email content
    public function get_content_html() {
        ob_start();
        wc_get_template( $this->template_html, array(
            'order'         => $this->object,
            'email_heading' => $this->get_heading(),
            'sent_to_admin' => false,
            'plain_text'    => false,
            'email'         => $this,
        ), '', $this->template_base );

        return ob_get_clean();
    }

    public function get_content_plain() {
        ob_start();
        wc_get_template( $this->template_plain, array(
            'order'         => $this->object,
            'email_heading' => $this->get_heading(),
            'sent_to_admin' => false,
            'plain_text'    => true,
            'email'         => $this,
        ), '', $this->template_base );

        return ob_get_clean();
    }

    // Add email settings (optional)
    public function init_form_fields() {

        $arr = apply_filters( 'bt_sst_shipping_statuses', BT_SHIPPING_STATUS );

        $this->form_fields = array(
            'enabled'    => array(
                'title'   => 'Enable/Disable',
                'type'    => 'checkbox',
                'label'   => 'Enable this email notification',
                'default' => 'yes'
            ),
            // 'recipient'  => array(
            //     'title'       => 'Recipient(s)',
            //     'type'        => 'text',
            //     'description' => sprintf( 'Enter recipients (comma separated) for this email. Defaults to <code>%s</code>.', esc_attr( get_option( 'admin_email' ) ) ),
            //     'placeholder' => '',
            //     'default'     => ''
            // ),
            'subject'    => array(
                'title'       => 'Subject',
                'type'        => 'text',
                'description' => sprintf( 'This controls the email subject line. Leave blank to use the default subject: <code>%s</code>.', $this->subject ),
                'placeholder' => '',
                'default'     => ''
            ),
            'heading'    => array(
                'title'       => 'Email Heading',
                'type'        => 'text',
                'description' => sprintf( __( 'This controls the main heading contained within the email notification. Leave blank to use the default heading: <code>%s</code>.' ), $this->heading ),
                'placeholder' => '',
                'default'     => ''
            ),
            'email_type' => array(
                'title'       => 'Email type',
                'type'        => 'select',
                'description' => 'Choose which format of email to send.',
                'default'     => 'html',
                'class'       => 'email_type',
                'options'     => array(
                    'plain'     => 'Plain text',
                    'html'      => 'HTML', 'woocommerce',
                    'multipart' => 'Multipart', 'woocommerce',
                )
                ),
                'email_events' => array(
                    'title'       => __( 'Email Events', 'woocommerce' ),
                    'type'        => 'multiselect',
                    'description' => __( 'Select the shipment events for which this email should be sent.', 'woocommerce' ),
                    'options'     => $arr,
                    'default'     => array( 'in_transit', 'out_for_delivery', 'delivered' ), // Default selected events
                    'desc_tip'    => true,
                ),
        );
    }


}
