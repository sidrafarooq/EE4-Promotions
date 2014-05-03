<?php
/**
 * This file contains the Promotions_Admin_List_Table class
 *
 * @since 1.0.0
 * @package EE Promotions
 * @subpackage admin
 */
if ( ! defined( 'EVENT_ESPRESSO_VERSION' )) { exit('NO direct script access allowed'); }

/**
 * Defines the list table for the EE promotions system.
 *
 * @since 1.0.0
 * @see EE_Admin_List_Table for code documentation
 *
 * @package EE4 Promotions
 * @subpackage admin
 * @author Darren Ethier
 */
class Promotions_Admin_List_Table extends EE_Admin_List_Table {


	protected function _setup_data() {
		$this->_data = $this->_get_promotions( $this->_per_page );
		$this->_all_data_count = $this->_get_promotions( $this->_per_page, TRUE );
	}



	protected function _set_properties() {
		$this->_wp_list_args = array(
			'singular' => __('Promotion', 'event_espresso'),
			'plural' => __('Promotion', 'event_espresso'),
			'ajax' => TRUE,
			'screen' => $this->_admin_page->get_current_screen()->id
			);

		$this->_columns = array(
			'cb' => '<input type="checkbox" />',
			'id' => __('ID', 'event_espresso'),
			'name' => __('Name', 'event_espresso'),
			'code' => __('Code', 'event_espresso'),
			'applies_to' => __('Applies To', 'event_espresso'),
			'valid_from' => __('Valid From', 'event_espresso'),
			'valid_until' => __('Valid Until', 'event_espresso'),
			'amount' => __('Amount', 'event_espresso'),
			'redeemed' => __('Redeemed', 'event_espresso'),
			'actions' => __('Actions', 'event_espresso')
			);

		$this->_sortable_columns = array(
			'id' => array( 'id' => TRUE ),
			'name' => array( 'name' => false ),
			'code' => array( 'code' => false ),
			'valid_from' => array( 'code' => false ),
			'valid_until' => array( 'valid_until' => false ),
			'redeemed' => array( 'redeemed' => false )
			);

		$this->_hidden_columns = array();
	}



	protected function _get_table_filters() {
		return array();
	}



	protected function _add_view_counts() {
		$this->_views['all']['count'] = $this->_all_data_count;
		//$this->_views['trash']['count'] = $this->_trashed_count();
	}



	public function column_cb() {}
	public function column_id() {}
	public function column_name() {}
	public function column_code() {}
	public function column_applies_to() {}
	public function column_valid_from() {}
	public function column_valid_until() {}
	public function column_amount() {}
	public function column_redeemed() {}
	public function column_actions() {}




	//todo
	protected function _get_promotions( $per_page = 10, $count = FALSE ) {
		$_orderby = !empt( $this->_req_data['orderby'] ) ? $this->_req_data['orderby'] : '';
		switch( $_orderby ) {
			case 'name' :
				$orderby = 'Price.PRC_name';
				break;

			case 'code' :
				$orderby = 'PRO_code';
				break;

			case 'valid_from' :
				$orderby = 'PRO_start';
				break;

			case 'valid_until' :
				$orderby = 'PRO_end';
				break;

			case 'redeemed' :
				$orderby = 'Promotion_Object.POB_used';
				break;
			default :
				$orderby = 'PRO_ID';
				break;
		}

		$sort = ( ! empty( $this->_req_data['order'] ) ) ? $this->_req_data['order'] : 'ASC';
		$current_page = ! empty( $this->_req_data['paged'] ) ? $this->_req_data['paged'] : 1;
		$per_page = ! empty( $per_page ) ? $per_page : 10;
		$per_page =  ! empty( $this->_req_data['perpage'] ) ? $this->_req_data['perpage'] : $per_page;

		$offset = ( $current_page - 1 ) * $per_page;
		$limit = array( $offset, $per_page );

		$promotions = $count ? EEM_Promotion::instance()->count() : EEM_Promotion::instance()->get_all( array( 'limit' => $limit, 'order_by' => $orderby, 'order' => $sort ) );
	}



	//not in use because promotions isn't a soft delete model currently.
	protected function _trashed_count() {
		return 0;
	}
}
