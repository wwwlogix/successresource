<?php

// Avoid direct calls to this file where wp core files not present
if ( ! function_exists ('add_action')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

$auto_open = FALSE;
$first_tab = FALSE;
$first_tab_title = FALSE;

class US_Shortcodes {

	/**
	 * @var array List of shortcode buttons, used in TinyMCEs
	 */
	public static $buttons = array(
		'columns',
		'gallery',
		'slider',
		'separator_btn',
		'button_btn',
		'tabs',
		'accordion',
		'iconbox',
		'testimonial',
		'team',
		'portfolio',
		'blog',
		'clients',
		'actionbox',
		'video',
		'message',
		'counter',
		'contact_form',
		'pricing_table',
		'social_links',
		'gmaps',
	);

	/**
	 * @var {String} Template directory
	 */
	protected $_template_directory;

	public function __construct()
	{
		add_filter('the_content', array($this, 'paragraph_fix'));
		add_filter('the_content', array($this, 'a_to_img_magnific_pupup'));

		if ( ! us_is_vc_fe()) {
			add_filter('the_content', array($this, 'sections_fix'), 99);
		}

		add_shortcode('item_title', array($this, 'item_title'));

		add_shortcode('timepoint_title', array($this, 'timepoint_title'));

//		add_shortcode('us_sharing', array($this, 'us_sharing'));

		add_shortcode('vc_iconbox', array($this, 'vc_iconbox'));
		add_shortcode('vc_testimonial', array($this, 'vc_testimonial'));

		add_shortcode('vc_blog', array($this, 'vc_blog'));
		add_shortcode('vc_portfolio', array($this, 'vc_portfolio'));
		add_shortcode('vc_clients', array($this, 'vc_clients'));
		add_shortcode('vc_latest_posts', array($this, 'vc_latest_posts'));
		add_shortcode('vc_careers', array($this, 'vc_careers'));
		add_shortcode('vc_annualreport', array($this, 'vc_annualreport'));
		add_shortcode('vc_mapslider', array($this, 'vc_mapslider'));
		
		add_shortcode('vc_member', array($this, 'vc_member'));

		add_shortcode('vc_actionbox', array($this, 'vc_actionbox'));

		add_shortcode('pricing_table', array($this, 'pricing_table'));
		add_shortcode('pricing_column', array($this, 'pricing_column'));
		add_shortcode('pricing_row', array($this, 'pricing_row'));
		add_shortcode('pricing_footer', array($this, 'pricing_footer'));

		add_shortcode('vc_contact_form', array($this, 'vc_contact_form'));
		add_shortcode('vc_social_links', array($this, 'vc_social_links'));
		add_shortcode('vc_contacts', array($this, 'vc_contacts'));

		add_shortcode('vc_counter', array($this, 'vc_counter'));

		remove_shortcode('gallery');
		add_shortcode('gallery', array($this, 'gallery'));
		add_shortcode('vc_simple_slider', array($this, 'vc_simple_slider'));
		add_shortcode('vc_grid_blog_slider', array($this, 'vc_grid_blog_slider'));

		//VC shortcodes
		if ( ! class_exists('WPBakeryVisualComposerAbstract')) {
			add_shortcode('vc_row', array($this, 'vc_row'));
			add_shortcode('vc_row_inner', array($this, 'vc_row'));
			add_shortcode('vc_column', array($this, 'vc_column'));
			add_shortcode('vc_column_inner', array($this, 'vc_column'));
			add_shortcode('vc_column_text', array($this, 'vc_column_text'));
			add_shortcode('vc_separator', array($this, 'vc_separator'));
			add_shortcode('vc_button', array($this, 'vc_button'));
			add_shortcode('vc_video', array($this, 'vc_video'));
			add_shortcode('vc_gmaps', array($this, 'vc_gmaps'));
			add_shortcode('vc_accordion', array($this, 'vc_accordion'));
			add_shortcode('vc_accordion_tab', array($this, 'vc_accordion_tab'));
			add_shortcode('vc_tabs', array($this, 'vc_tabs'));
			add_shortcode('vc_tab', array($this, 'vc_tab'));
			add_shortcode('vc_gallery', array($this, 'vc_gallery'));
			add_shortcode('vc_single_image', array($this, 'vc_single_image'));
			add_shortcode('vc_message', array($this, 'vc_message'));
			add_shortcode('rev_slider_vc', array($this, 'rev_slider_vc'));

			add_shortcode('vc_icon', array($this, 'vc_icon'));
		}

		$this->_template_directory = get_template_directory();
	}

	public function rev_slider_vc($atts, $content = null) {
		ob_start();
		include($this->_template_directory.'/vc_templates/rev_slider_vc.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function vc_row($atts, $content = null) {
		ob_start();
		include($this->_template_directory.'/vc_templates/vc_row.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function vc_column($atts, $content = null) {
		ob_start();
		include($this->_template_directory.'/vc_templates/vc_column.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function vc_column_text($atts, $content = null) {
		ob_start();
		include($this->_template_directory.'/vc_templates/vc_column_text.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function vc_accordion($atts, $content = null) {
		ob_start();
		include($this->_template_directory.'/vc_templates/vc_accordion.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function vc_accordion_tab($atts, $content = null) {
		ob_start();
		include($this->_template_directory.'/vc_templates/vc_accordion_tab.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function vc_tabs($atts, $content = null) {
		ob_start();
		include($this->_template_directory.'/vc_templates/vc_tabs.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function vc_tab($atts, $content = null) {
		ob_start();
		include($this->_template_directory.'/vc_templates/vc_tab.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function vc_gallery($atts, $content = null) {
		ob_start();
		include($this->_template_directory.'/vc_templates/vc_row.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function vc_single_image($atts, $content = null) {
		ob_start();
		include($this->_template_directory.'/vc_templates/vc_single_image.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function vc_message($atts, $content = null) {
		ob_start();
		include($this->_template_directory.'/vc_templates/vc_message.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function vc_separator($atts, $content = null) {
		ob_start();
		include($this->_template_directory.'/vc_templates/vc_separator.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function vc_button($atts, $content = null) {
		ob_start();
		include($this->_template_directory.'/vc_templates/vc_button.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function vc_video($atts, $content = null) {
		ob_start();
		include($this->_template_directory.'/vc_templates/vc_video.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function vc_gmaps($atts, $content = null) {
		ob_start();
		include($this->_template_directory.'/vc_templates/vc_gmaps.php');
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}

	public function paragraph_fix($content)
	{
		$array = array (
			'<p>[' => '[',
			']</p>' => ']',
			']<br />' => ']',
			']<br>' => ']',
		);

		$content = strtr($content, $array);
		return $content;
	}

	public function sections_fix($content)
	{
		remove_shortcode('section');
		$link_pages_args = array(
			'before'           => '<div class="w-blog-pagination"><nav class="navigation pagination" role="navigation">',
			'after'            => '</nav></div>',
			'next_or_number'   => 'next_and_number',
			'nextpagelink'     => '>',
			'previouspagelink' => '<',
			'link_before'      => '<span>',
			'link_after'       => '</span>',
			'echo'             => 0,
		);

		global $disable_section_shortcode;

		if ($disable_section_shortcode)
		{
			add_shortcode('section', array($this, 'section_dummy'));
			$content = $content.us_wp_link_pages($link_pages_args);
			return do_shortcode($content);
		}

		add_shortcode('section', array($this, 'section'));

		if (strpos($content, '[section') !== FALSE)
		{
			$content = strtr($content, array('[section' => '[/section automatic_end_section="1"][section'));
			$content = strtr($content, array('[/section]' => '[/section][section]'));
			$content = strtr($content, array('[/section automatic_end_section="1"]' => '[/section]'));
			$content = '[section]'.$content.us_wp_link_pages($link_pages_args).'[/section]';
		}
		else
		{
			$content = '[section]'.$content.us_wp_link_pages($link_pages_args).'[/section]';
		}

		$content = preg_replace('%\[section\](\\s)*\[/section\]%i', '', $content);

		return do_shortcode($content);
	}

	public function a_to_img_magnific_pupup ($content)
	{
		$pattern = "/<a(.*?)href=('|\")([^>]*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
		$replacement = '<a$1ref="magnificPopup" href=$2$3.$4$5$6>';
		$content = preg_replace($pattern, $replacement, $content);

		return $content;
	}

	public function vc_contact_form($atts, $content = null)
	{
		global $smof_data;

		$atts = shortcode_atts(
			array(
				'form_name_field' => 'required',
				'form_email_field' => 'required',
				'form_phone_field' => 'required',
				'form_message_field' => 'required',
				'form_email' => '',
				'form_captcha' => '',
				'captcha_salt' => 'wwwlogix',
				'button_type' => '',
				'button_outlined' => false,
				'button_color' => 'primary',
				'button_align' => '',
				'button_size' => '',
				'el_class' => '',

			), $atts);

		$btn_color_class = (isset($atts['button_color']) AND $atts['button_color'] != '') ? ' color_'.$atts['button_color'] : '';
		$btn_align_class = (isset($atts['button_align']) AND $atts['button_align'] != '') ? ' align_'.$atts['button_align'] : '';
		$btn_outlined_class = (isset($atts['button_outlined']) AND ($atts['button_outlined'] == 1 OR $atts['button_outlined'] == 'yes')) ? ' outlined' : '';
		$btn_size_class = ($atts['button_size'] != '') ? ' size_'.$atts['button_size'] : '';
		$btn_text = (isset($smof_data['contact_form_button_text']) AND $smof_data['contact_form_button_text'] != '') ? $smof_data['contact_form_button_text'] : __('Send Message', 'us');

		if ($atts['el_class'] != '') {
			$atts['el_class'] = ' '.$atts['el_class'];
		}

		$output = 	'<div class="w-form'.$btn_align_class.$atts['el_class'].'">
						<form action="" method="post" id="contact_form" class="contact_form">';
		$output .= '<input type="hidden" name="post_id" value="'.get_the_ID().'">';
		if (isset($atts['form_name_field']) AND ($atts['form_name_field'] == 'required' OR $atts['form_name_field'] == 'show')) {
			$name_required = (isset($atts['form_name_field']) AND $atts['form_name_field'] == 'required') ? 1 : 0;
			$name_required_label = '';
			if ($name_required) {
				$name_required_label = ' *';
			}
			$output .= 	'<div class="w-form-row" id="name_row">
							<div class="w-form-label">
								<label for="name">'.__('Your name', 'us').$name_required_label.'</label>
							</div>
							<div class="w-form-field">
								<input id="name" type="text" name="name" data-required="'.$name_required.'" placeholder="'.__('Your name', 'us').$name_required_label.'">
								<i class="fa fa-user"></i>
							</div>
							<div class="w-form-state" id="name_state"></div>
						</div>';
		}

		if (isset($atts['form_email_field']) AND ($atts['form_email_field'] == 'required' OR $atts['form_email_field'] == 'show')) {
			$email_required = (isset($atts['form_email_field']) AND $atts['form_email_field'] == 'required') ? 1 : 0;
			$email_required_label = $email_required ? ' *' : '';
			$output .= '<div class="w-form-row" id="email_row">
							<div class="w-form-label">
								<label for="email">'.__('Email', 'us').$email_required_label.'</label>
							</div>
							<div class="w-form-field">
								<input id="email" type="email" name="email" data-required="'.$email_required.'" placeholder="'.__('Email', 'us').$email_required_label.'">
								<i class="fa fa-envelope"></i>
							</div>
							<div class="w-form-state" id="email_state"></div>
						</div>';
		}
		if (isset($atts['form_phone_field']) AND ($atts['form_phone_field'] == 'required' OR $atts['form_phone_field'] == 'show')) {
			$phone_required = (isset($atts['form_phone_field']) AND $atts['form_phone_field'] == 'required') ? 1 : 0;
			$phone_required_label = $phone_required ? ' *' : '';
			$output .= 	'<div class="w-form-row" id="phone_row">
							<div class="w-form-label">
								<label for="phone">'.__('Phone Number', 'us').$phone_required_label.'</label>
							</div>
							<div class="w-form-field">
								<input id="phone" type="text" name="phone" data-required="'.$phone_required.'" placeholder="'.__('Phone Number', 'us').$phone_required_label.'">
								<i class="fa fa-phone"></i>
							</div>
							<div class="w-form-state" id="phone_state"></div>
						</div>';
		}

		if (isset($atts['form_message_field']) AND ($atts['form_message_field'] == 'required' OR $atts['form_message_field'] == 'show')) {
			$message_required = (isset($atts['form_message_field']) AND $atts['form_message_field'] == 'required') ? 1 : 0;
			$message_required_label = $message_required ? ' *' : '';
			$output .= 	'<div class="w-form-row" id="message_row">
							<div class="w-form-label">
								<label for="message">'.__('Message', 'us').$message_required_label.'</label>
							</div>
							<div class="w-form-field">
								<textarea id="message" name="message" cols="30" rows="10" data-required="'.$message_required.'" placeholder="'.__('Message', 'us').$message_required_label.'"></textarea>
								<i class="fa fa-pencil"></i>
							</div>
							<div class="w-form-state" id="message_state"></div>
						</div>';
		}
		if ($atts['form_captcha'] == 'show') {
			$first_num = rand(0, 19);
			$second_num = rand(0, 19);
			$sign = rand(0,1);
			if ($sign) {
				$result = $first_num+$second_num;
				$equation = $first_num.' + '.$second_num;
			} else {
				if ($first_num < $second_num){
					$first_num = $first_num+$second_num;
					$second_num = $first_num-$second_num;
					$first_num = $first_num-$second_num;
				}
				$result = $first_num-$second_num;
				$equation = $first_num.' - '.$second_num;
			}
			$output .= '<div class="w-form-row" id="captcha_row">
							<div class="w-form-label">
								<label for="contact_captcha">'.__('Just to prove you are a human, please solve the equation: ', 'us').' '.$equation.'</label>
							</div>
							<div class="w-form-field">
								<input type="hidden" name="captcha_result" value="'.md5($result.$atts['captcha_salt']).'">
								<input type="text" name="captcha" value="">
							</div>
							<div class="w-form-state" id="captcha_state"></div>
						</div>';
		}

		$output .= 			'<div class="w-form-row for_submit">
								<div class="w-form-field">
									<button class="g-btn '.$btn_color_class.$btn_outlined_class.$btn_size_class.'" id="message_send"><i class="fa fa-spinner fa-spin"></i><span>'.$btn_text.'</span></button>
									<div class="w-form-field-success"></div>
								</div>
							</div>
						</form>
					</div>';

		return $output;
	}

	public function vc_social_links($attributes, $content = null)
	{
		$attributes = shortcode_atts(
			array(
				'size' => '',
				'align' => '',
				'email' => '',
				'facebook' => '',
				'twitter' => '',
				'google' => '',
				'pinterest' => '',
				'linkedin' => '',
				'youtube' => '',
				'vimeo' => '',
				'flickr' => '',
				'instagram' => '',
				'behance' => '',
				'xing' => '',
				'skype' => '',
				'tumblr' => '',
				'dribbble' => '',
				'vk' => '',
				'soundcloud' => '',
				'yelp' => '',
				'twitch' => '',
				'deviantart' => '',
				'foursquare' => '',
				'github' => '',
				'rss' => '',
				'el_class' => '',
			), $attributes);

		$socials = array (
			'email' => 'Email',
			'facebook' => 'Facebook',
			'twitter' => 'Twitter',
			'google' => 'Google+',
			'pinterest' => 'Pinterest',
			'linkedin' => 'LinkedIn',
			'youtube' => 'YouTube',
			'vimeo' => 'Vimeo',
			'flickr' => 'Flickr',
			'instagram' => 'Instagram',
			'behance' => 'Behance',
			'xing' => 'Xing',
			'skype' => 'Skype',
			'tumblr' => 'Tumblr',
			'dribbble' => 'Dribbble',
			'vk' => 'Vkontakte',
			'soundcloud' => 'SoundCloud',
			'yelp' => 'Yelp',
			'twitch' => 'Twitch',
			'deviantart' => 'DeviantArt',
			'foursquare' => 'Foursquare',
			'github' => 'GitHub',
			'rss' => 'RSS',
		);

		$size_class = ($attributes['size'] != '')?' size_'.$attributes['size']:'';
		$align_class = ($attributes['align'] != '')?' align_'.$attributes['align']:'';
		if ($attributes['el_class'] != '') {
			$attributes['el_class'] = ' '.$attributes['el_class'];
		}

		$output = '<div class="w-socials'.$size_class.$align_class.$attributes['el_class'].'">
			<div class="w-socials-list">';

		foreach ($socials as $social_key => $social)
		{
			if ($attributes[$social_key] != '')
			{
				if ($social_key == 'email')
				{
					$output .= '<div class="w-socials-item '.$social_key.'">
					<a class="w-socials-item-link" href="mailto:'.$attributes[$social_key].'">
						<i class="fa fa-envelope"></i>
					</a>
					<div class="w-socials-item-popup">
						<span>'.$social.'</span>
					</div>
					</div>';

				}
				elseif ($social_key == 'google')
				{
					$output .= '<div class="w-socials-item gplus">
					<a class="w-socials-item-link" target="_blank" href="'.$attributes[$social_key].'">
						<i class="fa fa-google-plus"></i>
					</a>
					<div class="w-socials-item-popup">
						<span>'.$social.'</span>
					</div>
					</div>';

				}
				elseif ($social_key == 'youtube')
				{
					$output .= '<div class="w-socials-item '.$social_key.'">
					<a class="w-socials-item-link" target="_blank" href="'.$attributes[$social_key].'">
						<i class="fa fa-youtube-play"></i>
					</a>
					<div class="w-socials-item-popup">
						<span>'.$social.'</span>
					</div>
					</div>';

				}
				elseif ($social_key == 'vimeo')
				{
					$output .= '<div class="w-socials-item '.$social_key.'">
					<a class="w-socials-item-link" target="_blank" href="'.$attributes[$social_key].'">
						<i class="fa fa-vimeo-square"></i>
					</a>
					<div class="w-socials-item-popup">
						<span>'.$social.'</span>
					</div>
					</div>';

				}
				else
				{
					$output .= '<div class="w-socials-item '.$social_key.'">
					<a class="w-socials-item-link" target="_blank" href="'.$attributes[$social_key].'">
						<i class="fa fa-'.$social_key.'"></i>
					</a>
					<div class="w-socials-item-popup">
						<span>'.$social.'</span>
					</div>
					</div>';
				}

			}
		}

		$output .= '</div></div>';

		return $output;
	}

	public function vc_contacts($attributes, $content = null)
	{
		$attributes = shortcode_atts(
			array(
				'phone' => '',
				'fax' => '',
				'email' => '',
				'address' => '',
				'el_class' => '',
			), $attributes);

		if ($attributes['el_class'] != '') {
			$attributes['el_class'] = ' '.$attributes['el_class'];
		}
		$output = 	'<div class="w-contacts'.$attributes['el_class'].'">
						<div class="w-contacts-h">
							<div class="w-contacts-list">';
		if ($attributes['address'] != ''){
			$output .= 			'<div class="w-contacts-item">
									<i class="fa fa-map-marker"></i>
									<span class="w-contacts-item-value">'.$attributes['address'].'</span>
								</div>';
		}
		if ($attributes['phone'] != ''){
			$output .= 			'<div class="w-contacts-item">
									<i class="fa fa-phone"></i>
									<span class="w-contacts-item-value">'.$attributes['phone'].'</span>
								</div>';
		}
		if ($attributes['fax'] != ''){
			$output .= 			'<div class="w-contacts-item">
									<i class="fa fa-print"></i>
									<span class="w-contacts-item-value">'.$attributes['fax'].'</span>
								</div>';
		}
		if ($attributes['email'] != ''){
			$output .= 			'<div class="w-contacts-item">
									<i class="fa fa-envelope"></i>
									<span class="w-contacts-item-value"><a href="mailto:'.$attributes['email'].'">'.$attributes['email'].'</a></span>
								</div>';
		}

		$output .= 			'</div>
						</div>
					</div>';

		return $output;
	}

	public function pricing_table($attributes, $content = null)
	{
		$attributes = shortcode_atts(
			array(
			), $attributes);

		$output = '<div class="w-pricing">'.do_shortcode($content).'</div>';

		return $output;
	}

	public function pricing_column($attributes, $content = null)
	{
		$attributes = shortcode_atts(
			array(
				'title' => '',
				'type' => '',
				'price' => '',
				'time' => '',
			), $attributes);

		$featured_class = ($attributes['type'] == 'featured')?' type_featured':'';

		$output = 	'<div class="w-pricing-item'.$featured_class.'"><div class="w-pricing-item-h">
						<div class="w-pricing-item-header">
							<div class="w-pricing-item-title">'.$attributes['title'].'</div>
							<div class="w-pricing-item-price">'.$attributes['price'].'<small>'.$attributes['time'].'</small></div>
						</div>
						<ul class="w-pricing-item-features">'.
						do_shortcode($content).
					'</div></div>';

		return $output;
	}

	public function pricing_row($attributes, $content = null)
	{
		$attributes = shortcode_atts(
			array(
			), $attributes);

		$output = 	'<li class="w-pricing-item-feature">'.do_shortcode($content).'</li>';

		return $output;

	}

	public function pricing_footer($attributes, $content = null)
	{
		$attributes = shortcode_atts(
			array(
				'url' => '',
				'type' => 'default',
				'outlined' => false,
				'size' => '',
				'icon' => '',
			), $attributes);

		if ($attributes['url'] == '') $attributes['url'] = 'javascript:void(0)';
		$outlined_class = ($attributes['outlined'] == 1 OR $attributes['outlined'] == 'yes')?' outlined':'';
		$output = 	'</ul>
					<div class="w-pricing-item-footer">
						<a class="w-pricing-item-footer-button g-btn'.$outlined_class;
		$output .= ($attributes['type'] != '')?' color_'.$attributes['type']:'';
		$output .= ($attributes['size'] != '')?' size_'.$attributes['size']:'';
		$output .= '" href="'.$attributes['url'].'"><span>'.do_shortcode($content).'</span></a>
					</div>';

		return $output;

	}

	public function timepoint_title($attributes, $content = null)
	{
		$attributes = shortcode_atts(
			array(
				'title' => '',
				'active' => false,
			), $attributes);

		global $first_tab_title, $auto_open;
		if ($auto_open) {
//			$active_class = ($first_tab_title)?' active':'';
			$first_tab_title = FALSE;
		} else {
			$active_class = ($attributes['open'])?' active':'';
		}

		$active_class = ($attributes['active'] == 1 OR $attributes['active'] == 'yes')?' active':'';

		$output = 	'<div class="w-timeline-item'.$active_class.'"><span class="w-timeline-item-title">'.$attributes['title'].'</span></div> ';

		return $output;
	}

	public function item_title($attributes, $content)
	{
		$attributes = shortcode_atts(
			array(
				'title' => '',
				'active' => false,
				'icon' => '',
			), $attributes);

		global $first_tab_title, $auto_open;
		if ($auto_open) {
			$active_class = ($first_tab_title)?' active':'';
			$first_tab_title = FALSE;
		} else {
			$active_class = ($attributes['open'])?' active':'';
		}

		$active_class = ($attributes['active'] == 1 OR $attributes['active'] == 'yes')?' active':'';

		$icon_class = ($attributes['icon'] != '')?' fa fa-'.$attributes['icon']:'';
		$item_icon_class = ($attributes['icon'] != '')?' with_icon':'';

		$output = 	'<div class="w-tabs-item'.$active_class.$item_icon_class.'">'.
						'<span class="w-tabs-item-icon'.$icon_class.'"></span>'.
						'<span class="w-tabs-item-title">'.$attributes['title'].'</span>'.
					'</div>';

		return $output;
	}

	public function vc_actionbox ($attributes, $content)
	{
		$attributes = shortcode_atts(
			array(
				'type' => 'grey',
				'bg_color' => '',
				'text_color' => '',
				'title' => 'ActionBox title',
				'title_size' => 'h2',
				'message' => '',
				'button1' => '',
				'link1' => '',
				'style1' => 'default',
				'outlined1' => false,
				'size1' => '',
				'icon1' => '',
				'target1' => '',
				'button2' => '',
				'link2' => '',
				'style2' => 'default',
				'outlined2' => false,
				'size2' => '',
				'icon2' => '',
				'target2' => '',
				'animate' => '',
				'el_class' => '',
			), $attributes);

		$animate_class = ($attributes['animate'] != '')?' animate_'.$attributes['animate']:'';

		$actionbox_controls_position_class = ' controls_aside';
		if ($attributes['el_class'] != '') {
			$attributes['el_class'] = ' '.$attributes['el_class'];
		}

		$item_style = '';
		if ($attributes['type'] == 'custom') {
			if ($attributes['bg_color'] != '') {
				$item_style .= 'background-color: '.$attributes['bg_color'].';';
			}
			if ($attributes['text_color'] != '') {
				$item_style .= ' color: '.$attributes['text_color'].';';
			}
		}

		if ($item_style != '') {
			$item_style = ' style="'.$item_style.'"';
		}

		$output = 	'<div class="w-actionbox color_'.$attributes['type'].$actionbox_controls_position_class.$animate_class.$attributes['el_class'].'"'.$item_style.'>'.
			'<div class="w-actionbox-text">';
		if ($attributes['title'] != '')
		{
			$output .= 			'<h3>'.html_entity_decode($attributes['title']).'</h3>';
		}
		if ($attributes['message'] != '')
		{
			$output .= 			'<p>'.html_entity_decode($attributes['message']).'</p>';
		}


		$output .=			'</div>'.
			'<div class="w-actionbox-controls">';

		if ($attributes['button1'] != '' AND $attributes['link1'] != '')
		{
			$colour_class = ($attributes['style1'] != '')?' color_'.$attributes['style1']:'';
			$size_class = ($attributes['size1'] != '')?' size_'.$attributes['size1']:'';
			$outlined_class = ($attributes['outlined1'] == 1 OR $attributes['outlined1'] == 'yes')?' outlined':'';
			$taget_part = ($attributes['target1'] == '_blank')?' target="_blank"':'';
			$icon_part = ($attributes['icon1'] != '')?'<i class="fa fa-'.$attributes['icon1'].'"></i>':'';
			$output .= '<a class="w-actionbox-button g-btn'.$size_class.$colour_class.$outlined_class.'" href="'.$attributes['link1'].'"'.$taget_part.'><span>'.$icon_part.$attributes['button1'].'</span></a>';
		}

		if ($attributes['button2'] != '' AND $attributes['link2'] != '')
		{
			$colour_class = ($attributes['style2'] != '')?' color_'.$attributes['style2']:'';
			$size_class = ($attributes['size2'] != '')?' size_'.$attributes['size2']:'';
			$outlined_class = ($attributes['outlined2'] == 1 OR $attributes['outlined2'] == 'yes')?' outlined':'';
			$taget_part = ($attributes['target2'] == '_blank')?' target="_blank"':'';
			$icon_part = ($attributes['icon2'] != '')?'<i class="fa fa-'.$attributes['icon2'].'"></i>':'';
			$output .= 			'<a class="w-actionbox-button g-btn'.$size_class.$colour_class.$outlined_class.'" href="'.$attributes['link2'].'"'.$taget_part.'><span>'.$icon_part.$attributes['button2'].'</span></a>';
		}

		$output .=			'</div>'.
			'</div>';
		return $output;
	}

	public function section ($attributes, $content)
	{
		$attributes = shortcode_atts(
			array(
				'background' => FALSE,
				'bg_color' => FALSE,
				'text_color' => FALSE,
				'img' => FALSE,
//				'bg_fade' => FALSE,
				'parallax' => FALSE,
				'parallax_bg_width' => FALSE,
				'parallax_speed' => FALSE,
				'full_width' => FALSE,
				'full_height' => FALSE,
				'full_screen' => FALSE,
				'vertical_centering' => FALSE,
				'class' => FALSE,
				'id' => FALSE,
				'video' => FALSE,
				'video_mp4' => FALSE,
				'video_ogg' => FALSE,
				'video_webm' => FALSE,
				'overlay' => FALSE,
				'section_overlay' => FALSE,

			), $attributes);

		$item_style = $item_custom_class = '';
		$output_type = ($attributes['background'] != '')?' color_'.$attributes['background']:'';
		if ($attributes['background'] == 'custom') {
			$output_type = '';

			if ($attributes['bg_color'] != '') {
				$item_style .= 'background-color: '.$attributes['bg_color'].';';
			}
			if ($attributes['text_color'] != '') {
				$item_style .= ' color: '.$attributes['text_color'].';';
			}
		}

		$full_width_type = ($attributes['full_width'] != '')?' full_width':'';
		$full_height_type = ($attributes['full_height'] != '')?' full_height':'';
		$full_screen_type = ($attributes['full_screen'] == 1)?' full_screen':'';
		$vertical_centering_type = ($attributes['vertical_centering'] == 1)?' valign_center':'';
		$fade_class = '';
//		$fade_class = ($attributes['bg_fade'] != '')?' bg_fade '.$attributes['bg_fade']:'';
		$background_tag = '';
		if ($attributes['img'] != '')
		{
//			$output_type = ' type_background';
			if (is_numeric($attributes['img']))
			{
				$img_id = preg_replace('/[^\d]/', '', $attributes['img']);
				$img = wp_get_attachment_image_src($img_id, 'full', 0);

				if ( $img != NULL )
				{
					$img = $img[0];
					$background_tag = '<div class="l-submain-img" style="background-image: url('.$img.');"></div>';
				}


			}
			else
			{
				$background_tag = '<div class="l-submain-img" style="background-image: url('.$attributes['img'].');"></div>';
			}

		}

		$parallax_class = '';
		$additional_class = ($attributes['class'] != '')?' '.$attributes['class']:'';
		$section_id = ($attributes['id'] != '')?$attributes['id']:'';
		$section_id_string = ($attributes['id'] != '')?' id="'.$attributes['id'].'"':'';
		$js_output = '';
		if ($attributes['parallax'] == 'vertical') {
			// We need vertical parallax script for this, but only once per page
			if ( ! wp_script_is('us-parallax', 'enqueued')){
				wp_enqueue_script('us-parallax');
			}
			if ($section_id_string == '') {
				$section_id = 'section_'.rand(99999, 999999);
				$section_id_string = ' id="'.$section_id.'"';
			}
			$parallax_class = ' parallax_ver';

			$js_output = "<script type='text/javascript'>jQuery(document).ready(function(){ jQuery('#".$section_id." .l-submain-img').parallax('50%', '".$attributes['parallax_speed']."'); });</script>";
		}
		elseif ($attributes['parallax'] == 'horizontal') {
			// We need horizontal parallax script for this, but only once per page
			if ( ! wp_script_is('us-hor-parallax', 'enqueued')){
				wp_enqueue_script('us-hor-parallax');
			}
			if ($section_id_string == '') {
				$section_id = 'section_'.rand(99999, 999999);
				$section_id_string = ' id="'.$section_id.'"';
			}
			$parallax_class = ' parallax_hor';
			if ($attributes['parallax_bg_width'] != '') {
				$parallax_class .= ' bgwidth_'.$attributes['parallax_bg_width'];
			}

			$js_output = "<script type='text/javascript'>jQuery(document).ready(function(){ jQuery('#".$section_id."').horparallax(); });</script>";
		}

		$video_html = $video_class = '';

		if ($attributes['video'] AND ($attributes['video_mp4'] != '' OR $attributes['video_ogg'] != '' OR $attributes['video_webm'] != '' )) {
			$video_class = ' with_video';
			$parallax_class = $js_output = '';
			$video_mp4_part = ($attributes['video_mp4'] != '')?'<source type="video/mp4" src="'.$attributes['video_mp4'].'"></source>':'';
			$video_ogg_part = ($attributes['video_ogg'] != '')?'<source type="video/ogg" src="'.$attributes['video_ogg'].'"></source>':'';
			$video_webm_part = ($attributes['video_webm'] != '')?'<source type="video/webm" src="'.$attributes['video_webm'].'"></source>':'';
			$video_poster_part = ($attributes['background'] != '')?' poster="'.$attributes['background'].'"':'';
			$video_img_part = ($attributes['background'] != '')?'<img src="'.$attributes['background'].'" alt="">':'';
			// We need mediaelement script for this, but only once per page
			if( ! wp_script_is('us-mediaelement', 'enqueued')){
				wp_enqueue_script('us-mediaelement');
			}
			$video_html = '<div class="l-submain-video"><video loop="loop" autoplay="autoplay" preload="auto"'.$video_poster_part.'>'.$video_mp4_part.$video_ogg_part.$video_webm_part.$video_img_part.'</video></div>';
		}

		$overlay_html = '';

		if ($item_style != '') {
			$item_style = ' style="'.$item_style.'"';
			$item_custom_class = ' color_custom';
		}

		if ( ! empty($attributes['section_overlay'])) {
			$overlay_html = '<div class="l-submain-overlay" style="background-color: '.$attributes['section_overlay'].';"></div>';
		} elseif ( ! empty($attributes['overlay']) AND in_array($attributes['overlay'], array('white_10', 'white_20', 'white_30', 'white_40', 'white_50', 'white_60', 'white_70', 'white_80', 'white_90', 'black_10', 'black_20', 'black_30', 'black_40', 'black_50', 'black_60', 'black_70', 'black_80', 'black_90', ))) {
			$overlay_html = '<div class="l-submain-overlay '.$attributes['overlay'].'"></div>';
		}

		$output =	'<div class="l-submain wpb_row'.$fade_class.$full_width_type.$full_height_type.$full_screen_type.$vertical_centering_type.$output_type.$parallax_class.$video_class.$additional_class.$item_custom_class.'"'.$section_id_string.$item_style.'>'.
						$background_tag.$video_html.$overlay_html.
						'<div class="l-submain-h g-html i-cf">'.
							do_shortcode($content).
						'</div>'.
					'</div>'.$js_output;

		return $output;
	}

	public function section_dummy ($attributes, $content)
	{
		$attributes = shortcode_atts(
			array(
				'type' => FALSE,
				'with' => FALSE,

			), $attributes);

		$output = 	'<div>'.do_shortcode($content).'</div>';

		return $output;
	}

	public function vc_icon($attributes, $content = null)
	{
		$attributes = shortcode_atts(
			array(
				'icon' => "",
				'color' => "",
				'size' => "",
				'with_circle' => false,
				'link' => "",
				'external' => false,
			), $attributes);

		$color_class = ($attributes['color'] != '')?' color_'.$attributes['color']:' color_text';
		$size_class = ($attributes['size'] != '')?' size_'.$attributes['size']:'';
		$with_circle_class = ($attributes['with_circle'] == 1 OR $attributes['with_circle'] == 'yes')?' type_solid':'';

		if ($attributes['link'] != '') {
			$link = $attributes['link'];
			$link_start = '<a class="w-icon-link" href="'.$link.'"';
			$link_start .= ($attributes['external'] == 1 OR $attributes['external'] == 'yes')?' target="_blank"':'';
			$link_start .= '>';
			$link_end = '</a>';
		}
		else
		{
			$link_start = '<span class="w-icon-link">';
			$link_end = '</span>';
		}

		$output = 	'<span class="w-icon'.$color_class.$size_class.$with_circle_class.'">
						'.$link_start.'
							<i class="fa fa-'.$attributes['icon'].'"></i>
						'.$link_end.'
					</span>';

		return $output;
	}

	public function us_sharing($attributes)
	{
		$attributes = shortcode_atts(
			array(
				'align' => '',
				'type' => '',
				'facebook' => false,
				'twitter' => false,
				'gplus' => false,
				'pintererst' => false,


			), $attributes);

		switch ($attributes['type']) {
			case 'outlined': $type_class = ' type_outlined'; break;
			case 'solid': $type_class = ' type_solid'; break;
			case 'fixed_left': $type_class = ' type_solid fixed_left'; break;
			case 'fixed_right': $type_class = ' type_solid fixed_right'; break;
			default: $type_class = ' type_simple'; break;
		}

		$output = '<div class="w-sharing'.$type_class.' align_center">
		<a class="w-sharing-item facebook" title="Share this" href="#">
			<span class="w-sharing-icon">
				<i class="fa fa-facebook"></i>
			</span>
			<span class="w-sharing-count">13</span>
		</a>
		<a class="w-sharing-item gplus" title="Share this" href="#">
			<span class="w-sharing-icon">
				<i class="fa fa-google-plus"></i>
			</span>
			<span class="w-sharing-count">2</span>
		</a>
		<a class="w-sharing-item twitter" title="Tweet this" href="#">
			<span class="w-sharing-icon">
				<i class="fa fa-twitter"></i>
			</span>
			<span class="w-sharing-count">0</span>
		</a>
		<a class="w-sharing-item pinterest" title="Pin this" href="#">
			<span class="w-sharing-icon">
				<i class="fa fa-pinterest"></i>
			</span>
			<span class="w-sharing-count">7</span>
		</a>
	</div>';
	}

	public function vc_iconbox($attributes, $content)
	{
		$attributes = shortcode_atts(
			array(
				'icon' => '',
				'img' => '',
				'title' => '',
				'with_circle' => false,
				'icon_style' => null,
				'size' => false,
				'link' => '',
				'iconpos' => 'top',
				'color' => 'primary',
				'external' => false,
				'bg_color' => false,
				'icon_color' => false,
				'el_class' => '',

			), $attributes);

		if (empty($attributes['icon_style']) AND ($attributes['with_circle'] == 1 OR $attributes['with_circle'] == 'yes')) {
			$attributes['icon_style'] = 'circle_outlined';
		}

		switch ($attributes['icon_style']) {
			case 'circle_solid': $icon_style_class = ' type_solid'; break;
			case 'circle_outlined': $icon_style_class = ' type_outlined'; break;
			default: $icon_style_class = ' type_default'; break;
		}

		$img_class = ($attributes['img'] != '')?' custom_img':'';
		$iconpos_class = ($attributes['iconpos'] != '')?' iconpos_'.$attributes['iconpos']:'';
		$size_class = ($attributes['size'] != '')?' size_'.$attributes['size']:'';
		$color_class = ($attributes['color'] != '')?' color_'.$attributes['color']:'';
		$no_text_class = ($attributes['title'] == '' AND $content == '')?' no_text':'';
		if ($attributes['el_class'] != '') {
			$attributes['el_class'] = ' '.$attributes['el_class'];
		}

		$item_style = '';
		if ($attributes['color'] == 'custom') {
			if ($attributes['bg_color'] != '') {
				$item_style .= 'background-color: '.$attributes['bg_color'].'; box-shadow: 0 0 0 2px '.$attributes['bg_color'].' inset;';
			}
			if ($attributes['icon_color'] != '') {
				$item_style .= ' color: '.$attributes['icon_color'].';';
			}

			if ($item_style != '') {
				$item_style = ' style="'.$item_style.'"';
			}
		}

		if ($attributes['link'] != '') {
			$link = $attributes['link'];
			$link_start = '<a class="w-iconbox-link" href="'.$link.'"';
			$link_start .= ($attributes['external'] == 1 OR $attributes['external'] == 'yes')?' target="_blank"':'';
			$link_start .= '>';
			$link_end = '</a>';
		}
		else
		{
			$link_start = '<span class="w-iconbox-nolink">';
			$link_end = '</span>';
		}

		$output =	'<div class="w-iconbox'.$no_text_class.$img_class.$iconpos_class.$size_class.$icon_style_class.$color_class
.$attributes['el_class'].'">
						'.$link_start.'
						<div class="w-iconbox-icon"'.$item_style.'>
							<i class="fa fa-'.$attributes['icon'].'"></i>';
		if ($attributes['img'] != '') {
			if (is_numeric($attributes['img']))
			{
				$img_id = preg_replace('/[^\d]/', '', $attributes['img']);
				$img = wp_get_attachment_image_src($img_id, 'full', 0);

				if ( $img != NULL )
				{
					$img = $img[0];
				}
			}
			else
			{
				$img =  $attributes['img'];
			}
			$output .=		'<img src="'.$img.'" alt="">';
		}
		$output .=	'	</div>';
		if ($attributes['title'] != '') {
			$output .=	'<h4 class="w-iconbox-title">'.$attributes['title'].'</h4>';
		}
		$output .=      $link_end;
		if ($content != '') {
			$output .=	'<div class="w-iconbox-text">
							<p>'.do_shortcode($content).'</p>
						</div>';
		}
		$output .=	'</div>';

		return $output;
	}

	public function vc_testimonial($attributes, $content)
	{
		$attributes = shortcode_atts(
			array(
				'author' => '',
				'company' => '',
				'animate' => '',
				'img' => '',
				'el_class' => '',

			), $attributes);

		$animate_class = ($attributes['animate'] != '')?' animate_'.$attributes['animate']:'';
		if ($attributes['el_class'] != '') {
			$attributes['el_class'] = ' '.$attributes['el_class'];
		}

		$img_tag = '<i class="fa fa-user"></i>';
		$img = '';

		if (is_numeric($attributes['img']))
		{
			$img_id = preg_replace('/[^\d]/', '', $attributes['img']);
			$img = wp_get_attachment_image_src($img_id, 'gallery-m', 0);

			if ( $img != NULL )
			{
				$img = $img[0];
			}
		}
		else
		{
			$img =  $attributes['img'];
		}

		if ( $img != '' )
		{
			$img_tag = '<img src="'.$img.'" alt="">'."\n";
		}

		$output = 	'<div class="w-testimonial'.$animate_class.$attributes['el_class'].'">
						<blockquote>
							<q class="w-testimonial-text">'.do_shortcode($content).'</q>
							<div class="w-testimonial-person">
								'.$img_tag.'
								<span class="w-testimonial-person-name">'.$attributes['author'].'</span>
								<span class="w-testimonial-person-meta">'.$attributes['company'].'</span>
							</div>
						</blockquote>
					</div>';

		return $output;
	}

	/*public function vc_member ($attributes, $content)
	{
		$attributes = shortcode_atts(
			array(
				'name' => '',
				'role' => '',
				'img' => '',
				'email' => '',
				'facebook' => '',
				'twitter' => '',
				'google_plus' => '',
				'linkedin' => '',
				'skype' => '',
				'custom_icon' => '',
				'custom_link' => '',
				'link' => '',
				'external' => '',
				'animate' => '',
				'el_class' => '',
			), $attributes);

		$animate_class = ($attributes['animate'] != '')?' animate_'.$attributes['animate']:'';
		if ($attributes['el_class'] != '') {
			$attributes['el_class'] = ' '.$attributes['el_class'];
		}

		if (is_numeric($attributes['img']))
		{
			$img_id = preg_replace('/[^\d]/', '', $attributes['img']);
			$img = wp_get_attachment_image_src($img_id, 'full', 0);

			if ( $img != NULL )
			{
				$img = $img[0];
			}
		}
		else
		{
			$img =  $attributes['img'];
		}

		if ( $img == NULL OR $img == '' )
		{
			$img = get_template_directory_uri().'/img/placeholder/500x500.gif';
		}

		$social_output = '';

		if ($attributes['facebook'] != '' OR $attributes['twitter'] != '' OR $attributes['google_plus'] != '' OR $attributes['linkedin'] != '' OR $attributes['email'] != '' OR $attributes['skype'] != '' OR ($attributes['custom_icon'] != '' AND $attributes['custom_link'] != ''))
		{
			$social_output .=		'<div class="w-team-links">'.
										'<div class="w-team-links-list">';
			if ($attributes['email'] != '')
			{
				$social_output .= 			'<a class="w-team-links-item" href="mailto:'.$attributes['email'].'"><i class="fa fa-envelope"></i></a>';
			}
			if ($attributes['facebook'] != '')
			{
				$social_output .= 			'<a class="w-team-links-item" href="'.$attributes['facebook'].'" target="_blank"><i class="fa fa-facebook"></i></a>';
			}
			if ($attributes['twitter'] != '')
			{
				$social_output .= 			'<a class="w-team-links-item" href="'.$attributes['twitter'].'" target="_blank"><i class="fa fa-twitter"></i></a>';
			}
			if ($attributes['google_plus'] != '')
			{
				$social_output .= 			'<a class="w-team-links-item" href="'.$attributes['google_plus'].'" target="_blank"><i class="fa fa-google-plus"></i></a>';
			}
			if ($attributes['linkedin'] != '')
			{
				$social_output .= 			'<a class="w-team-links-item" href="'.$attributes['linkedin'].'" target="_blank"><i class="fa fa-linkedin"></i></a>';
			}
			if ($attributes['skype'] != '')
			{
				$social_output .= 			'<a class="w-team-links-item" href="'.esc_url($attributes['skype']).'" target="_blank"><i class="fa fa-skype"></i></a>';
			}
			if ($attributes['custom_icon'] != '' AND $attributes['custom_link'] != '')
			{
				$social_output .= 			'<a class="w-team-links-item" href="'.esc_url($attributes['custom_link']).'" target="_blank"><i class="fa fa-'.$attributes['custom_icon'].'"></i></a>';
			}
			$social_output .=			'</div>'.
									'</div>';
		}

		$link_start = $link_end = '';

		if ($attributes['link'] != '') {
			$taget_part = ($attributes['external'] == 1 OR $attributes['external'] == 'yes')?' target="_blank"':'';
			$link_start = '<a class="w-team-link" href="'.$attributes['link'].'"'.$taget_part.'>';
			$link_end = '</a>';
		}

		$name_part = '';
		if ($attributes['name'] != '') {
			$name_part = $link_start.'<h4 class="w-team-name"><span>'.$attributes['name'].'</span></h4>'.$link_end;
		}

		$role_part = '';
		if ($attributes['role'] != '') {
			$role_part = '<div class="w-team-role">'.$attributes['role'].'</div>';
		}

		$content_part = '';
		if ($content != '') {
			$content_part = '<div class="w-team-description">
								<p>'.do_shortcode($content).'</p>
							</div>';
		}

		$output = 	'<div class="w-team'.$animate_class.$attributes['el_class'].'">
						<div class="w-team-image">
							'.$link_start.'<img src="'.$img.'" alt="'.$attributes['name'].'" />'.$link_end.'
							'.$social_output.'
						</div>
						<div class="w-team-content">
							'.$name_part.
							$role_part.$content_part.'
						</div>
					</div>';

		return $output;
	}
		*/
		public function vc_member ($attributes, $content)
	{
		$attributes = shortcode_atts(
			array(
				'name' => '',
				'role' => '',
				'img' => '',
				'limg' => '',
				'email' => '',
				'facebook' => '',
				'twitter' => '',
				'google_plus' => '',
				'linkedin' => '',
				'link' => '',
				'external' => '',
				'animate' => '',
			), $attributes);

		$animate_class = ($attributes['animate'] != '')?' animate_'.$attributes['animate']:'';

		if (is_numeric($attributes['img']))
		{
			$img_id = preg_replace('/[^\d]/', '', $attributes['img']);
			$img = wp_get_attachment_image_src($img_id, 'full', 0);

			if ( $img != NULL )
			{
				$img = $img[0];
			}
		}
		else
		{
			$img =  $attributes['img'];
		}
		if (is_numeric($attributes['limg']))
		{
			$limg_id = preg_replace('/[^\d]/', '', $attributes['limg']);
			$limg = wp_get_attachment_image_src($limg_id, 'full', 0);

			if ( $limg != NULL )
			{
				$limg = $limg[0];
			}
		}
		else
		{
			$limg =  $attributes['limg'];
		}
		
		if ( $img == NULL OR $img == '' )
		{
			$img = get_template_directory_uri().'/img/placeholder/500x500.gif';
		}
		if ( $limg == NULL OR $limg == '' )
		{
			$limg = get_template_directory_uri().'/img/team-biggerimage.png';
		}

		
		

		$output = 	'<div class="w-team'.$animate_class.'">
						<div class="w-team-image">
							<img src="'.$img.'" alt="'.$attributes['name'].'" />
							<div class="team-title">';
							$output .=  do_shortcode('[ultimate_modal icon_type="" modal_contain="ult-html" modal_on="text" onload_delay="2" btn_size="sm" btn_bg_color="#333333" btn_txt_color="#ffffff" modal_on_align="center" read_text="<div>'.$attributes['name'].'<p>'.$attributes['role'].'</p></div>" txt_color="#00aeef" modal_size="medium" modal_style="overlay-doorvertical" overlay_bg_color="#000" overlay_bg_opacity="85" header_text_color="#333333" modal_border_style="solid" modal_border_width="0" modal_border_color="" modal_border_radius="0" icon="Defaults-th-list" modal_title="" el_class="team-popups-modal"]<div class="g-cols wpb_row offset_default teams-popups-container"><div class="one-half"><img src="'.$limg.'" alt="'.$attributes['name'].'" /></div><div class="one-half team-left-content"><h2 class="team-popupname">'.$attributes['name'].'</h2><h3 class="team-popuprole">'.$attributes['role'].'</h3><p>'.do_shortcode($content).'</p></div></div> [/ultimate_modal]');
							$output .= '
							</div>
						</div>
						
					</div>';

		return $output;
	}

	public function vc_blog($attributes, $content)
	{
		if (is_admin()) {
			return '';
		}

		$attributes = shortcode_atts(
			array(
				'pagination' => '',
				'type' => 'large_image',
				'show_date' => null,
				'show_author' => null,
				'show_categories' => null,
				'show_tags' => null,
				'show_comments' => null,
				'show_read_more' => null,
				'category' => null,
				'items' => null,
				'post_content' => null,
				'columns' => null,
				'el_class' => '',
			), $attributes);

		$rand_id = rand(100000, 999999);

		// We need fotorama script for this, but only once per page
		if ( ! wp_script_is('us-fotorama', 'enqueued')){
			wp_enqueue_script('us-fotorama');
		}

		$blog_thumbnails = array(
			'large_image' => 'blog-large', 'small_square_image' => 'blog-small', 'small_circle_image' => 'blog-small','masonry_ajax' => 'blog-grid', 'masonry_paginated' => 'blog-grid'
		);

		if ( ! in_array($attributes['type'], array('large_image','small_square_image','small_circle_image','masonry_ajax','masonry_paginated')))
		{
			$attributes['type'] = 'large_image';
		}

		if ( ! in_array($attributes['columns'], array(1,2,3)))
		{
			$attributes['columns'] = 3;
		}
		if ($attributes['el_class'] != '') {
			$attributes['el_class'] = ' '.$attributes['el_class'];
		}

		global $paged;

		if (is_front_page()) {
			$page_string = 'page';
		} else {
			$page_string = 'paged';
		}

		if ($attributes['pagination'] == 1 OR $attributes['pagination'] == 'yes' OR $attributes['pagination'] == 'regular') {
			$paged = get_query_var($page_string) ? get_query_var($page_string) : 1;
		} else {
			$paged = 1;
		}

		$args = array(
			'post_type' 		=> 'post',
			'post_status' 		=> 'publish',
			'orderby' 			=> 'date',
			'order' 			=> 'DESC',
			'paged' 			=> $paged
		);

		$categories_slugs = null;

		if ( ! empty($attributes['category']))
		{
			$categories_slugs = explode(',', $attributes['category']);
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'category',
					'field' => 'slug',
					'terms' => $categories_slugs
				)
			);
		}

		$attributes['items'] = intval($attributes['items']);
		if (is_integer($attributes['items']) AND $attributes['items'] > 0) {
			$args['posts_per_page'] = $attributes['items'];
		} else {
			$attributes['items'] = 0;
		}

		$classes = 'w-blog columns_'.$attributes['columns'].$attributes['el_class'];

		switch ($attributes['type']) {
			case 'large_image':
				$classes .= ' imgpos_attop';
				break;
			case 'small_square_image':
				$classes .= ' imgpos_atleft';
				break;
			case 'small_circle_image':
				$classes .= ' imgpos_atleft imgtype_circle';
				break;
			case 'masonry':
			case 'masonry_ajax':
			case 'masonry_paginated':
				$classes .= ' type_masonry imgpos_attop';
				// We'll need the isotope script for this, but only once
				if ( ! wp_script_is('us-isotope', 'enqueued')){
					wp_enqueue_script('us-isotope');
				}
				break;
		}

		$output = '<div class="'.$classes.'" id="us_blog_'.$rand_id.'">
						<div class="w-blog-list">';

		global $wp_query;

		$wp_query = new WP_Query(); $wp_query->query($args);

		while ($wp_query->have_posts())
		{
			$wp_query->the_post();
			global $us_thumbnail_size, $post, $smof_data;
			$us_thumbnail_size = $blog_thumbnails[$attributes['type']];
			$icon_class = 'article-icon';
			$post_format = get_post_format()?get_post_format():'standard';

			if (empty($us_thumbnail_size))
			{
				$us_thumbnail_size = 'blog-grid';
			}


			if ($post_format == 'image')
			{
				$icon_class = 'gallery-icon';
				$preview = us_post_format_image_preview($us_thumbnail_size);
			}
			elseif ($post_format == 'gallery')
			{
				$icon_class = 'gallery-icon';
				$preview = (has_post_thumbnail())?get_the_post_thumbnail(get_the_ID(), $us_thumbnail_size):'';

				if ($preview == '') {
					if ($us_thumbnail_size == 'blog-small') {
						$preview = '<span class="w-blog-entry-preview-icon">
							<i class="fa fa-camera"></i>
						</span>';
					} else {
						$preview = us_post_format_gallery_preview(true, $us_thumbnail_size);
					}
				}
			}
			elseif ($post_format == 'video')
			{
				$icon_class = 'video-icon';
				$preview = (has_post_thumbnail())?get_the_post_thumbnail(get_the_ID(), $us_thumbnail_size):'';

				if ($preview == '') {
					if ($us_thumbnail_size == 'blog-small') {
						$preview = '<span class="w-blog-entry-preview-icon">
						<i class="fa fa-film"></i>
					</span>';
					} else {
						$preview = us_post_format_video_preview();
					}
				}

			}
			elseif ($post_format == 'quote')
			{
				$preview = (has_post_thumbnail())?get_the_post_thumbnail(get_the_ID(), $us_thumbnail_size):'';

				if ($preview == '' AND $us_thumbnail_size == 'blog-small') {
					$preview = '<span class="w-blog-entry-preview-icon">
						<i class="fa fa-quote-left"></i>
					</span>';
				}
			}
			else
			{
				$preview = (has_post_thumbnail())?get_the_post_thumbnail(get_the_ID(), $us_thumbnail_size):'';
			}

			if (empty($preview) AND $us_thumbnail_size == 'blog-small')
			{
				$preview = '<span class="w-blog-entry-preview-icon"><i class="fa fa-file-o"></i></span>';
			}
			$output .= '<div class="' . join( ' ', get_post_class( 'w-blog-entry', null ) ) . '">
				<div class="w-blog-entry-h">';
			if ($preview AND in_array($post_format, array('video', 'gallery'))) {
				$output .= '<span class="w-blog-entry-preview">'.$preview.'</span>';
			}
			if ($preview AND ! in_array($post_format, array('video', 'gallery'))) {
				$output .= '<span class="w-blog-entry-preview">'.$preview.'</span>';
			}
			
			$output .= '<div class="w-blog-meta-area">';
			   			$output .= '<div class="w-blog-meta">';
						$output .='<div class="'.$icon_class.'"></div>';
			if ($attributes['show_date'] == 1 OR $attributes['show_date'] == 'yes') {
				$output .= '<div class="w-blog-meta-date">
								
								<span>'.get_the_date().'</span>
							</div>';
			}
			if ($attributes['show_author'] == 1 OR $attributes['show_author'] == 'yes') {
				$output .= '<div class="w-blog-meta-author">
								<i class="fa fa-user"></i>';
				if (get_the_author_meta('url')) {
					$output .= '<a href="'.esc_url( get_the_author_meta('url') ).'">'.get_the_author().'</a>';
				} else {
					$output .= '<span>'.get_the_author().'</span>';
				}
				$output .= '</div>';
			}
			if ($attributes['show_categories'] == 1 OR $attributes['show_categories'] == 'yes') {
				$output .= '<div class="w-blog-meta-category">
								<i class="fa fa-folder-open"></i>';
				$categories = get_the_category();
				$categories_output = '';
				$separator = ', ';
				if($categories){
					foreach($categories as $category) {
						$categories_output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
					}
				}
				$output .= trim($categories_output, $separator).'
								</div>';
			}
			if ($attributes['show_tags'] == 1 OR $attributes['show_tags'] == 'yes') {
				$tags = wp_get_post_tags($post->ID);
				if ($tags) {
					$output .= '<div class="w-blog-meta-tags">
									<i class="fa fa-tags"></i>';

					$tags_output = '';
					$separator = ', ';
					foreach($tags as $tag) {
						$tags_output .= '<a href="'.get_tag_link($tag->term_id).'">'.$tag->name.'</a>'.$separator;
					}

					$output .= trim($tags_output, $separator).'
									</div>';
				}
			}
			if ($attributes['show_comments'] == 1 OR $attributes['show_comments'] == 'yes') {

				if ( ! (get_comments_number() == 0 AND ! comments_open() AND ! pings_open())) {
					$output .= '<div class="w-blog-meta-comments">';
					$output .= '<i class="fa fa-comments"></i>';
					$number = get_comments_number();

					if ( 0 == $number ) {
						$comments_link = get_permalink() . '#respond';
					}
					else {
						$comments_link = esc_url(get_comments_link());
					}

					$output .= '<a href="'.$comments_link.'" class="w-blog-entry-meta-comments-h">';


					if ( $number > 1 )
						$output .= str_replace('%', number_format_i18n($number), __('% Comments', 'us'));
					elseif ( $number == 0 )
						$output .= __('No Comments', 'us');
					else // must be one
						$output .= __('1 Comment', 'us');
					$output .= '</a></div>';
				}

			}
			$output .= '</div>';
				$output .= '</div>';
				
			
			$output .= '<div class="w-blog-content-area">';
					
					if ($post_format == 'quote')
			{
				$output .= '<div class="w-blog-entry-title">
				<blockquote class="w-blog-entry-title-h entry-title">'.get_the_title().'</blockquote>
				</div>';
			}
			else
			{
				$output .= '<h2 class="w-blog-entry-title">
				<span class="w-blog-entry-title-h entry-title">'.get_the_title().'</span>
				</h2>';
			}
			$output .='<div class="w-blog-entry-body">';

			if ($attributes['post_content'] != 'none') {
				$output .= '<div class="w-blog-entry-short">';

				global $disable_section_shortcode;
				$original_section_shortcode_state = $disable_section_shortcode;
				$disable_section_shortcode = TRUE;

				if ($attributes['post_content'] != 'full' OR $attributes['type'] == 'masonry_paginated') {
					$excerpt = $post->post_excerpt;
					if ($excerpt == '') {
						$excerpt = get_the_content();
						$excerpt = do_shortcode($excerpt);
						$excerpt = $this->sections_fix($excerpt);

						$excerpt = apply_filters('the_excerpt', $excerpt);
						$excerpt = str_replace(']]>', ']]&gt;', $excerpt);
						$excerpt_length = apply_filters('excerpt_length', 55);
						$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
						$excerpt = wp_trim_words( $excerpt, $excerpt_length, $excerpt_more );
					} else {
						$excerpt = apply_filters('the_excerpt', $excerpt);
						$excerpt = str_replace(']]>', ']]&gt;', $excerpt);
					}

					$output .= $excerpt;
				} else {

					$excerpt = $post->post_content;

					if ( preg_match( '/<!--more(.*?)?-->/', $excerpt, $matches ) ) {
						$excerpt = explode( $matches[0], $excerpt, 2 );
						$excerpt = $excerpt[0];
					}

					$excerpt = do_shortcode($excerpt);
					$excerpt = $this->sections_fix($excerpt);

					$excerpt = str_replace(']]>', ']]&gt;', $excerpt);

					$output .= $excerpt;


				}

				$disable_section_shortcode = $original_section_shortcode_state;

				$output .= '</div>';
			}

			if ($attributes['show_read_more'] == 1 OR $attributes['show_read_more'] == 'yes') {
				
				$content = get_the_content();
				
				$modalmeta = '<div class="w-blog-meta-area"><div class="w-blog-meta"><div class="'.$icon_class.'"></div>';

				$modalmeta .= '<div class="w-blog-meta-date">
								<span>'.get_the_date().'</span>
							</div></div></div>';
				
				$modaltitle  = '<div><h2 class="w-blog-entry-title">
				<span class="w-blog-entry-title-h entry-title">'.get_the_title().'</span>
				</h2>';
				$modalauthor = '<div class="w-blog-meta-author">';
				$modalauthor .= '<span>'.get_the_author().'</span>';
				$modalauthor .= '</div><div class="clear"></div></div>';
					if($post_format == 'video'){
						$popupcontent = get_the_content();
						
						$popupcontent = wp_trim_words( $popupcontent, 1000000, '' );
					}
					else{
						$popupcontent = get_the_content();
					}
							
				$output .=  do_shortcode('[ultimate_modal icon_type="none" modal_contain="ult-html" modal_on="ult-button" onload_delay="2" btn_size="sm" btn_bg_color="#ffffff" btn_txt_color="#00aeef" modal_on_align="left" read_text="click me" txt_color="#f60f60" modal_size="medium" modal_style="overlay-doorvertical" overlay_bg_color="#333333" overlay_bg_opacity="85" header_text_color="#333333" modal_border_width="2" modal_border_color="#333333" modal_border_radius="0" btn_text="Read More" content_bg_color="#ffffff" content_text_color="#bcbdc0"]<div class="popup-news-area"><div class="popup-news-image">'.$preview.'</div>'.$modalmeta.'<div class="w-blog-content-area">'.$modaltitle.$modalauthor.'<div class="news-modal-text">'.$popupcontent.'</div></div></div> [/ultimate_modal]');
			}



			$output .= '</div></div></div></div>';
		}

		$output .= '</div></div>';

		if ($attributes['pagination'] == 1 OR $attributes['pagination'] == 'yes' OR $attributes['pagination'] == 'regular') {
				$output .= '<div class="w-blog-pagination">
						'.get_the_posts_pagination( array(
						'prev_text' => '<',
						'next_text' => '>',
						'before_page_number' => '<span>',
						'after_page_number' => '</span>',
					) ).'
				</div>';
		} elseif ($attributes['pagination'] == 'ajax') {
			$max_num_pages = $wp_query->max_num_pages;
			if ($max_num_pages > 1) {
				$output .= '<div class="w-loadmore" id="us_load_more_'.$rand_id.'">
								<a href="javascript:void(0);" id="grid_load_more" class="g-btn color_contrast outlined size_small"><span>'.__('View More', 'us').'</span></a>
								<i class="fa fa-refresh fa-spin"></i>
							</div>';

				$output .= '<script type="text/javascript">
							jQuery(document).ready(function(){
								var page = 1,
									max_page = '.$max_num_pages.';

								jQuery("#us_load_more_'.$rand_id.' .g-btn").click(function(){
									jQuery("#us_load_more_'.$rand_id.'").addClass("loading");
									jQuery.ajax({
										type: "POST",
										url: "'.admin_url("admin-ajax.php").'",
										data: {
											action: "blogAjaxPagination",
											type: "'.$us_thumbnail_size.'",
											post_content: "'.$attributes['post_content'].'",
											show_date: "'.$attributes['show_date'].'",
											show_author: "'.$attributes['show_author'].'",
											show_comments: "'.$attributes['show_comments'].'",
											show_categories: "'.$attributes['show_categories'].'",
											show_tags: "'.$attributes['show_tags'].'",
											show_read_more: "'.$attributes['show_read_more'].'",
											category: "'.$attributes['category'].'",
											items: "'.$attributes['items'].'",
											page: page+1
										},
										success: function(data, textStatus, XMLHttpRequest){
											page++;

											var newItems = jQuery("<div>", {html:data}),
											blogList = jQuery("#us_blog_'.$rand_id.' .w-blog-list");';
				if ($us_thumbnail_size == 'blog-grid') {
					$output .= '           newItems.imagesLoaded(function() {
												newItems.children().each(function(childIndex,child){
													blogList.append(jQuery(child)).isotope("appended", jQuery(child));
													blogList.find(".fotorama").fotorama().on("fotorama:ready", function (e, fotorama) { blogList.isotope("layout"); });
												});
											});';
				} else {
					$output .= '           newItems.children().each(function(childIndex,child){
												blogList.append(jQuery(child));
												if (jQuery().fotorama){
													blogList.find(".fotorama").fotorama();
												}
											});';
				}
				$output .= '                jQuery("#us_load_more_'.$rand_id.'").removeClass("loading");
											if (max_page <= page) {
												jQuery("#us_load_more_'.$rand_id.'").addClass("done");
											}
										},
										error: function(MLHttpRequest, textStatus, errorThrown){
											jQuery("#us_load_more_'.$rand_id.'").removeClass("loading");
										}
									});
								});
							});
							</script>';
			}
		}

		wp_reset_query();

		return $output;
	}

	public function vc_portfolio($attributes, $content)
	{
		if (is_admin()) {
			return '';
		}

		$attributes = shortcode_atts(
			array(
				'style' => false,
				'align' => false,
				'meta' => false,
				'random_order' => false,
				'pagination' => '',
				'filters' => false,
				'columns' => 3,
				'category' => null,
				'items' => null,
				'ratio' => '3:2',
				'with_indents' => false,
				'el_class' => '',
			), $attributes);

		$rand_id = rand(100000, 999999);

		if ( ! in_array($attributes['columns'], array(2,3,4,5)))
		{
			$attributes['columns'] = 3;
		}

		if ( ! in_array($attributes['ratio'], array('3:2','4:3','1:1', '2:3', '3:4',)))
		{
			$attributes['ratio'] = '3:2';
		}

		if ( ! in_array($attributes['style'],
			array(
				'type_1',
				'type_2',
				'type_3',
				'type_4',
				'type_5',
				'type_6',
				'type_7',
				'type_8',
				'type_9',
				'type_10',
				'type_11',
				'type_12',
				'type_13',
				'type_14',
				'type_15',
				)
			))
		{
			$attributes['style'] = 'type_1';
		}

		if ( ! in_array($attributes['align'], array('left','right','center',)))
		{
			$attributes['align'] = 'center';
		}

		$attributes['ratio'] = str_replace(':', '-', $attributes['ratio']);
		if ($attributes['el_class'] != '') {
			$attributes['el_class'] = ' '.$attributes['el_class'];
		}

		global $wp_query;

		$attributes['items'] = intval($attributes['items']);
		$portfolio_items = (is_integer($attributes['items']) AND $attributes['items'] > 0)?$attributes['items']:$attributes['columns'];

		$order = 'DESC';
		$order_by = 'date';
		if ($attributes['random_order'] == 'yes') {
			$attributes['pagination'] = '';
			$order = null;
			$order_by = 'rand';
		}

		global $paged;

		if (is_front_page()) {
			$page_string = 'page';
		} else {
			$page_string = 'paged';
		}

		if ($attributes['pagination'] == 1 OR $attributes['pagination'] == 'yes' OR $attributes['pagination'] == 'regular') {
			$paged = get_query_var($page_string) ? get_query_var($page_string) : 1;
		} else {
			$paged = 1;
		}

		$args = array(
			'post_type' 		=> 'us_portfolio',
			'posts_per_page' 	=> $portfolio_items,
			'post_status' 		=> 'publish',
			'orderby' 			=> $order_by,
			'order' 			=> $order,
			'paged' 			=> $paged
		);

		$filters_html = $sortable_class = '';
		$categories_slugs = null;

		if ( ! empty($attributes['category'])) {

			$categories_slugs = explode(',', $attributes['category']);
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'us_portfolio_category',
					'field' => 'slug',
					'terms' => $categories_slugs
				)
			);
		}


		if ($attributes['filters'] == 1 OR $attributes['filters'] == 'yes') {
			$categories = get_terms('us_portfolio_category');

			if ( ! empty($categories_slugs))
			{
				foreach ($categories as $cat_id => $category)
				{
					if ( ! in_array($category->slug, $categories_slugs)) {
						unset($categories[$cat_id]);
					}
				}
			}

			if (count($categories) > 1) {
				$filters_html .= '<div class="w-filters">
									<div class="w-filters-item active" data-filter="*">'.__('All', 'us').'</div>';
				foreach($categories as $category) {
					$filters_html .= '<div class="w-filters-item" data-filter=".'.$category->slug.'">'.$category->name.'</div>';
				}

				$filters_html .= '</div>';
			}
		}

		if ($filters_html != ''){
			$sortable_class = ' type_sortable';
			// We'll need the isotope script for this, but only once
			if( ! wp_script_is('us-isotope', 'enqueued')){
				wp_enqueue_script('us-isotope');
			}
		}

		$with_indents_class = ($attributes['with_indents'] == 1 OR $attributes['with_indents'] == 'yes')?' with_indents':'';

		$output = '<div class="w-portfolio '.$attributes['style'].' align_'.$attributes['align'].' columns_'.$attributes['columns'].' ratio_'.$attributes['ratio'].$sortable_class.$with_indents_class.$attributes['el_class'].'" id="us_portfolio_'.$rand_id.'">
						'.$filters_html;

		$output .= '<div class="w-portfolio-list">';

		$wp_query = new WP_Query($args);

		$portfolio_order_counter = 0;

		while ( $wp_query->have_posts() )
		{
			$wp_query->the_post();
			$portfolio_order_counter++;
			$item_categories_links = '';
			$item_categories_classes = '';
			$item_categories = get_the_terms(get_the_ID(), 'us_portfolio_category');
			if (is_array($item_categories))
			{
				foreach ($item_categories as $item_category)
				{
					$item_categories_links .= $item_category->name.' / ';
					$item_categories_classes .= ' '.$item_category->slug;
				}
			}
			if (function_exists('mb_strlen'))
			{
				if (mb_strlen($item_categories_links) > 0 )
				{
					$item_categories_links = mb_substr($item_categories_links, 0, -2);
				}
			}
			else
			{
				if (strlen($item_categories_links) > 0 )
				{
					$item_categories_links = substr($item_categories_links, 0, -2);
				}
			}

			$link_ref = $link_target = '';
			$link = esc_url( apply_filters( 'the_permalink', get_permalink() ) );

			if (rwmb_meta('us_custom_link') != ''){
				$link = rwmb_meta('us_custom_link');
				if (rwmb_meta('us_custom_link_blank') == 1){
					$link_target = ' target="_blank"';
				}
			}

			if (rwmb_meta('us_lightbox') == 1){
				$img_id = get_post_thumbnail_id();
				$link = wp_get_attachment_image_src($img_id, 'full');
				$link = $link[0];
				$link_ref = ' ref="magnificPopup"';
			}

			$meta_html = '';

			if ($attributes['meta'] == 'date') {
				$meta_html = get_the_date();
			} elseif ($attributes['meta'] == 'category') {
				$meta_html = $item_categories_links;
			}

			$anchor_css = '';
			if (rwmb_meta('us_title_bg_color') != '') {
				$anchor_css .= ' background-color: '.rwmb_meta('us_title_bg_color').';';
			}
			if (rwmb_meta('us_title_text_color') != '') {
				$anchor_css .= ' color: '.rwmb_meta('us_title_text_color').';';
			}
			if ($anchor_css != '') {
				$anchor_css = ' style="'.$anchor_css.'"';
			}

			$output .= '<div class="w-portfolio-item order_'.$portfolio_order_counter.$item_categories_classes.'">
							<div class="w-portfolio-item-h">
								<a class="w-portfolio-item-anchor"'.$link_target.$link_ref.' href="'.$link.'"'.$anchor_css.'>
									<div class="w-portfolio-item-image">';
			if (has_post_thumbnail()) {
				$the_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(), 'portfolio-list-'.$attributes['ratio']);
				$the_thumbnail = $the_thumbnail[0];
				$output .= '<img class="w-portfolio-item-image-first" src="'.$the_thumbnail.'" alt="'.get_the_title().'">';
			} else {
				$output .= '<img class="w-portfolio-item-image-first" src="'.get_template_directory_uri().'/img/placeholder/500x500.gif" alt="'.get_the_title().'">';
			}

			$additional_image = '';
			if (rwmb_meta('us_additional_image') != '')
			{
				$additional_img_id = preg_replace('/[^\d]/', '', rwmb_meta('us_additional_image'));
				$additional_img = wp_get_attachment_image_src($additional_img_id, 'portfolio-list-'.$attributes['ratio'], 0);

				if ( $additional_img != NULL )
				{
					$additional_image = $additional_img[0];
				}
			}
			if ($additional_image != '')
			{
				$output .= '<img class="w-portfolio-item-image-second" src="'.$additional_image.'" alt="'.get_the_title().'">';
			}
					$output .= '	</div>
									<div class="w-portfolio-item-meta">
										<div class="w-portfolio-item-meta-h">
											<h2 class="w-portfolio-item-title">'.the_title('','', FALSE).'</h2>
											<span class="w-portfolio-item-text">'.$meta_html.'</span>
											<span class="w-portfolio-item-arrow"></span>
										</div>
									</div>
								</a>
							</div>
						</div>';
		}

		$output .= '</div>';
		if ($attributes['pagination'] == 1 OR $attributes['pagination'] == 'yes' OR $attributes['pagination'] == 'regular') {
			$output .= '<div class="w-portfolio-pagination">
					'.get_the_posts_pagination( array(
					'prev_text' => '<',
					'next_text' => '>',
					'before_page_number' => '<span>',
					'after_page_number' => '</span>',
				) ).'
			</div>';
		} elseif ($attributes['pagination'] == 'ajax') {
			$max_num_pages = $wp_query->max_num_pages;
			if ($max_num_pages > 1) {
				$output .= '<div class="w-loadmore" id="us_load_more_'.$rand_id.'">
								<a href="javascript:void(0);" id="grid_load_more" class="g-btn color_contrast outlined size_small"><span>'.__('Load More Items', 'us').'</span></a>
								<i class="fa fa-refresh fa-spin"></i>
							</div>';

				$output .= '<script type="text/javascript">
							jQuery(document).ready(function(){
								var page = 1,
									max_page = '.$max_num_pages.';

								jQuery("#us_load_more_'.$rand_id.' .g-btn").click(function(){
									jQuery("#us_load_more_'.$rand_id.'").addClass("loading");
									jQuery.ajax({
										type: "POST",
										url: "'.admin_url("admin-ajax.php").'",
										data: {
											action: "portfolioAjaxPagination",
											columns: "'.$attributes['columns'].'",
											items: "'.$attributes['items'].'",
											style: "'.$attributes['style'].'",
											align: "'.$attributes['align'].'",
											ratio: "'.$attributes['ratio'].'",
											with_indents: "'.$attributes['with_indents'].'",
											meta: "'.$attributes['meta'].'",
											filters: "'.$attributes['filters'].'",
											category: "'.$attributes['category'].'",
											page: page+1
										},
										success: function(data, textStatus, XMLHttpRequest){
											page++;

											var newItems = jQuery("<div>", {html:data}),
											portfolioList = jQuery("#us_portfolio_'.$rand_id.' .w-portfolio-list");';
				if ($filters_html != '') {
					$output .= '           newItems.imagesLoaded(function() {
												newItems.find("a[ref=magnificPopup][class!=direct-link]").magnificPopup({
													type: "image",
													fixedContentPos: false
												});
												newItems.children().each(function(childIndex,child){
													portfolioList.append(jQuery(child)).isotope("appended", jQuery(child), function(){
														portfolioList.isotope("layout");
													});
												});

												jQuery("#us_load_more_'.$rand_id.'").removeClass("loading");
												if (max_page <= page) {
													jQuery("#us_load_more_'.$rand_id.'").addClass("done");
												}
											});';

				} else {
					$output .= '
											newItems.find("a[ref=magnificPopup][class!=direct-link]").magnificPopup({
												type: "image",
												fixedContentPos: false
											});
											newItems.children().each(function(childIndex,child){
													portfolioList.append(jQuery(child))
												});
											jQuery("#us_load_more_'.$rand_id.'").removeClass("loading");
											if (max_page <= page) {
												jQuery("#us_load_more_'.$rand_id.'").addClass("done");
											}

												';
				}

				$output .= '

										},
										error: function(MLHttpRequest, textStatus, errorThrown){
											jQuery("#us_load_more_'.$rand_id.'").removeClass("loading");
										}
									});
								});
							});
							</script>';
			}
		}

		$output .= '</div>';

		wp_reset_query();

		return $output;

	}
	
	public function vc_careers($attributes, $content)
	{
		$attributes = shortcode_atts(
			array(
				'posts' => 2,
				'category' => null,
			), $attributes);

		if ( ! in_array($attributes['posts'], array(1,2,3)))
		{
			$attributes['posts'] = 2;
		}

		$args = array(
			'post_type' => 'us_careers',
			'post_status' => 'publish',
			'paged' => 1,
			'posts_per_page' => $attributes['posts'],
			'post__not_in' => get_option( 'sticky_posts' )
		);

		if ( ! empty($attributes['category'])) {
			$args['category_name'] = $attributes['category'];
		}

		$latest_posts = new WP_Query($args);

		$output = 	'<div class="w-shortblog w-careers columns_1 date_atleft">
							<div class="w-shortblog-h">
								<div class="w-shortblog-list">';
		$output .= '<div class="careers-head">Current Opportunities</div>';	
		$output .= '<div class="careers-head-arrow"></div>';					
		global $disable_section_shortcode;
		$disable_section_shortcode_tmp = $disable_section_shortcode;
		$disable_section_shortcode = TRUE;
		
		while($latest_posts->have_posts())
		{
			$latest_posts->the_post();


			$excerpt = get_the_content(get_the_ID());
			$excerpt = do_shortcode($excerpt);
			$excerpt = $this->sections_fix($excerpt);


			$excerpt = apply_filters('the_excerpt', $excerpt);
			$excerpt = str_replace(']]>', ']]&gt;', $excerpt);
			$excerpt_length = apply_filters('excerpt_length', 55);
			$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
			$excerpt = wp_trim_words( $excerpt, $excerpt_length, $excerpt_more );

			$output .= 				'<div class="w-shortblog-entry">
										<div class="w-shortblog-entry-h">';
											
											$ptitle ='	<h4 class="w-shortblog-entry-title">
													<span class="w-shortblog-entry-title-h">'.get_the_title().'</span>
												</h4>';
												
												
												$modaltitle  = '<h2 class="w-career-title">
				'.get_the_title().'
				</h2>';
					
						$popupcontent = get_the_content();
					
							
				$output .=  do_shortcode('[ultimate_modal icon_type="none" modal_contain="ult-html" modal_on="text" onload_delay="2"  modal_on_align="left" read_text="<h4><span>'.get_the_title().'</span></h4>" txt_color="#bcbdc0" modal_size="medium" modal_style="overlay-doorvertical" overlay_bg_color="#333333" overlay_bg_opacity="85" header_text_color="#333333" modal_border_width="2" modal_border_color="#333333" modal_border_radius="0" content_bg_color="#ffffff" content_text_color="#bcbdc0"]<div class="popup-news-area"><div>'.$modaltitle.'<div class="news-modal-text">'.$popupcontent.'</div></div></div> [/ultimate_modal]');
			
											
											
$output .=  '<div class="w-shortblog-entry-short">
											'.$excerpt.'
											</div>
											<div class="career-meta">
											<div class="w-entry-meta-date">
													
													<span class="w-meta-date-month">'.get_the_date('m.d.y').'</span>
												</div>
												<div class="career-read-more">';
												
												$content = get_the_content();
				
				
				
				$modaltitle  = '<h2 class="w-career-title">
				'.get_the_title().'
				</h2>';
					
						$popupcontent = get_the_content();
					
							
				$output .=  do_shortcode('[ultimate_modal icon_type="none" modal_contain="ult-html" modal_on="text" onload_delay="2"  modal_on_align="left" read_text="Continue Reading" txt_color="#bcbdc0" modal_size="medium" modal_style="overlay-doorvertical" overlay_bg_color="#333333" overlay_bg_opacity="85" header_text_color="#333333" modal_border_width="2" modal_border_color="#333333" modal_border_radius="0" content_bg_color="#ffffff" content_text_color="#bcbdc0"]<div class="popup-news-area"><div>'.$modaltitle.'<div class="news-modal-text">'.$popupcontent.'</div></div></div> [/ultimate_modal]');
			
			
												
												$output .=	'
												</div>
												</div>
										</div>
									</div>';
		}
		if (! $latest_posts->have_posts() ){
			$output .=	'<div class="w-shortblog-entry">
										<div class="w-shortblog-entry-h">
											
												
											
											<div class="w-shortblog-entry-short">
											Currently we don\'t have any open positions but if you are interested in possible future opportunities, please submit your interest.
											</div>
											</div>
									</div>';
		}
		$output .=				'</div>
							</div>
						</div>';
		$disable_section_shortcode = $disable_section_shortcode_tmp;
		return $output;
	}


public function vc_annualreport($attributes, $content)
	{
		$attributes = shortcode_atts(
			array(
				'posts' => 2,
				'category' => null,
			), $attributes);

		

		$args = array(
			'post_type' => 'us_annualreport',
			'post_status' => 'publish',
			'paged' => 1,
			'posts_per_page' => $attributes['posts'],
			'post__not_in' => get_option( 'sticky_posts' )
		);

		
		$categories_slugs = null;
		$cat_name = '';
		if ( ! empty($attributes['category'])) {
			$cat_name = $attributes['category'];
			$categories_slugs = explode(',', $attributes['category']);
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'us_annualreport_category',
					'field' => 'slug',
					'terms' => $categories_slugs
				)
			);
		}

		$latest_posts = new WP_Query($args);
		$output .= 	'<div class="w-annualreport w-annualreport columns_1">';
		$output .= '<div class="annualreport-head">'.$cat_name.'</div>';	
		
		 $output .= 	'<div id="mytabs-container">
		 					<div class="mytabs-menu-container">
							<ul class="mytabs-menu">';
        global $disable_section_shortcode;
		$disable_section_shortcode_tmp = $disable_section_shortcode;
		$disable_section_shortcode = TRUE;
		/*$pageWasRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0';
		if($pageWasRefreshed ) {
			session_start();
		$_SESSION["title_counter"] = 0;
		$title_counter = $_SESSION["title_counter"];
  
		} else {

		}*/
		
		
		if (!isset($_SESSION["title_counter"]) && empty($_SESSION["title_counter"])) {
			if (session_status() == PHP_SESSION_NONE) {
			session_start();
			}
			$_SESSION["title_counter"] = 1;
			$_SESSION["content_counter"] = 1;
			$_SESSION["title_counter_last"] = 1;
			$_SESSION["content_counter_last"] = 1;
    	} 
		$title_counter = $_SESSION["title_counter"];
		while($latest_posts->have_posts())
		{
			$latest_posts->the_post();
            	if($title_counter==$_SESSION["title_counter_last"]){
					 $output .='<li class="current"><a href="#mytab-'.$title_counter.'">'.get_the_title().'</a></li>';
					}
					else
					{
						$output .='<li><a href="#mytab-'.$title_counter.'">'.get_the_title().'</a></li>';
					}
           $title_counter++; 
           }
		   $_SESSION["title_counter_last"]  =  $title_counter;
		   $_SESSION["title_counter"] =  $title_counter;
		   
         $output .='</ul> </div>';
		  $content_counter= $_SESSION["content_counter"]; 
		 $output .=  '<div class="mytab">';
		 while($latest_posts->have_posts())
		{
			$latest_posts->the_post();
			
			if($content_counter==$_SESSION["content_counter_last"]){
 			$output .= '<div id="mytab-'.$content_counter.'" class="mytab-content first-tab-content" style="display:block;" >'.get_the_content(get_the_ID()).'</div>';
			}
			else{
 			$output .= '<div id="mytab-'.$content_counter.'" class="mytab-content" >'.get_the_content(get_the_ID()).'</div>';
			}
			$content_counter++; 
         }
		  $_SESSION["content_counter"] =  $content_counter;
		  $_SESSION["content_counter_last"] = $content_counter;
		 $output .= '</div></div>';
		 
		 if (! $latest_posts->have_posts() ){
			$output .=	'<div class="w-annualreport-entry">
										
											<h4 class="w-annualreport-entry-title">
													<span class="w-annualreport-entry-title-h">No Report  </span>
												</h4>
											
									</div>';
		}
		 $output .= '</div>';
		$disable_section_shortcode = $disable_section_shortcode_tmp;
		return $output;
	}
	
	public function vc_mapslider($attributes, $content)
	{
		$output .= '<div class="container map-countryslider">
            <div class="map-container">
                <a class="add-pin pin-australia active" title="Australia"  href="#"></a>
				 <a class="add-pin pin-New_Zealand" title="New Zealand"  href="#"></a>
                <a class="add-pin pin-Malaysia " title="Malaysia"  href="#"></a>
                <a class="add-pin pin-Indonesia" title="Indonesia"  href="#"></a>
                <a class="add-pin pin-Singapore" title="Singapore"  href="#"></a>
                <a class="add-pin pin-India" title="India"  href="#"></a>
                <a class="add-pin pin-Taiwan" title="Taiwan"  href="#"></a>
                <a class="add-pin pin-Brunei" title="Brunei"  href="#"></a>
                <a class="add-pin pin-China" title="China"  href="#"></a>
                <a class="add-pin pin-Thailand" title="Thailand"  href="#"></a>
                <a class="add-pin pin-South_Africa" title="South Africa"  href="#"></a>
                <a class="add-pin pin-Spain" title="Spain"  href="#"></a>
                <a class="add-pin pin-Germany" title="Germany"  href="#"></a>
                <a class="add-pin pin-Italy" title="Italy"  href="#"></a>
                <a class="add-pin pin-Netherlands" title="Netherlands"  href="#"></a>
                <a class="add-pin pin-UK" title="United Kingdom"  href="#"></a>
                <a class="add-pin pin-Poland" title="Poland"  href="#"></a>
                <a class="add-pin pin-Norway" title="Norway"  href="#"></a>
                <a class="add-pin pin-USA" title="USA"  href="#"></a>
                <a class="add-pin pin-Canada" title="Canada"  href="#"></a>
				<a class="add-pin pin-brazil" title="Brazil"  href="#"></a>
				  <a class="add-pin pin-japan" title="Japan"  href="#"></a>
				   <a class="add-pin pin-vietnam" title="Vietnam"  href="#"></a>
				    <a class="add-pin pin-Lithuania" title="Lithuania"  href="#"></a>
            </div>
            <div id="active-pin"><img src="images/blue-pin.png"> Australia</div>
            <div id="carousel-slides">
                <div class="carousel-slider">
                    <div class="slide"><div class="row slide-items">
                            <div class="col-md-12 border-right">
                                <div class="image-holder">
                                    <div class="imge"><img src="images/australia.png" alt="..."></div>
                                    <div class="name-text">Never Work Again</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide"><div class="row slide-items">
                            <div class="col-md-12 border-right">
                                <div class="image-holder">
                                    <div class="imge"><img src="images/australia.png" alt="..."></div>
                                    <div class="name-text">Masters of Wealth</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide"><div class="row slide-items">
                            <div class="col-md-12 border-right">
                                <div class="image-holder">
                                    <div class="imge"><img src="images/australia.png" alt="..."></div>
                                    <div class="name-text">National Achievers Congress</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide"><div class="row slide-items">
                            <div class="col-md-12 border-right">
                                <div class="image-holder">
                                    <div class="imge"><img src="images/australia.png" alt="..."></div>
                                    <div class="name-text">Date With Destiny</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide"><div class="row slide-items">
                            <div class="col-md-12 border-right">
                                <div class="image-holder">
                                    <div class="imge"><img src="images/australia.png" alt="..."></div>
                                    <div class="name-text">Reignite</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide"><div class="row slide-items">
                            <div class="col-md-12 border-right">
                                <div class="image-holder">
                                    <div class="imge"><img src="images/australia.png" alt="..."></div>
                                    <div class="name-text">Business Mastery</div>
                                </div>
                            </div>
                        </div>
                    </div>
					 <div class="slide"><div class="row slide-items">
                            <div class="col-md-12 border-right">
                                <div class="image-holder">
                                    <div class="imge"><img src="images/australia.png" alt="..."></div>
                                    <div class="name-text">Ted Thomas</div>
                                </div>
                            </div>
                        </div>
                    </div>
					 <div class="slide"><div class="row slide-items">
                            <div class="col-md-12 border-right">
                                <div class="image-holder">
                                    <div class="imge"><img src="images/australia.png" alt="..."></div>
                                    <div class="name-text">Unleash the Power Within</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slide"><div class="row slide-items">
                            <div class="col-md-12 border-right">
                                <div class="image-holder">
                                    <div class="imge"><img src="images/australia.png" alt="..."></div>
                                    <div class="name-text">Wolf of Wall Street Tour</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> ';
							
		$disable_section_shortcode = $disable_section_shortcode_tmp;
		return $output;
	}
	
	
	public function vc_clients($attributes, $content)
	{
		$attributes = shortcode_atts(
			array(
				'amount' => 1000,
				'auto_scroll' => false,
				'interval' => 1,
				'arrows' => false,
				'indents' => false,
				'columns' => 5,
				'el_class' => '',
			), $attributes);

		$args = array(
			'post_type' => 'us_client',
			'paged' => 1,
			'posts_per_page' => $attributes['amount'],
		);
		if ($attributes['el_class'] != '') {
			$attributes['el_class'] = ' '.$attributes['el_class'];
		}

		$clients = new WP_Query($args);

		$columns = (in_array($attributes['columns'], array(6, 5, 4, 3, 2, 1)))?$attributes['columns']:5;

		$arrows_class = ($attributes['arrows'] == 1 OR $attributes['arrows'] == 'yes')?' nav_arrows':'';
		$indents_class = ($attributes['indents'] == 1 OR $attributes['indents'] == 'yes')?' with_indents':'';
		$auto_scroll = ($attributes['auto_scroll'] == 1 OR $attributes['auto_scroll'] == 'yes')?'1':'0';
		$interval = intval($attributes['interval']);
		if ($interval < 1) {
			$interval = 1;
		}
		$interval = $interval*1000;

		// We need slick script for this, but only once per page
		if( ! wp_script_is('us-slick', 'enqueued')){
			wp_enqueue_script('us-slick');
		}

		$output = '<div class="w-clients'.$arrows_class.$indents_class.$attributes['el_class'].'">
						<div class="w-clients-list slick-loading" data-columns="'.$columns.'" data-autoPlay="'.$auto_scroll.'" data-autoPlaySpeed="'.$interval.'">';

		while ($clients->have_posts())
		{
			$clients->the_post();
			if(has_post_thumbnail())
			{
				$thumb_src =  wp_get_attachment_image_src(get_post_thumbnail_id( get_the_ID() ), 'carousel-thumb');
				if (rwmb_meta('us_client_url') != '')
				{
					$client_new_tab = (rwmb_meta('us_client_new_tab') == 1)?' target="_blank"':'';
					$client_url = (rwmb_meta('us_client_url') != '')?rwmb_meta('us_client_url'):'javascript:void(0);';
					$output .= '<div class="w-clients-item"><a class="w-clients-item-h" href="'.$client_url.'"'.$client_new_tab.'>'.
						'<img data-lazy="'.$thumb_src[0].'" alt="'.get_the_title().'"></a></div>';
				}
				else
				{
					$output .= '<div class="w-clients-item"><span class="w-clients-item-h">'.
						'<img data-lazy="'.$thumb_src[0].'" alt="'.get_the_title().'"></span></div>';
				}

			}
		}

		wp_reset_query();

		$output .= '</div>
					</div>';
		return $output;
	}

	public function vc_latest_posts($attributes, $content)
	{
		$attributes = shortcode_atts(
			array(
				'posts' => 2,
				'category' => null,
				'el_class' => null,
			), $attributes);

		if ( ! in_array($attributes['posts'], array(1,2,3)))
		{
			$attributes['posts'] = 2;
		}
		if ($attributes['el_class'] != '') {
			$attributes['el_class'] = ' '.$attributes['el_class'];
		}

		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'paged' => 1,
			'posts_per_page' => $attributes['posts'],
			'post__not_in' => get_option( 'sticky_posts' )
		);

		if ( ! empty($attributes['category'])) {
			$args['category_name'] = $attributes['category'];
		}

		$latest_posts = new WP_Query($args);

		$output = 	'<div class="w-shortblog columns_'.$attributes['posts'].' date_atleft'.$attributes['el_class'].'">
							<div class="w-shortblog-h">
								<div class="w-shortblog-list">';
		global $disable_section_shortcode, $post;
		$disable_section_shortcode_tmp = $disable_section_shortcode;
		$disable_section_shortcode = TRUE;
		while($latest_posts->have_posts())
		{
			$latest_posts->the_post();

			$excerpt = $post->post_excerpt;
			if ($excerpt == '') {
				$excerpt = get_the_content();
				$excerpt = do_shortcode($excerpt);
				$excerpt = $this->sections_fix($excerpt);


				$excerpt = apply_filters('the_excerpt', $excerpt);
				$excerpt = str_replace(']]>', ']]&gt;', $excerpt);
				$excerpt_length = apply_filters('excerpt_length', 55);
				$excerpt_more = apply_filters('excerpt_more', ' ' . '[...]');
				$excerpt = wp_trim_words( $excerpt, $excerpt_length, $excerpt_more );
			} else {
				$excerpt = apply_filters('the_excerpt', $excerpt);
				$excerpt = str_replace(']]>', ']]&gt;', $excerpt);
			}


			$output .= 				'<div class="w-shortblog-entry">
										<div class="w-shortblog-entry-h">
											<a class="w-shortblog-entry-link" href="'.get_permalink(get_the_ID()).'">
												<h4 class="w-shortblog-entry-title">
													<span class="w-shortblog-entry-title-h">'.get_the_title().'</span>
												</h4>
											</a>
											<div class="w-shortblog-entry-meta">
												<div class="w-shortblog-entry-meta-date">
													<span class="w-shortblog-entry-meta-date-month">'.get_the_date('M').'</span>
										<span class="w-shortblog-entry-meta-date-day">'.get_the_date('d').'</span>
										<span class="w-shortblog-entry-meta-date-year">'.get_the_date('Y').'</span>
												</div>
											</div>
											<div class="w-shortblog-entry-short">
											'.$excerpt.'
											</div>
										</div>
									</div>';
		}
		$output .=				'</div>
							</div>
						</div>';
		$disable_section_shortcode = $disable_section_shortcode_tmp;
		wp_reset_query();

		return $output;
	}

	public function vc_counter($attributes, $content)
	{
		$attributes = shortcode_atts(
			array(
				'number' => '0',
				'count' => '99',
				'suffix' => '',
				'prefix' => '',
				'color' => '',
				'custom_color' => '',
				'size' => 'medium',
				'title' => '',
				'el_class' => '',
			), $attributes);

		if  (! in_array($attributes['size'], array('small', 'medium', 'big'))) {
			$attributes['size'] = 'medium';
		}
		$color_class = ($attributes['color'] != '')?' color_'.$attributes['color']:'';
		$item_style = '';
		if ($attributes['color'] == 'custom' AND $attributes['custom_color'] !=  '') {
			$item_style .= ' style="color: '.$attributes['custom_color'].';"';
		}
		if ($attributes['el_class'] != '') {
			$attributes['el_class'] = ' '.$attributes['el_class'];
		}

		$output = 	'<div class="w-counter size_'.$attributes['size'].$color_class.$attributes['el_class'].'" data-number="'.$attributes['number'].'" data-count="'.$attributes['count'].'" data-prefix="'.$attributes['prefix'].'" data-suffix="'.$attributes['suffix'].'">
						<div class="w-counter-h">
							<div class="w-counter-number"'.$item_style.'>'.$attributes['prefix'].$attributes['count'].$attributes['suffix'].'</div>
							<h6 class="w-counter-title">'.$attributes['title'].'</h6>
						</div>
					</div>';

		return $output;

	}

	public function vc_simple_slider($attributes)
	{
		$post = get_post();

		static $instance = 0;
		$instance++;

		if ( ! empty( $attributes['ids'] ) )
		{
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if ( empty( $attributes['orderby'] ) )
			{
				$attributes['orderby'] = 'post__in';
			}
			$attributes['include'] = $attributes['ids'];
		}

		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if ( isset( $attributes['orderby'] ) )
		{
			$attributes['orderby'] = sanitize_sql_orderby( $attributes['orderby'] );
			if ( !$attributes['orderby'] )
			{
				unset( $attributes['orderby'] );
			}
		}

		extract(shortcode_atts(array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'itemtag'    => 'dl',
			'icontag'    => 'dt',
			'captiontag' => 'dd',
			'columns'    => 3,
			'type'       => 's',
			'include'    => '',
			'exclude'    => '',
			'auto_rotation'    => null,
			'arrows'    => null,
			'nav'    => null,
			'transition'    => null,
			'fullscreen'    => null,
			'stretch'    => null,
			'el_class'    => '',
		), $attributes));

		$size = 'gallery-s';


		$id = intval($id);

		if ( !empty($include) )
		{
			$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

			$attachments = array();
			if (is_array($_attachments))
			{
				foreach ( $_attachments as $key => $val ) {
					$attachments[$val->ID] = $_attachments[$key];
				}
			}
		}
		elseif ( !empty($exclude) )
		{
			$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		}
		else
		{
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		}

		if ( empty($attachments) )
		{
			return '';
		}

		$data_autoplay = '';
		if ($auto_rotation == 'yes' OR $auto_rotation == 1) {
			$data_autoplay = ' data-autoplay="true"';
		}

		$data_arrows = ' data-arrows="always"';
		if ($arrows == 'hide') {
			$data_arrows = ' data-arrows="false"';
		} elseif ($arrows == 'hover') {
			$data_arrows = ' data-arrows="true"';
		}

		$data_nav =  ' data-nav="none"';
		if ($nav == 'dots') {
			$data_nav = ' data-nav="dots"';
		} elseif ($nav == 'thumbs') {
			$data_nav = ' data-nav="thumbs"';
		}

		$data_transition =  ' data-transition="slide"';
		if ($transition == 'fade') {
			$data_transition = ' data-transition="crossfade"';
		} elseif ($transition == 'dissolve') {
			$data_transition = ' data-transition="dissolve"';
		}

		$data_allowfullscreen = '';
		if ($fullscreen == 'yes' OR $fullscreen == 1) {
			$data_allowfullscreen = ' data-allowfullscreen="true"';
		}

		$data_fit = '';
		if ($stretch == 'yes' OR $stretch == 1) {
			$data_fit = ' data-fit="cover"';
		}

		$rand_id = rand(100000, 999999);

		$i = 1;
		if (is_array($attachments))
		{
			$data_ratio = null;
			$output = '';
			foreach ( $attachments as $id => $attachment ) {
				if ($data_ratio == null) {
					$first_img =  wp_get_attachment_image_src( $id, 'full' );
					if (is_array($first_img) AND ( ! empty($first_img[1])) AND ( ! empty($first_img[2]))) {
						$data_ratio = ' data-ratio="'.$first_img[1].'/'.$first_img[2].'"';
					} else {
						$data_ratio = -1;
					}
				}

				$output .= '<a href="'.wp_get_attachment_url($id).'">';
				$output .= wp_get_attachment_image( $id, $size, 0 );
				$output .= '</a>';

				$i++;
			}

			if ($data_ratio == null OR $data_ratio == -1) {
				$data_ratio = '';
			}

			// We need fotorama script for this, but only once per page
			if ( ! wp_script_is('us-fotorama', 'enqueued')){
				wp_enqueue_script('us-fotorama');
			}

			if ($el_class != '') {
				$el_class = ' '.$el_class;
			}

			$output = '<div class="w-slider'.$el_class.'"><div class="fotorama" id="slider_'.$rand_id.'" data-auto="false" data-shadows="false" data-glimpse="0" data-margin="0" data-loop="true" data-swipe="true" data-width="100%"'.$data_autoplay.$data_arrows.$data_nav.$data_transition.$data_allowfullscreen.$data_fit.$data_ratio.'>'.$output."</div></div>";
		}


		return $output;
	}

	public function vc_grid_blog_slider($attributes)
	{
		$post = get_post();

		static $instance = 0;
		$instance++;

		if ( ! empty( $attributes['ids'] ) )
		{
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if ( empty( $attributes['orderby'] ) )
			{
				$attributes['orderby'] = 'post__in';
			}
			$attributes['include'] = $attributes['ids'];
		}

		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if ( isset( $attributes['orderby'] ) )
		{
			$attributes['orderby'] = sanitize_sql_orderby( $attributes['orderby'] );
			if ( !$attributes['orderby'] )
			{
				unset( $attributes['orderby'] );
			}
		}

		extract(shortcode_atts(array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'itemtag'    => 'dl',
			'icontag'    => 'dt',
			'captiontag' => 'dd',
			'columns'    => 3,
			'type'       => 's',
			'include'    => '',
			'exclude'    => '',
			'auto_rotation'    => '1',
		), $attributes));


		$type_classes = ' type_slider';
		$size = 'gallery-full';


		$id = intval($id);

		if ( !empty($include) )
		{
			$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

			$attachments = array();
			if (is_array($_attachments))
			{
				foreach ( $_attachments as $key => $val ) {
					$attachments[$val->ID] = $_attachments[$key];
				}
			}
		}
		elseif ( !empty($exclude) )
		{
			$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		}
		else
		{
			$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
		}

		if ( empty($attachments) )
		{
			return '';
		}


		$rand_id = rand(100000, 999999);
		$output = '<div class="w-gallery'.$type_classes.'"><div class="w-gallery-main"><div class="w-gallery-main-h flexslider flex-loading" id="slider_'.$rand_id.'">';



		$i = 1;
		if (is_array($attachments))
		{

			$output .= '<ul class="slides">';
			foreach ( $attachments as $id => $attachment ) {

				$output .= '<li>';
				$output .= wp_get_attachment_image( $id, 'portfolio-list', 0 );
				$output .= '</li>';

				$i++;

			}
			$output .= '</ul>';



		}

		$output .= "</div></div></div>";

		$disable_rotation = '';
		if ($auto_rotation == 0) {
			$disable_rotation = 'slideshow: false,';
		}

		$output .= '<script type="text/javascript">
						jQuery(window).load(function() {
							jQuery("#slider_'.$rand_id.'").flexslider({
								'.$disable_rotation.'controlsContainer: "#slider_'.$rand_id.'",
								directionalNav: true,
								controlNav: false,
								start: function(slider) {
									jQuery("#slider_'.$rand_id.'").removeClass("flex-loading");
									slider.resize();
									jQuery(".w-blog.type_masonry .w-blog-list").isotope("layout");
								}
							});
						});
					</script>';

		return $output;
	}

	public function gallery($attributes)
	{
		$post = get_post();

		static $instance = 0;
		$instance++;

		if ( ! empty($attributes['ids']))
		{
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if (empty($attributes['orderby']))
			{
				$attributes['orderby'] = 'post__in';
			}
			$attributes['include'] = $attributes['ids'];
		}

		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if (isset($attributes['orderby']))
		{
			$attributes['orderby'] = sanitize_sql_orderby($attributes['orderby']);
			if ( !$attributes['orderby'])
			{
				unset($attributes['orderby']);
			}
		}

		extract(shortcode_atts(array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'itemtag'    => 'dl',
			'icontag'    => 'dt',
			'captiontag' => 'dd',
			'columns'    => 3,
			'include'    => '',
			'exclude'    => '',
			'indents'    => '',
		), $attributes));

		$columns_to_size = array(
			1 => 'l',
			2 => 'l',
			3 => 'l',
			4 => 'm',
			5 => 'm',
			6 => 'm',
			7 => 'm',
			8 => 's',
			9 => 's',
			10 => 's',
		);

		$size = 'gallery-'.$columns_to_size[$columns];
		$type_classes = ' columns_'.$columns;
		$indents_class = ($attributes['indents'] == 1 OR $attributes['indents'] == 'yes' OR $attributes['indents'] == 'true')?' with_indents':'';

		$id = intval($id);
		if ('RAND' == $order)
		{
			$orderby = 'none';
		}

		if ( !empty($include))
		{
			$_attachments = get_posts(array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));

			$attachments = array();
			if (is_array($_attachments))
			{
				foreach ($_attachments as $key => $val) {
					$attachments[$val->ID] = $_attachments[$key];
				}
			}
		}
		elseif ( !empty($exclude))
		{
			$attachments = get_children(array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
		}
		else
		{
			$attachments = get_children(array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby));
		}

		if (empty($attachments))
		{
			return '';
		}

		if (is_feed())
		{
			$output = "\n";
			if (is_array($attachments))
			{
				foreach ($attachments as $att_id => $attachment)
					$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
			}
			return $output;
		}

		$rand_id = rand(99999, 999999);

		$output = '<div id="gallery_'.$rand_id.'" class="w-gallery'.$type_classes.$indents_class.'"> <div class="w-gallery-tnails">';

		$i = 1;
		if (is_array($attachments))
		{
			foreach ($attachments as $id => $attachment) {

				$title = trim(strip_tags(get_post_meta($id, '_wp_attachment_image_alt', true)));
				if (empty($title))
				{
					$title = trim(strip_tags($attachment->post_excerpt)); // If not, Use the Caption
				}
				if (empty($title))
				{
					$title = trim(strip_tags($attachment->post_title)); // Finally, use the title
				}

				$output .= '<a class="w-gallery-tnail order_'.$i.'" href="'.wp_get_attachment_url($id).'" title="'.$title.'">';
				$output .= wp_get_attachment_image($id, $size, 0, array('class' => 'w-gallery-tnail-img'));
				$output .= '<span class="w-gallery-tnail-title"></span>';
				$output .= '</a>';

				$i++;

			}
		}

		$output .= "</div> </div>\n";

		return $output;
	}
}

