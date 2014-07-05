<?php
if ( defined('WP_CLI') && WP_CLI ) {

	class Example_CLI_Commands extends WP_CLI_Command {

		public function example() {
			WP_CLI::line( 'Process started: ' . date_i18n( 'Y-m-d H:i:s e' ) );
			//Output our start time so we know how long our scripts run.

			$args = array(
				'post_type' => 'post',
				'posts_per_page' => -1,
				'fields' => 'ids',
			);
			$post_ids = get_posts( $args ); //This could be changed to any query you'd want. Meta, User, Taxonomy, etc.

			//Check to make sure we have some posts to update.
			if ( empty( $post_ids ) ) {
				WP_CLI::warning( 'No posts to update.' );
				continue;
			}

			$found_posts = count( $post_ids ); //Count how many posts we grabbed, for our progress bar.
			WP_CLI::line( 'Found ' . $found_posts . ' posts.' ); //Output how many posts we're processing.

			$progress_bar = \WP_CLI\Utils\make_progress_bar( 'Progress:', $found_posts ); //Initialize and output our progress bar.
			$successfully_processed = 0; //Variable we'll increment for each success, so we know how many processed correctly.

			foreach ( $post_ids as $post_id ) {

				$success = $this->do_something( $post_id ); //Process each post. We're assuming this function returns 'true' or 'false'.

				if( $success ) {
					$successfully_processed++;
				}
				$progress_bar->tick(); //Increment our progress bar.
			}

			$progress_bar->finish(); //Complete our progress bar.

			WP_CLI::line( $successfully_processed . ' posts have been updated.' );
			WP_CLI::line( 'Process completed: ' . date_i18n( 'Y-m-d H:i:s e' ) );
		}

		private function do_something( $post_id ){
			//Here is where we'd actually process the post.
			return true;
		}
	}

	WP_CLI::add_command( 'example', 'Example_CLI_Commands' );
}
