<?php
/*
Plugin Name: Timezone widget
Description: Displays the current time according to the selected time zone.
Author: Julien Desrosiers
Version: 1.0
Author URI: http://www.juliendesrosiers.com
License: MIT
*/


class TimezoneWidget extends WP_Widget {

	function TimezoneWidget() {
		$widget_ops = array('classname' => 'widget_tzwidget', 'description' => __('Show the time according to the given time zone'));
		$control_ops = array('width' => 400, 'height' => 350);
		$this->WP_Widget('timezone', __('Time Zone'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance );
		$zone = apply_filters( 'widget_tzwidget', $instance['zone'], $instance );
		echo $before_widget;
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; } 

      // on stocke la zone precedente, pour la remettre apres avoir fait notre job
      $default_zone = date_default_timezone_get();
      // on set la zone pour la job de notre plugin seulement
      date_default_timezone_set($zone);
    ?>			
    <div class="tzwidgetwidget"><?php echo date('r'); ?></div>
		<?php
      // on remet la zone qui etait en place avant le passage de notre plugin
      date_default_timezone_set($default_zone);

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
    $instance['zone'] = $new_instance['zone'];
		return $instance;
	}

  private function selected($zone_name, $current_zone) {
    echo ' value="'.$zone_name.'" '. ($zone_name == $current_zone ? ' selected="selected" ': '');
  }

	function form( $instance ) {
    global $tz_zone_options;
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'zone' => '' ) );
		$title = strip_tags($instance['title']);
    $zone = strip_tags($instance['zone']);
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

    <p><label for="<?php echo $this->get_field_id('zone'); ?>"><?php _e('Zone:'); ?></label>
    <select id="<?php echo $this->get_field_id('zone'); ?>" name="<?php echo $this->get_field_name('zone'); ?>">
      <optgroup label="Africa">
      <option <?php $this->selected("Africa/Kampala", $zone); ?>>Kampala</option>
      <option <?php $this->selected("Africa/Kinshasa", $zone); ?>>Kinshasa</option>
      </optgroup>
      <optgroup label="America">
      <option <?php $this->selected("America/Guatemala", $zone); ?>>Guatemala</option>
      <option <?php $this->selected("America/Montreal", $zone); ?>>Montreal</option>
      <option <?php $this->selected("America/Vancouver", $zone); ?>>Vancouver</option>
      </optgroup>
      <optgroup label="Antarctica">
      <option <?php $this->selected("Antarctica/Vostok", $zone); ?>>Vostok</option>
      </optgroup>
      <optgroup label="Arctic">
      <option <?php $this->selected("Arctic/Longyearbyen", $zone); ?>>Longyearbyen</option>
      </optgroup>
      <optgroup label="Asia">
      <option <?php $this->selected("Asia/Shanghai", $zone); ?>>Shanghai</option>
      <option <?php $this->selected("Asia/Tokyo", $zone); ?>>Tokyo</option>
      </optgroup>
      <optgroup label="Atlantic">
      <option <?php $this->selected("Atlantic/Canary", $zone); ?>>Canary</option>
      <option <?php $this->selected("Atlantic/St_Helena", $zone); ?>>St Helena</option>
      </optgroup>
      <optgroup label="Australia">
      <option <?php $this->selected("Australia/Melbourne", $zone); ?>>Melbourne</option>
      <option <?php $this->selected("Australia/Sydney", $zone); ?>>Sydney</option>
      </optgroup>
      <optgroup label="Europe">
      <option <?php $this->selected("Europe/Amsterdam", $zone); ?>>Amsterdam</option>
      <option <?php $this->selected("Europe/Monaco", $zone); ?>>Monaco</option>
      <option <?php $this->selected("Europe/Vatican", $zone); ?>>Vatican</option>
      </optgroup>
      <optgroup label="Indian">
      <option <?php $this->selected("Indian/Christmas", $zone); ?>>Christmas</option>
      <option <?php $this->selected("Indian/Reunion", $zone); ?>>Reunion</option>
      </optgroup>
      <optgroup label="Pacific">
      <option <?php $this->selected("Pacific/Tahiti", $zone); ?>>Tahiti</option>
      </optgroup>
      <optgroup label="UTC">
      <option <?php $this->selected("UTC", $zone); ?>>UTC</option>
      </optgroup>
      <optgroup label="Manual Offsets">
      <option <?php $this->selected("UTC+0", $zone); ?>>UTC+0</option>
      </optgroup>
    </select>
    </p>

<?php
	}

}

add_action('widgets_init', function() { 
  return register_widget("TimezoneWidget"); 
});