global $us_shortcodes;

$us_shortcodes = new US_Shortcodes;

// Add buttons to tinyMCE
function us_add_buttons() {
	if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )
	{
		add_filter('mce_external_plugins', 'us_tinymce_plugin');
		add_filter('mce_buttons_3', 'us_tinymce_buttons');
	}
}

function us_tinymce_buttons($buttons) {
	array_push($buttons, "columns", "separator_btn", "button_btn", "tabs", "accordion", "icon", "iconbox", "testimonial", "services", "team", "latest_posts", "portfolio", "clients", "actionbox", "video", "pricing_table", "counter", "alert", "contact_form", "contacts", "social_links", "gmaps");
	if(class_exists('RevSlider')){
		$slider_revolution = array();
		$slider = new RevSlider();
		$arrSliders = $slider->getArrSliders();
		foreach($arrSliders as $revSlider) {
			$slider_revolution[$revSlider->getAlias()] = $revSlider->getTitle();
		}

		if (count ($slider_revolution) > 0) {
			array_push($buttons, "rev_slider");
		}
	}
	return $buttons;
}

function us_tinymce_plugin($plugin_array) {
	$plugin_array['columns'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['alert'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['tabs'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['accordion'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['video'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['team'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['button_btn'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['separator_btn'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['icon'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['iconbox'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['testimonial'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['latest_posts'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['portfolio'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['clients'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['actionbox'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['pricing_table'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['contact_form'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['social_links'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['contacts'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['gmaps'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['rev_slider'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
	$plugin_array['counter'] = get_template_directory_uri().'/functions/tinymce/buttons.js';
//	$plugin_array['animate'] = get_template_directory_uri().'/functions/tinymce/buttons.js';

	return $plugin_array;
}

add_action('admin_init', 'us_add_buttons');

// Add Indents checkbox to Gallery window
function us_media_templates(){

	?>
	<script type="text/html" id="tmpl-my-custom-gallery-setting">
		<label class="setting">
			<span>Indendts</span>
			<input type="checkbox" data-setting="indents">
		</label>
	</script>

	<script>

		jQuery(document).ready(function(){

			// add your shortcode attribute and its default value to the
			// gallery settings list; $.extend should work as well...
			_.extend(wp.media.gallery.defaults, {
				type: 'default_val'
			});

			// merge default gallery settings template with yours
			wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
				template: function(view){
					return wp.media.template('gallery-settings')(view)
						+ wp.media.template('my-custom-gallery-setting')(view);
				}
			});

		});

	</script>
<?php

}

add_action('print_media_templates', 'us_media_templates');

