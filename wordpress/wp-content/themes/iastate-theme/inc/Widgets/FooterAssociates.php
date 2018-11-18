<?php

class FooterAssociates extends WP_Widget
{
    /**
     * Sets up the widgets name etc
     */
    public function __construct()
    {
        parent::__construct('isu_footer_associates', 'ISU Associates', array(
            'classname' => 'footer-associates',
            'description' => 'ISU Associates',
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
        echo iastate_theme()->widgetFooterAssociates();
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
            $options['footer_associates'] = iastate_theme()->getOption('footer_associates');
        } else {
            $options = $instance;
        }

        foreach ($options['footer_associates'] as $index => $option) {
            echo sprintf('<p><label for="%1$s">Label [%5$d]</label><input id="%1$s" class="widefat" type="text" name="%2$s[%4$d]" value="%3$s"></p>',
                $this->get_field_id('label'),
                $this->get_field_name('label'),
                $option['label'],
                $index,
                $index + 1
            );
            echo sprintf('<p><label for="%1$s">URL [%5$d]</label><input id="%1$s" class="widefat" type="url" name="%2$s[%4$d]" value="%3$s"></p>',
                $this->get_field_id('url'),
                $this->get_field_name('url'),
                $option['url'],
                $index,
                $index + 1
            );
        }
        // additional field
        echo sprintf('<p><label for="%1$s">Additional Label</label><input id="%1$s" class="widefat" type="text" name="%2$s[]" value=""></p>',
            $this->get_field_id('label'),
            $this->get_field_name('label')
        );
        echo sprintf('<p><label for="%1$s">Additional URL</label><input id="%1$s" class="widefat" type="url" name="%2$s[]" value=""></p>',
            $this->get_field_id('url'),
            $this->get_field_name('url')
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
        $formattedLinks = array();
        foreach ($new_instance as $label => $values) {
            foreach ($values as $index => $value) {
                if ($label === 'url' || $label === 'label') {
                    $formattedLinks[$index][$label] = $value;
                }
            }
        }
        foreach ($formattedLinks as $index => $option) {
            if ($option['label'] == '' && $option['url'] == '') {
                unset($formattedLinks[$index]);
            }
        }
        $tmp['footer_associates'] = array_values($formattedLinks);

        return $tmp;
    }

}