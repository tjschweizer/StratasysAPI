<?php

class FooterLegal extends WP_Widget
{
    /**
     * Sets up the widgets name etc
     */
    public function __construct()
    {
        parent::__construct('isu_footer_legal', 'ISU Legal Information', array(
            'classname' => 'footer-legal',
            'description' => 'ISU Legal Information',
		));
    }

    /**
     * @inheritdoc
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        echo iastate_theme()->widgetFooterLegal();
        echo $args['after_widget'];
    }

    /**
     * @inheritdoc
     */
    public function form($instance)
    {
        ?>
		<p>Legal Information</p>
        <?php
    }

    /**
     * @inheritdoc
     */
    public function update($new_instance, $old_instance)
    {
        return $old_instance;
    }
}