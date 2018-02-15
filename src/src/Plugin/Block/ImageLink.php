<?php
namespace Drupal\image_link\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an Image Link block.
 *
 * @Block(
 *   id = "image_link",
 *   admin_label = @Translation("Image Link"),
 *   category = @Translation("Custom"),
 * )
 */
class ImageLink extends BlockBase {

	/**
	 * {@inheritdoc}
	 */
	public function build() {
		return array(
			'#image' => "Image path",
			'#link' => "Image link URL",
			'#theme' => 'image_link_block',
		);
	}
}
?>