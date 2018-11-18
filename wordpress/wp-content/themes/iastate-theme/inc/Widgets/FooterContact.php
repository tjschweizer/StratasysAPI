<?php

class FooterContact extends WP_Widget
{
    /**
     * Sets up the widgets name etc
     */
    public function __construct()
    {
        parent::__construct('isu_footer_contact', 'ISU Contact', array(
            'classname' => 'footer-contact',
            'description' => 'ISU Contact',
        ));
    }

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        if ($instance) {
            iastate_theme()->setOptions($instance);
        }
        echo $args['before_widget'];
        echo iastate_theme()->widgetFooterContact();
        echo $args['after_widget'];
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form($instance)
    {
        if (!$instance) {
            $options['footer_contact'] = iastate_theme()->getOption('footer_contact');
        } else {
            $options = $instance;
        }
        echo sprintf('<p><label for="%1$s">Address</label><textarea id="%1$s" class="large-text" rows="4" name="%2$s">%3$s</textarea></p>',
            $this->get_field_id('address'),
            $this->get_field_name('address'),
            implode("\n", $options['footer_contact']['address'])
        );

        echo sprintf('<p><label for="%1$s">Address URL</label><input id="%1$s" class="widefat" type="url" name="%2$s" value="%3$s"></p>',
            $this->get_field_id('address_url'),
            $this->get_field_name('address_url'),
            $options['footer_contact']['address_url']
        );

        echo sprintf('<p><label for="%1$s">Email</label><textarea id="%1$s" class="large-text" rows="2" name="%2$s">%3$s</textarea></p>',
            $this->get_field_id('email'),
            $this->get_field_name('email'),
            implode("\n", $options['footer_contact']['email'])
        );

        echo sprintf('<p><label for="%1$s">Phone</label><textarea id="%1$s" class="large-text" rows="2" name="%2$s">%3$s</textarea></p>',
            $this->get_field_id('phone'),
            $this->get_field_name('phone'),
            implode("\n", $options['footer_contact']['phone'])
        );

        echo sprintf('<p><label for="%1$s">Fax</label><textarea id="%1$s" class="large-text" rows="2" name="%2$s">%3$s</textarea></p>',
            $this->get_field_id('fax'),
            $this->get_field_name('fax'),
            implode("\n", $options['footer_contact']['fax'])
        );
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     *
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        $tmp = array();
        $tmp['footer_contact']['address'] = array_filter(explode("\n", $new_instance['address']));
        $tmp['footer_contact']['email'] = array_filter(explode("\n", $new_instance['email']));
        $tmp['footer_contact']['phone'] = array_filter(explode("\n", $new_instance['phone']));
        $tmp['footer_contact']['fax'] = array_filter(explode("\n", $new_instance['fax']));
        $tmp['footer_contact']['address_url'] = esc_url_raw($new_instance['address_url']);

        return $tmp;
    }
}