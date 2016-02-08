<?php
namespace WeDevs\ERP\CRM;

/**
* Form request data handler class
*
* @since 1.0
*
* @package WP-ERP\CRM
*/
class Form_Handler {

    /**
     * Hook all actions
     *
     * @since 1.0
     *
     * @return void
     */
    public function __construct() {
        add_action( 'load-crm_page_erp-sales-customers', array( $this, 'customer_bulk_action') );
        add_action( 'load-crm_page_erp-sales-contact-groups', array( $this, 'contact_subscriber_bulk_action') );
    }

    /**
     * Handle customer bulk action
     *
     * @since 1.0
     *
     * @return void [redirection]
     */
    public function customer_bulk_action() {
        if ( ! isset( $_REQUEST['_wpnonce'] ) || ! isset( $_GET['page'] ) ) {
            return;
        }

        if ( $_GET['page'] != 'erp-sales-customers' ) {
            return;
        }

        if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-customers' ) ) {
            return;
        }

        $customer_table = new \WeDevs\ERP\CRM\Contact_List_Table();
        $action         = $customer_table->current_action();

        if ( $action ) {

            $redirect = remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'customer_search', 'filter_life_stage' ), wp_unslash( $_SERVER['REQUEST_URI'] ) );

            switch ( $action ) {

                case 'delete' :

                    if ( isset( $_GET['customer_id'] ) && !empty( $_GET['customer_id'] ) ) {
                        erp_crm_customer_delete( $_GET['customer_id'], false );
                    }

                    wp_redirect( $redirect );
                    exit();

                case 'permanent_delete' :
                    if ( isset( $_GET['customer_id'] ) && !empty( $_GET['customer_id'] ) ) {
                        erp_crm_customer_delete( $_GET['customer_id'], true );
                    }

                    wp_redirect( $redirect );
                    exit();

                case 'restore' :
                    if ( isset( $_GET['customer_id'] ) && !empty( $_GET['customer_id'] ) ) {
                        erp_crm_customer_restore( $_GET['customer_id'] );
                    }

                    wp_redirect( $redirect );
                    exit();

                case 'filter_life_stage':
                    $redirect = remove_query_arg( [ 'filter_customer' ], $redirect );
                    wp_redirect( $redirect );
                    exit();

                default:
                    wp_redirect( $redirect );
                    exit();

            }
        }
    }

    /**
     * Handle contact subscriber bulk actions
     *
     * @since 1.0
     *
     * @return void
     */
    public function contact_subscriber_bulk_action() {

        if ( ! isset( $_REQUEST['_wpnonce'] ) || ! isset( $_GET['page'] ) || ! isset( $_GET['groupaction'] ) ) {
            return;
        }

        if ( $_GET['page'] != 'erp-sales-contact-groups' ) {
            return;
        }

        if ( $_GET['groupaction'] != 'view-subscriber' ) {
            return;
        }

        if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-contactsubscribers' ) ) {
            return;
        }


        $customer_table = new \WeDevs\ERP\CRM\Contact_Subscriber_List_Table();
        $action         = $customer_table->current_action();

        if ( $action ) {

            $redirect = remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'filter_group' ), wp_unslash( $_SERVER['REQUEST_URI'] ) );

            switch ( $action ) {

                case 'filter_group':
                    wp_redirect( $redirect );
                    exit();

                case 'delete':

                    if ( isset( $_GET['suscriber_contact_id'] ) && !empty( $_GET['suscriber_contact_id'] ) ) {
                        erp_crm_contact_subscriber_delete( $_GET['suscriber_contact_id'] );
                    }

                    wp_redirect( $redirect );
                    exit();

                default:
                    wp_redirect( $redirect );
                    exit();

            }
        }

    }

}