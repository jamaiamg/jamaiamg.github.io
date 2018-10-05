<?php
/*
Plugin Name: Bangla Web Fonts
Plugin URI: http://xhostbd.com/
Description: Enables bangla web fonts.
Author: M.A. IMRAN
Version: 1.2
Author URI: http://facebook.com/imran2w
*/

/*
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or ( at your option) any later version. This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of ERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details. You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA. Online: http://www.gnu.org/licenses/gpl.txt;
*/

// Bismillah...

	defined( 'ABSPATH' ) or die( 'Stop! You can not do this!' );
	
	function embed_bangla_font() {
		print(
			'<link href="'.plugins_url( 'solaiman-lipi/font.css', __FILE__ ).'" rel="stylesheet">'
		);
		
	?>
		<style>
			body, h1, h2, h3, h4, h5, h6, textarea, input, select, .topbar, .main-menu, .breadcrumb, .copyrights-area {
				font-family: 'SolaimanLipi', Arial, sans-serif !important;
			}
		</style>
	<?php
	}
	
	add_action('wp_head', 'embed_bangla_font');
	
	?>
