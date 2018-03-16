<?php

class haet_cleverreach_widget extends WP_Widget {

    function __construct() {
        // Instantiate the parent object
        parent::__construct( false, __('CleverReach Sign-Up Form', 'cleverreach') );
    }

    function widget( $args, $instance ) {
        echo $args['before_widget'];
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'];
            echo esc_html( $instance['title'] );
            echo $args['after_title'];
        }
        print_cleverreach_form(true);
        echo $args['after_widget'];
    }

    function update( $new_instance, $old_instance ) {
        $instance['title'] = strip_tags( $new_instance['title'] );
        return $instance;
    }

    function form( $instance ) {
        $title  = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
        ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
        <?php
    }
}

function haet_cleverreach_register_widget() {
    register_widget( 'haet_cleverreach_widget' );
}

add_action( 'widgets_init', 'haet_cleverreach_register_widget' );