<?php
/*
	Copyright 2016 Axelerant

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

require_once EDD_SR_DIR_LIB . 'aihrus-framework/aihrus-framework.php';


function eddsr_requirements_check( $force_check = false ) {
	$check_okay = get_transient( 'eddsr_requirements_check' );
	if ( empty( $force_check ) && false !== $check_okay ) {
		return $check_okay;
	}

	$deactivate_reason = false;
	if ( ! function_exists( 'aihr_check_aihrus_framework' ) ) {
		$deactivate_reason = esc_html__( 'Missing Aihrus Framework', 'edd-sales-recovery' );
		add_action( 'admin_notices', 'eddsr_notice_aihrus' );
	} elseif ( ! aihr_check_aihrus_framework( EDD_SR_BASE, EDD_SR_NAME, EDD_SR_AIHR_VERSION ) ) {
		$deactivate_reason = esc_html__( 'Old Aihrus Framework version detected', 'edd-sales-recovery' );
	}

	if ( ! aihr_check_php( EDD_SR_BASE, EDD_SR_NAME ) ) {
		$deactivate_reason = esc_html__( 'Old PHP version detected', 'edd-sales-recovery' );
	}

	if ( ! aihr_check_wp( EDD_SR_BASE, EDD_SR_NAME ) ) {
		$deactivate_reason = esc_html__( 'Old WordPress version detected', 'edd-sales-recovery' );
	}

	if ( ! class_exists( EDD_SR_REQ_CLASS ) ) {
		$deactivate_reason = sprintf( esc_html__( '%1$s not activated', 'edd-sales-recovery' ), EDD_SR_REQ_NAME );
		add_action( 'admin_notices', 'eddsr_notice_version' );
	}

	if ( ! empty( $deactivate_reason ) ) {
		aihr_deactivate_plugin( EDD_SR_BASE, EDD_SR_NAME, $deactivate_reason );
	}

	$check_okay = empty( $deactivate_reason );
	if ( $check_okay ) {
		delete_transient( 'eddsr_requirements_check' );
		set_transient( 'eddsr_requirements_check', $check_okay, HOUR_IN_SECONDS );
	}

	return $check_okay;
}


function eddsr_notice_version() {
	aihr_notice_version( EDD_SR_REQ_NAME, EDD_SR_REQ_VERSION, EDD_SR_NAME );
}


function eddsr_notice_aihrus() {
	$help_url  = esc_url( 'https://axelerant.atlassian.net/wiki/display/WPFAQ/Axelerant+Framework+Out+of+Date' );
	$help_link = sprintf( __( '<a href="%1$s">Update plugins</a>. <a href="%2$s">More information</a>.', 'edd-sales-recovery' ), self_admin_url( 'update-core.php' ), $help_url );

	$text = sprintf( esc_html__( 'Plugin "%1$s" has been deactivated as it requires a current Aihrus Framework. Once corrected, "%1$s" can be activated. %2$s', 'edd-sales-recovery' ), EDD_SR_NAME, $help_link );

	aihr_notice_error( $text );
}


function eddsr_notice_not_enabled() {
	$help_link = EDD_Sales_Recovery::$settings_link;

	$text = sprintf( esc_html__( '%1$s isn\'t possible until at least one attempt type, like "Initial Attempt", is enabled in %2$s.', 'edd-sales-recovery' ), EDD_SR_NAME, $help_link );

	aihr_notice_error( $text );
}

?>