<?php
if ( defined('WP_CLI') && WP_CLI ) {

	class Example_Content_Update_CLI_Commands extends WP_CLI_Command {

		public function start () {

			WP_CLI::line( 'Process started: ' . date_i18n( 'Y-m-d H:i:s e' ) );

			$args = array(
				'post_type' => 'post',
				'posts_per_page' => -1,
				'fields' => 'ids',
			);

			$post_ids = get_posts( $args );

			//Check to make sure we have some posts to update.
			if ( empty( $post_ids ) ) {
				WP_CLI::warning( 'No posts to update.' );

				continue;
			}

			$found_posts = count( $post_ids );

			$cleaned = 0;

			$progress_bar = \WP_CLI\Utils\make_progress_bar( 'Progress:', $found_posts );

			WP_CLI::line( 'Found ' . $found_posts . ' posts.' );

			foreach ( $post_ids as $post_id ) {

				$content = get_post_field( 'post_content', $post_id ); //Get the post content
				$excerpt = get_post_field( 'post_excerpt', $post_id ); //Get the post content

				$updated_post = array(
					'ID' => $post_id,
					'post_content' => $this->replace_content( $content ),
					'post_excerpt' => $excerpt = $this->replace_content( $excerpt )
				);

				// Update the post into the database
				$updated = wp_update_post( $updated_post );

				if( 0 != $updated ) {
					$cleaned++;
				}

				$progress_bar->tick();
			}

			$progress_bar->finish();

			WP_CLI::line( $cleaned . ' posts have been updated.' );


			WP_CLI::line( 'Process completed: ' . date_i18n( 'Y-m-d H:i:s e' ) );
		}

		private function replace_content ( $content ) {
			return str_replace( 'REPLACE_THIS', 'THAT', $content );
		}

	WP_CLI::add_command( 'update_content', 'Example_Content_Update_CLI_Commands' );
}
